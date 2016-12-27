<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\models\TemplateSms;
use common\models\TemplateSmsSearch;
use common\models\UserActivity;
use kartik\widgets\ActiveForm;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * TemplateSmsController implements the CRUD actions for TemplateSms model.
 */
class TemplateSmsController extends BaseBEController
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
                'model_type_default' => UserActivity::ACTION_TARGET_TEMPLATE,
            ],
        ]);
    }

    /**
     * Lists all TemplateSms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplateSmsSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TemplateSms model.
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
     * Creates a new TemplateSms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TemplateSms();

        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->template_createby = Yii::$app->user->id;
            $model->created_at = time();
            $model->updated_at = time();
            if ($model->save(false)) {

                \Yii::$app->getSession()->setFlash('success', 'Thêm mới thành công');

                return $this->redirect(['index']);
            } else {
                // Yii::info($model->getErrors());
                // Yii::$app->getSession()->setFlash('error', 'Lỗi lưu danh mục');
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpload()
    {
        $model = new TemplateSms();
        $check = 0;
        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
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
                        $model = new TemplateSms();
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
                        if ($row == 1) {
                            continue;
                        }
                        if ($rowData[0][2] == 'Active') {
                            $model->status = TemplateSms::STATUS_ACTIVE;
                        } else {
                            $model->status = TemplateSms::STATUS_INACTIVE;
                        }
                        $model->created_at = time();
                        $model->updated_at = time();
                        $model->template_createby = Yii::$app->user->id;
                        $model->template_name = $rowData[0][1];
                        $model->template_content = $rowData[0][3];
                        if ($model->save(false)) {
                            $check = 1;
                        }
                    }
                    if ($check) {
                        \Yii::$app->getSession()->setFlash('success', 'Upload thành công');
                        return $this->redirect(['index']);
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Upload không thành công');
                    }
                } catch (Exception $ex) {

                }
            }else{
                Yii::$app->getSession()->setFlash('error', 'Bạn chưa chọn file tải mẫu để upload');
                return $this->render('upload', [
                    'model' => $model,
                ]);
            }

        } else {
            return $this->render('upload', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TemplateSms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->template_createby = Yii::$app->user->id;
            $model->updated_at = time();
            if ($model->save(false)) {

                \Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công');

                return $this->redirect(['index']);
            } else {
                // Yii::info($model->getErrors());
                // Yii::$app->getSession()->setFlash('error', 'Lỗi lưu danh mục');
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TemplateSms model.
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
     * Finds the TemplateSms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TemplateSms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TemplateSms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDownloadTemplate(){
        $file_name = 'example.xlsx';
        $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@example') . '/';
        $file = $tmp.$file_name;
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
}
