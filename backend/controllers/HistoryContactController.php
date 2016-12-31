<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\helpers\APISMS;
use common\helpers\FileUtils;
use common\helpers\TBApplication;
use common\models\Brandname;
use common\models\ContactDetail;
use common\models\HistoryContact;
use common\models\HistoryContactAsm;
use common\models\HistoryContactSearch;
use common\models\User;
use common\models\UserActivity;
use kartik\form\ActiveForm;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * HistoryContactController implements the CRUD actions for HistoryContact model.
 */
class HistoryContactController extends BaseBEController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            [
                'class' => ActionLogTracking::className(),
                'user' => Yii::$app->user,
                'model_type_default' => UserActivity::ACTION_TARGET_HISTORY_CONTACT,
            ],
        ]);
    }

    /**
     * Lists all HistoryContact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HistoryContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HistoryContact model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HistoryContact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = 1)
    {
        $model = new HistoryContact();

        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->is_campaign = HistoryContact::IS_CAMPAIGN;
            $model->member_by = Yii::$app->user->id;
            $brand = Brandname::findOne(['id' => $model->brandname_id]);
            /** @var $brand Brandname */
            if ($model->is_send) {
                $model->send_schedule = strtotime($model->send_schedule);
            }
            $model->save(false);
            $max_numbersms = User::findOne(['id' => Yii::$app->user->id])->number_sms;

            $i = 0;
            $total = 0; // tong tin nhan
            $total_sucess = 0; // tin nhan success
            $content = '';
            if ($type == 1) {
                foreach ($model->contact_id as $value) {
                    $contactDetail = ContactDetail::find()
                        ->andWhere(['contact_id' => $value])
                        ->andWhere(['status' => ContactDetail::STATUS_ACTIVE])
                        ->all();
                    $total1 = 0;
                    $total1_success = 0;
                    FileUtils::errorLog("Bắt đầu gửi tin nhắn");
                    foreach ($contactDetail as $detail) {
                        /** @var $detail ContactDetail */
                        $phone_number = $detail->phone_number;
                        $contact_content = HistoryContact::getTemplateContact($model->content, $detail->id);
                        $contact_content = TBApplication::removesign($contact_content, '');
                        $sotin = strlen($contact_content) / 160;
                        if (strlen($contact_content) >= 0 && strlen($contact_content) < 160)
                            $sotin = 1;
                        $content_number = round($sotin);
                        $content = $contact_content;
                        $result_send = 0;
                        if ($model->type == HistoryContact::TYPE_CSKH && !$model->is_send && $total_sucess <= $max_numbersms && $total1 <= $max_numbersms) {
                            $callAPI = new APISMS();
                            $result_send = $callAPI->sent($brand->brand_username, $brand->brand_password, $brand->brandname, $phone_number, $contact_content, $detail->id);
                            FileUtils::errorLog(trim($result_send) . " cua sdt " . $phone_number . " co brandname la " . $brand->brandname);
                        }
                        $historyContactAsm = new HistoryContactAsm();
                        $historyContactAsm->history_contact_id = $model->id;
                        $historyContactAsm->contact_id = $detail->id;
                        $historyContactAsm->created_at = time();
                        $historyContactAsm->updated_at = time();
                        $historyContactAsm->content_number = $content_number;
                        $historyContactAsm->api_sms_id = $result_send;
                        if (trim($result_send) == "0|Success") {
                            FileUtils::errorLog("Gui tin nhan thanh cong co sdt " . $phone_number . " co brandname la " . $brand->brandname);
                            $historyContactAsm->history_contact_status = 1;
                            $total1_success += $content_number;
                        } elseif ($model->type == HistoryContact::TYPE_ADV) {
                            $historyContactAsm->history_contact_status = -1;
                        } else {
                            $historyContactAsm->history_contact_status = 0;
                        }
                        if ($total_sucess > $max_numbersms || $total1_success > $max_numbersms) {
                            $historyContactAsm->history_contact_status = 0;
                        }
                        if ($historyContactAsm->save(false)) {
                            $total1 += $content_number;
                        }
                    }
                    $total += $total1;
                    $total_sucess += $total1_success;
                    $i++;
                }
            } else {
                $file_download = UploadedFile::getInstance($model, 'uploadedFile');
                if ($file_download) {
                    $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file_download->extension;
                    $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_downloads') . '/';
                    if (!file_exists($tmp)) {
                        mkdir($tmp, 0777, true);
                    }
                    $file_download->saveAs($tmp . $file_name);
                    try {
                        $inputFileType = \PHPExcel_IOFactory::identify($tmp . $file_name);
                        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($tmp . $file_name);
                        $sheet = $objPHPExcel->getSheet(0);
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();
                        for ($row = 1; $row <= $highestRow; $row++) {
                            $modelContact = new ContactDetail();
                            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
                            if ($row == 1) {
                                continue;
                            }
                            FileUtils::errorLog("Bắt đầu gửi tin nhắn");
                            $modelContact->fullname = $rowData[0][0];
                            $modelContact->email = $rowData[0][1];
                            $modelContact->phone_number = $rowData[0][2];
                            $modelContact->address = $rowData[0][3];
                            $modelContact->company = $rowData[0][4];
                            $modelContact->birthday = strtotime(str_replace('/', '-', $rowData[0][5]));

                            if ($rowData[0][6] == 'Nam') {
                                $modelContact->gender = ContactDetail::GENDER_MALE;
                            } else {
                                $modelContact->gender = ContactDetail::GENDER_FEMAILE;
                            }
                            $modelContact->notes = $rowData[0][7];
                            $modelContact->created_at = time();
                            $modelContact->updated_at = time();
                            $modelContact->created_by = Yii::$app->user->id;
                            $modelContact->status = ContactDetail::STATUS_ACTIVE;
                            $modelContact->save(false);
                            $phone_number = $rowData[0][2];
                            $contact_content = HistoryContact::getTemplateContact($model->content, $modelContact->id);
                            $contact_content = TBApplication::removesign($contact_content, '');
                            $sotin = strlen($contact_content) / 160;
                            if (strlen($contact_content) >= 0 && strlen($contact_content) < 160)
                                $sotin = 1;
                            $content_number = round($sotin);
                            $content = $contact_content;
                            $result_send = 0;
                            if ($model->type == HistoryContact::TYPE_CSKH && !$model->is_send && $total_sucess <= $max_numbersms) {
                                $callAPI = new APISMS();
                                $result_send = $callAPI->sent($brand->brand_username, $brand->brand_password, $brand->brandname, $phone_number, $contact_content, $modelContact->id);
                                FileUtils::errorLog(trim($result_send) . " cua sdt " . $phone_number . " co brandname la " . $brand->brandname);
                            }
                            $historyContactAsm = new HistoryContactAsm();
                            $historyContactAsm->history_contact_id = $model->id;
                            $historyContactAsm->contact_id = $modelContact->id;
                            $historyContactAsm->created_at = time();
                            $historyContactAsm->updated_at = time();
                            $historyContactAsm->content_number = $content_number;
                            $historyContactAsm->api_sms_id = $result_send;
                            if (trim($result_send) == "0|Success") {
                                FileUtils::errorLog("Gui tin nhan thanh cong cua sdt " . $phone_number . " co brandname la " . $brand->brandname);
                                $historyContactAsm->history_contact_status = 1;
                                $total_sucess += $content_number;
                            } elseif ($model->type == HistoryContact::TYPE_ADV) {
                                $historyContactAsm->history_contact_status = -1;
                            } else {
                                $historyContactAsm->history_contact_status = 0;
                            }
                            if ($total_sucess > $max_numbersms) {
                                $historyContactAsm->history_contact_status = 0;
                            }
                            if ($historyContactAsm->save(false)) {
                                $total += $content_number;
                            }

                        }
                    } catch (Exception $ex) {
                    }
                }
            }
            $model = $this->findModel($model->id);
            $model->total_sms = $total;
            $model->total_success = $total_sucess;
            $model->save(false);
            $modelUser = User::findOne(['id' => Yii::$app->user->id]);
            $modelUser->number_sms = $max_numbersms - $total_sucess;
            $modelUser->save(false);
            FileUtils::errorLog("**** KET THUC GUI TIN ****");
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'type' => $type
            ]);
        }
    }

    /**
     * Updates an existing HistoryContact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing HistoryContact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the HistoryContact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HistoryContact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HistoryContact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCronJob()
    {
        $date_current = strtotime(date('Y-m-d H:i:s')) + 7 * 3600;
        $date_1 = $date_current + 3600; // 1h sau
        $history_contact = HistoryContact::find()
            ->andWhere('send_schedule is not null')
            ->andWhere('send_schedule >= :t', [':t' => $date_current])
            ->andWhere('send_schedule <= :t1', [':t1' => $date_1])
            ->all();
        $max_numbersms = User::findOne(['id' => Yii::$app->user->id])->number_sms;
        $total = 0; // tong tin nhan
        $total_sucess = 0; // tin nhan success
        FileUtils::errorLog("**** BAT DAU GUI TIN THEO LICH *****");
        foreach ($history_contact as $history) {
            /** @var $history HistoryContact */
            /** @var  $brandname Brandname */
            $brandname = Brandname::findOne(['id' => $history->brandname_id]);
            $contact_detail = ContactDetail::find()
                ->innerJoin('history_contact_asm', 'history_contact_asm.contact_id = contact_detail.id')
                ->innerJoin('history_contact', 'history_contact.id = history_contact_asm.history_contact_id')
                ->andWhere(['history_contact.id' => $history->id])->all();
            foreach ($contact_detail as $item) {
                /** @var $item ContactDetail */
                $result_send = 0;
                $contact_content = HistoryContact::getTemplateContact($history->content, $item->id);
                $contact_content = TBApplication::removesign($contact_content, '');
                $sotin = strlen($contact_content) / 160;
                if (strlen($contact_content) >= 0 && strlen($contact_content) < 160)
                    $sotin = 1;
                $content_number = round($sotin);
                if ($history->type == HistoryContact::TYPE_CSKH && $total_sucess <= $max_numbersms) {
                    $callAPI = new APISMS();
                    $result_send = $callAPI->sent($brandname->brand_username, $brandname->brand_password, $brandname->brandname, $item->phone_number, $history->content, $item->id);
                } else {
                    $result_send = 0;
                }
                FileUtils::errorLog($result_send . " gui tin nhan co sdt " . $item->phone_number . " voi brandname la : " . $brandname->brandname);
                $model = HistoryContactAsm::find()
                    ->andWhere(['history_contact_id' => $history->id])
                    ->andWhere(['contact_id' => $item->id])
                    ->one();
                $model->api_sms_id = $result_send;
                if (trim($result_send) == "0|Success") {
                    $model->history_contact_status = 1;
                    $total_sucess += $content_number;
                } elseif ($history->type == HistoryContact::TYPE_ADV) {
                    $model->history_contact_status = -1;
                } else {
                    $model->history_contact_status = 0;
                }
                if ($total_sucess > $max_numbersms) {
                    $model->history_contact_status = 0;
                }
                if ($model->save(false)) {
                    $total += $content_number;
                }
            }
            $history->updated_at = time();
            $history->total_success = $total_sucess;
            $history->save(false);
            $modelUser = User::findOne(['id' => Yii::$app->user->id]);
            $modelUser->number_sms = $max_numbersms - $total_sucess;
            $modelUser->save(false);
            FileUtils::errorLog("**** KET THUC GUI TIN NHAN THEO LICH ****");
        }
    }

    public function actionSend()
    {
        if (isset($_POST['arr_member']) && isset($_POST['content'])) {
            $content = $_POST['content'];
            $user_current = User::findOne(['id' => Yii::$app->user->id]);
            $brand = Brandname::findOne(['id' => $user_current->brandname_id]);
            FileUtils::errorLog("*****BAT DAU GUI TIN CHO USER*****");
            FileUtils::infoLog("*****BAT DAU GUI TIN CHO USER*****");

            $history_contact = new HistoryContact();
            $history_contact->type = HistoryContact::TYPE_CSKH;
            $history_contact->brandname_id = $user_current->brandname_id;
            $history_contact->content = $content;
            $history_contact->campain_name = 'NOT CAMPAIN';
            $history_contact->created_at = time();
            $history_contact->updated_at = time();
            $history_contact->member_by = Yii::$app->user->id;
            $history_contact->is_campaign = HistoryContact::NOT_CAMPAIGN;
            $history_contact->save(false);
            $total_sms = 0;
            $total_sucess = 0;
            for ($i = 0; $i < sizeof($_POST['arr_member']); $i++) {
                FileUtils::errorLog("********Bắt đầu gửi tin nhắn ********");
                $historyContactAsm = new HistoryContactAsm();
                $historyContactAsm->history_contact_id = $history_contact->id;
                $historyContactAsm->contact_id = $_POST['arr_member'][$i];
                $historyContactAsm->created_at = time();
                $historyContactAsm->updated_at = time();
                $phone_number = ContactDetail::findOne(['id' => $_POST['arr_member'][$i]])->phone_number;
                $contact_content = HistoryContact::getTemplateContact($content, $_POST['arr_member'][$i]);
                $contact_content = TBApplication::removesign($contact_content, '');
                $sotin = strlen($contact_content) / 160;
                if (strlen($contact_content) >= 0 && strlen($contact_content) < 160)
                    $sotin = 1;
                $content_number = round($sotin);
                $historyContactAsm->content_number = $content_number;
                $result_send = 0;
                if ($user_current->number_sms >= $total_sucess) {
                    $callAPI = new APISMS();
                    $result_send = $callAPI->sent($brand->brand_username, $brand->brand_password, $brand->brandname, $phone_number, $contact_content, $_POST['arr_member'][$i]);
                    FileUtils::errorLog(trim($result_send) . " cua sdt " . $phone_number . " co brandname la " . $brand->brandname);
                }
                $historyContactAsm->api_sms_id = $result_send;
                if (trim($result_send) == "0|Success") {
                    FileUtils::errorLog("Gui tin nhan thanh cong co sdt " . $phone_number . " co brandname la " . $brand->brandname);
                    $historyContactAsm->history_contact_status = 1;
                    $total_sucess += $content_number;
                } else {
                    $historyContactAsm->history_contact_status = 0;
                }
                $total_sms += $content_number;
                $historyContactAsm->save(false);
                FileUtils::errorLog("**********Kết thúc gửi tin nhắn*********");
            }
            $model = HistoryContact::findOne(['id' => $history_contact->id]);
            $model->total_success = $total_sucess;
            $model->total_sms = $total_sms;
            $model->save(false);
            $user_current->number_sms = $user_current->number_sms - $total_sucess;
            if ($user_current->save(false)) {
                FileUtils::errorLog("*****KET THUC THANH CONG  GUI TIN CHO USER*****");
                FileUtils::infoLog("*****KET THUC THANH CONG GUI TIN CHO USER*****");
                echo '{"status":"ok"}';
            } else {
                FileUtils::errorLog("*****KET THUC THAT BAI  GUI TIN CHO USER*****");
                FileUtils::infoLog("*****KET THUC THAT BAI GUI TIN CHO USER*****");
                echo '{"status":"nok"}';
            }

        } else {
            echo '{"status":"nok"}';
        }
    }
}
