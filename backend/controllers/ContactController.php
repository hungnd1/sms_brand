<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\helpers\TBApplication;
use common\models\Contact;
use common\models\ContactDetail;
use common\models\ContactDetailSearch;
use common\models\ContactSearch;
use common\models\ContactSearch_;
use common\models\HistoryContact;
use common\models\User;
use common\models\UserActivity;
use Exception;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends BaseBEController
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
                'model_type_default' => UserActivity::ACTION_TARGET_CONTACT,
            ],
        ]);
    }

    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContactSearch();
        $searchModel1 = new ContactSearch_();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, null);
        $dataProviderClass = $searchModel1->search(Yii::$app->request->queryParams, 1);
        $message  = '';
        if (Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILY ||
            Yii::$app->user->identity->level == User::USER_LEVEL_ADMIN ||
            Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI ||
            Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANGADMIN
        ) {
            $time_send = User::findOne(['id' => Yii::$app->user->id])->time_send;
            $time_send_before = time() - $time_send * 24 * 60 * 60;

            $listContact = ContactDetail::find()->andWhere(['status'=>ContactDetail::STATUS_ACTIVE,'created_by'=>Yii::$app->user->id])->count();

            $listContactMessage = ContactDetail::find()
                ->innerJoin('history_contact_asm', 'history_contact_asm.contact_id = contact_detail.id')
                ->andWhere(['contact_detail.status' => ContactDetail::STATUS_ACTIVE])
                ->andWhere(['contact_detail.created_by' => Yii::$app->user->id])
                ->andWhere(['history_contact_asm.history_contact_status' => HistoryContact::STATUS_SUCCESS])
                ->andWhere(['<=', 'history_contact_asm.created_at', time()])
                ->andWhere(['>=', 'history_contact_asm.created_at', $time_send_before])
                ->orderBy(['history_contact_asm.created_at' => SORT_DESC])
                ->distinct('contact_detail.id')->count();
            $listContactNoMessage = intval($listContact) - intval($listContactMessage);
            $message = 'Hệ thống phát hiện ra đang  có '. $listContactNoMessage .'/ '.$listContact.' người sử dụng của hệ thống đã quá '.$time_send.' ngày liên tiếp chưa nhận được thông báo gì từ hệ thống gửi tới';
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'searchModel1' => $searchModel1,
            'dataProvider' => $dataProvider,
            'dataProviderClass' => $dataProviderClass,
            'message' => $message
        ]);
    }

    /**
     * Displays a single Contact model.
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
     * Creates a new Contact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = null)
    {
        $model = new Contact();

        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $check = 0;
        $check1 = 0;
        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->created_by = Yii::$app->user->id;
            if ($model->save(false)) {
                $check = 1;
            }
            $file_download = UploadedFile::getInstance($model, 'file');
            if ($file_download) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file_download->extension;
                $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_downloads') . '/';
                if (!file_exists($tmp)) {
                    mkdir($tmp, 0777, true);
                }
                if ($file_download->saveAs($tmp . $file_name)) {
                    $model->file = $file_name;
                }
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
                        $modelContact->fullname = $rowData[0][0];
                        $modelContact->email = $rowData[0][1];
                        $modelContact->phone_number = TBApplication::convert84($rowData[0][2]);
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
                        $modelContact->contact_id = $model->id;
                        $modelContact->status = ContactDetail::STATUS_ACTIVE;
                        if ($modelContact->save(false)) {
                            $check1 = 1;
                        }
                    }

                } catch (Exception $ex) {
                }
            } else {
                $check1 = 1;
            }
            if (!$check) {
                Yii::$app->session->setFlash('error', 'Thêm danh bạ không thành công');
                if ($type) {
                    return $this->render('_create', [
                        'model' => $model,
                    ]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

            } else if (!$check1) {
                Yii::$app->session->setFlash('error', 'Upload danh bạ chi tiết xảy ra lỗi');
            } else {
                Yii::$app->session->setFlash('success', 'Thêm danh bạ thành công');
                return $this->redirect(['index']);
            }
        } else {
            if ($type) {
                return $this->render('_create', [
                    'model' => $model,
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Contact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $type = $model->path;
        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $check = 0;
        $check1 = 0;
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time();
            if ($model->save(false)) {
                $check = 1;
            }
            $file_download = UploadedFile::getInstance($model, 'file');
            if ($file_download) {
                $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $file_download->extension;
                $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_downloads') . '/';
                if (!file_exists($tmp)) {
                    mkdir($tmp, 0777, true);
                }
                if ($file_download->saveAs($tmp . $file_name)) {
                    $model->file = $file_name;
                }
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
                        $modelContact->fullname = $rowData[0][0];
                        $modelContact->email = $rowData[0][1];
                        $modelContact->phone_number = TBApplication::convert84($rowData[0][2]);
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
                        $modelContact->contact_id = $model->id;
                        $modelContact->status = ContactDetail::STATUS_ACTIVE;
                        if ($modelContact->save(false)) {
                            $check1 = 1;
                        }
                    }

                } catch (Exception $ex) {
                }
            } else {
                $check1 = 1;
            }
            if (!$check) {
                Yii::$app->session->setFlash('error', 'Cập nhật danh bạ không thành công');
                if ($type) {
                    return $this->render('_update', [
                        'model' => $model,
                    ]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

            } else if (!$check1) {
                Yii::$app->session->setFlash('error', 'Upload danh bạ chi tiết xảy ra lỗi');
            } else {
                Yii::$app->session->setFlash('success', 'Cập nhật danh bạ thành công');
                return $this->redirect(['index']);
            }
        } else {
            if ($type) {
                return $this->render('_update', [
                    'model' => $model,
                ]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Deletes an existing Contact model.
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
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDownloadTemplate()
    {
        $file_name = 'contact.xls';
        $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@example') . '/';
        $file = $tmp . $file_name;
        if (file_exists($file)) {

            header("Content-Length: " . filesize($file));
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename=" . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            ob_clean();
            flush();

            readfile($file);
        } else {
            echo 'The file "contact.xls" does not exist';
        }
    }

    public function actionMessage(){

        $searchModel = new ContactDetailSearch();
        $dataProvider = $searchModel->message(Yii::$app->request->queryParams);
        $model = new ContactDetail();
        return $this->render('message', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
}
