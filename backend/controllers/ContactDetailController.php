<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\helpers\TBApplication;
use common\models\Comment;
use common\models\ContactDetail;
use common\models\ContactDetailSearch;
use common\models\HistoryContactAsm;
use common\models\TemplateComment;
use common\models\UserActivity;
use kartik\widgets\ActiveForm;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ContactDetailController implements the CRUD actions for ContactDetail model.
 */
class ContactDetailController extends BaseBEController
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
                'model_type_default' => UserActivity::ACTION_TARGET_CONTACT_DETAIL,
            ],
        ]);
    }


    /**
     * Lists all ContactDetail models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new ContactDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);
        $model = new ContactDetail();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'model' => $model
        ]);
    }

    /**
     * Displays a single ContactDetail model.
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
     * Creates a new ContactDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ContactDetail();

        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->created_by = Yii::$app->user->id;
            $model->contact_id = $id;
            $model->birthday = strtotime($model->birthday);
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Thêm chi tiết thành công');
                return $this->redirect(['index', 'id' => $id]);
            } else {
                Yii::$app->session->setFlash('error', 'Thêm chi tiết không thành công');
                return $this->render('create', [
                    'model' => $model,
                    'id' => $id
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'id' => $id
            ]);
        }
    }

    /**
     * Updates an existing ContactDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->birthday = date('d-m-Y', $model->birthday);

        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time();
            $model->birthday = strtotime($model->birthday);

            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Cập nhật thành công');
                return $this->redirect(['index', 'id' => $model->contact_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Cập nhật không thành công');
                return $this->render('update', [
                    'model' => $model,
                    'id' => $id
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'id' => $id
            ]);
        }
    }

    /**
     * Deletes an existing ContactDetail model.
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
     * Finds the ContactDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContactDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContactDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpload()
    {

        if (0 < $_FILES['file']['error']) {
            echo '{"status":"nok"}';
        } else {
            $file_name = Yii::$app->user->id . '.' . uniqid() . time() . '.' . $_FILES['file']['name'];
            $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_downloads') . '/';
            if (!file_exists($tmp)) {
                mkdir($tmp, 0777, true);
            }
            $fileUpload = $tmp . $file_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $fileUpload);
            try {
                $inputFileType = \PHPExcel_IOFactory::identify($fileUpload);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($fileUpload);
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
//                    $modelContact->contact_id = $id;
                    $modelContact->status = ContactDetail::STATUS_ACTIVE;
                    $modelContact->save(false);
                }
            } catch (Exception $ex) {
            }
            echo '{"status":"ok"}';
        }
    }

    public function actionUpdateContact()
    {
        if (isset($_POST['id'])) {
            $contactDetail = ContactDetail::find()
                ->andWhere('contact_id is null')
                ->andWhere(['status' => ContactDetail::STATUS_ACTIVE])
                ->all();
            foreach ($contactDetail as $item) {
                $contact = ContactDetail::findOne(['id' => $item->id]);
                $contact->contact_id = $_POST['id'];
                $contact->save(false);
            }
            echo '{"status":"ok"}';
        } else {
            echo '{"status":"nok"}';
        }
    }

    public function actionShareContact()
    {
        if (isset($_POST['arr_member']) && isset($_POST['contactId'])) {
            $check = 0;
            for ($i = 0; $i < sizeof($_POST['arr_member']); $i++) {
                $modelContactDetail = ContactDetail::findOne(['id' => $_POST['arr_member'][$i]]);
                $modelContactDetail->contact_id = $_POST['contactId'];
                if ($modelContactDetail->save(false)) {
                    $check = 1;
                } else {
                    $check = 0;
                    break;
                }

            }
            if ($check) {
                echo '{"status":"ok"}';
            } else {
                echo '{"status":"nok"}';
            }
        } else {
            echo '{"status":"nok"}';
        }
    }

    public function actionBirthday()
    {
        $searchModel = new ContactDetailSearch();
        $dataProvider = $searchModel->searchBirthday(Yii::$app->request->queryParams);
        $model = new ContactDetail();
        return $this->render('birthday', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionSearch()
    {

        $searchModel = new ContactDetailSearch();
        $model = new HistoryContactAsm();
        $dataProvider = $searchModel->searchHistory();
        if ($model->load(Yii::$app->request->post())) {
            $dataProvider = $searchModel->searchHistory($model);
        }
        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionReport()
    {

        $searchModel = new ContactDetailSearch();
        $model = new HistoryContactAsm();
        $dataProvider = $searchModel->searchHistory();
        if ($model->load(Yii::$app->request->post())) {
            $dataProvider = $searchModel->searchHistory($model);
        }
        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionReportMonth()
    {

        $searchModel = new ContactDetailSearch();
        $model = new HistoryContactAsm();
        $dataProvider = $searchModel->searchHistory();
        if ($model->load(Yii::$app->request->post())) {
            $dataProvider = $searchModel->searchHistory($model);
        }
        return $this->render('report_month', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionComment()
    {
        $searchModel = new ContactDetailSearch();
        $dataProvider = $searchModel->comment();
        $model = new ContactDetail();
        if ($model->load(Yii::$app->request->post())) {
            $dataProvider = $searchModel->comment($model);
        }
        if (isset($_POST['hasEditable'])) {
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                $id = $post['editableKey'];
                $timestamp = strtotime('today midnight');
                $index = $post['editableIndex'];
                $create_at_end = $timestamp + (60 * 60 * 24);

                $comment = Comment::find()
                    ->andWhere(['id_contact_detail' => $id])
                    ->andWhere(['is_month' => Comment::NOT_MONTH])
                    ->andWhere(['>=', 'updated_at', $timestamp])
                    ->andWhere(['<=', 'updated_at', $create_at_end])
                    ->orderBy(['updated_at' => SORT_DESC])->one();
                if ($comment) {
                    if (isset($post['ContactDetail'][$index]['comment'])) {
                        $content = $post['ContactDetail'][$index]['comment'];
                        $comment->content = $content;
                    }
                    if (isset($post['ContactDetail'][$index]['comment_bonus'])) {
                        $comment->content_bonus = $post['ContactDetail'][$index]['comment_bonus'];
                    }
                    if ($comment->save(false)) {
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    }
                } else {
                    $comment = new Comment();
                    $comment->id_contact_detail = $id;
                    $comment->created_at = $timestamp;
                    $comment->updated_at = time();
                    $comment->is_month = Comment::NOT_MONTH;
                    if (isset($post['ContactDetail'][$index]['comment'])) {
                        $content = $post['ContactDetail'][$index]['comment'];
                        $comment->content = $content;
                    }
                    if (isset($post['ContactDetail'][$index]['comment_bonus'])) {
                        $comment->content_bonus = $post['ContactDetail'][$index]['comment_bonus'];
                    }
                    if ($comment->save(false)) {
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    }
                }
            } else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }
            return;
        }

        return $this->render('comment', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionCommentMonth()
    {
        $searchModel = new ContactDetailSearch();
        $dataProvider = $searchModel->commentMonth();
        $model = new ContactDetail();
        if ($model->load(Yii::$app->request->post())) {
            $dataProvider = $searchModel->commentMonth($model);
        }
        if (isset($_POST['hasEditable'])) {
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                $id = $post['editableKey'];
                $timestamp = strtotime('today midnight');
                $index = $post['editableIndex'];
                $create_at_end = $timestamp + (60 * 60 * 24);
                $comment = Comment::find()
                    ->andWhere(['id_contact_detail' => $id])
                    ->andWhere(['is_month' => Comment::IS_MONTH])
                    ->andWhere(['>=', 'updated_at', $timestamp])
                    ->andWhere(['<=', 'updated_at', $create_at_end])
                    ->orderBy(['updated_at' => SORT_DESC])->one();
                if ($comment) {
                    if (isset($post['ContactDetail'][$index]['comment'])) {
                        $content = $post['ContactDetail'][$index]['comment'];
                        $comment->content = $content;
                    }
                    if (isset($post['ContactDetail'][$index]['comment_bonus'])) {
                        $comment->content_bonus = $post['ContactDetail'][$index]['comment_bonus'];
                    }
                    if ($comment->save(false)) {
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    }
                } else {
                    $comment = new Comment();
                    $comment->id_contact_detail = $id;
                    $comment->created_at = $timestamp;
                    $comment->updated_at = time();
                    $comment->is_month = Comment::IS_MONTH;
                    if (isset($post['ContactDetail'][$index]['comment'])) {
                        $content = $post['ContactDetail'][$index]['comment'];
                            $comment->content = $content;
                    }
                    if (isset($post['ContactDetail'][$index]['comment_bonus'])) {
                        $comment->content_bonus = $post['ContactDetail'][$index]['comment_bonus'];
                    }
                    if ($comment->save(false)) {
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    }
                }
            } else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }
            return;
        }

        return $this->render('comment_', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }
}
