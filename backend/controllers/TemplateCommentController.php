<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\models\UserActivity;
use kartik\form\ActiveForm;
use PHPExcel;
use PHPExcel_IOFactory;
use Yii;
use common\models\TemplateComment;
use common\models\TemplateCommentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * TemplateCommentController implements the CRUD actions for TemplateComment model.
 */
class TemplateCommentController extends BaseBEController
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
                'model_type_default' => UserActivity::ACTION_TARGET_TYPE_OTHER,
            ],
        ]);
    }

    /**
     * Lists all TemplateComment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplateCommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TemplateComment model.
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
     * Creates a new TemplateComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TemplateComment();

        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->created_by = Yii::$app->user->id;
            $model->updated_at = time();
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Thêm nhận xét mẫu thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TemplateComment model.
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
            $model->updated_at = time();
            $model->created_by = Yii::$app->user->id;
            $model->save(false);
            if ($model->save(false)) {
                \Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công');

                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TemplateComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        \Yii::$app->getSession()->setFlash('success', 'Xóa thành công');
        return $this->redirect(['index']);
    }

    /**
     * Finds the TemplateComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TemplateComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TemplateComment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImport()
    {
        $model = new TemplateComment();
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'uploadedFile');
            if ($file) {
                $file_name = uniqid() . time() . '.' . $file->extension;
                if ($file->saveAs(Yii::getAlias('@webroot') . "/" . Yii::getAlias('@file_downloads') . "/" . $file_name)) {
                    $objPHPExcel = PHPExcel_IOFactory::load(Yii::getAlias('@file_downloads') . "/" . $file_name);
                    $sheetData   = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    if (sizeof($sheetData) > 0) {
                        $dataStarted = false;
                        $idx         = 0;
                        $numedit =0;
                        $errorsArr   = [];
                        foreach ($sheetData as $row) {
                            $idx++;
                            if ($row['A'] == 'STT') {
                                $dataStarted = true;
                                continue;
                            }
                            if (!$dataStarted) {
                                continue;
                            }

                            $errors = [];
                            $comment    = trim($row['B']);
                            if(empty($comment)){
                                $errors[TemplateComment::COMMENT] = 'Nội dung nhận xét  không  được để  trống';
                            }
                            if(!empty($comment)){
                                $template                  = new TemplateComment();
                                $template->comment     = $this->getImportedValue(TemplateComment::COMMENT, $row['B']);
                                $template->status = TemplateComment::STATUS_ACTIVE;
                                $template->created_by = Yii::$app->user->id;
                                $template->created_at = time();
                                $template->updated_at = time();
                                if($template->save(false)){
                                    $numedit++;
                                }
                            }
                        }
                        Yii::trace($errorsArr);
                        if ($errorsArr) {
                            $objPHPExcel = new PHPExcel;
                            $objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
                            $objSheet    = $objPHPExcel->getActiveSheet();
                            $objSheet->getCell('A1')->setValue(Yii::t("app","Thiết bị"));
                            $objSheet->mergeCells('A1:C1');

                            // headers
                            $objSheet->getCell('A2')->setValue(Yii::t("app","STT"));
                            $objSheet->getCell('B2')->setValue(Yii::t("app","Nội dung"));

                            // data
                            $rowOrderError = 3;
                            foreach ($errorsArr as $order => $errors) {
                                $objSheet->getCell($this->getCell(TemplateComment::IPT_ORDER, $rowOrderError))->setValue($order);
                                foreach ($errors as $attr => $error) {
                                    $objSheet->getCell($this->getCell($attr, $rowOrderError))->setValue($error);
                                }
                                $rowOrderError++;
                            }

                            // autosize the columns
                            $objSheet->getColumnDimension('A')->setAutoSize(true);
                            $objSheet->getColumnDimension('B')->setAutoSize(true);

                            $error_file_name = basename($file_name) . '_err.' . $file->extension;
                            $objWriter->save(Yii::getAlias('@file_downloads') . "/" . $error_file_name);
                            Yii::$app->getSession()->setFlash('error', Yii::t("app","Có lỗi xảy ra trong quá trình upload. Vui lòng tải liên kết bên dưới và xem chi tiết lỗi trong file"));
                            $model            = new TemplateComment();
                            $model->errorFile = Yii::getAlias('@file_downloads') . "/" . $error_file_name;
                            return $this->render('import', [
                                'model' => $model,
                            ]);
                        }

                        Yii::$app->getSession()->setFlash('success', Yii::t("app","Đã import thành công "). $numedit.Yii::t("app"," mẫu nhận xét."));
//                        return $this->actionIndex();
                        return $this->redirect(['index']);
                    }
                }
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t("app","Có lỗi xảy ra trong quá trình upload. Vui lòng thử lại"));
                $model = new TemplateComment();
                return $this->render('import', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('import', [
                'model' => $model,
            ]);
        }
    }

    private function getImportedValue($attr, $value)
    {
        $value = trim($value);
        switch ($attr) {
            case TemplateComment::COMMENT:
                return $value;
        }
        return $value;
    }
    private function getCell($attr, $rowIdx)
    {
        switch ($attr) {
            case TemplateComment::IPT_ORDER:
                return "A$rowIdx";
            case TemplateComment::COMMENT:
                return "B$rowIdx";
        }
        return '';
    }
}
