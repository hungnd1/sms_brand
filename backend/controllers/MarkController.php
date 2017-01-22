<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\models\Contact;
use common\models\ContactDetail;
use common\models\Mark;
use common\models\Subject;
use common\models\UserActivity;
use kartik\widgets\ActiveForm;
use PHPExcel_IOFactory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * MarkController implements the CRUD actions for Mark model.
 */
class MarkController extends BaseBEController
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
                'model_type_default' => UserActivity::ACTION_TARGET_MARK,
            ],
        ]);
    }

    /**
     * Lists all Mark models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ArrayDataProvider();
        $model = new Mark();
        $classes = Contact::getAllClasses()
            ->andWhere('contact_name != \'' . Contact::STUDENT_GRADUATED . '\'')
            ->all();
        $subjects = Subject::find()->all();
        $dataContact = ArrayHelper::map($classes, 'id', 'contact_name');
        $dataSubject = ArrayHelper::map($subjects, 'id', 'name');

        if (count($classes) < 1) {
            Yii::$app->getSession()->setFlash('error', 'Lớp chưa được tạo trên hệ thống');
        }

        if (count($subjects) < 1) {
            Yii::$app->getSession()->setFlash('error', 'Môn học chưa được tạo trên hệ thống');
        }

        if (count($classes) > 0 && count($subjects) > 0) {
            $dataProvider = new ActiveDataProvider([
                'query' => Mark::find()->where(['class_id' => $classes[0]->id, 'semester' => 1, 'subject_id' => $subjects[0]->id]),
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $dataProvider = new ActiveDataProvider([
                'query' => Mark::find()->where(['class_id' => $model->class_id, 'semester' => $model->semester, 'subject_id' => $model->subject_id]),
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'dataContact' => $dataContact,
            'dataSubject' => $dataSubject,
        ]);
    }

    /**
     * @return string
     */
    public function actionUpload()
    {
        $model = new Mark();
        $check = 0;
        $post = Yii::$app->request->post();

        // download template
        if ($model->load($post) && strcmp($model->action, "download") == 0) {
            $this->downloadTemplate($model);
            $model->setScenario('admin_create_update');
            return $this->render('upload', [
                'model' => $model,
            ]);
        }

        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($post)) {

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
                    $sheets = $objPHPExcel->getAllSheets();

                    // validate class
                    $class = Contact::findOne($model->class_id);
                    if (is_null($class)) {
                        Yii::$app->getSession()->setFlash('error', 'Lớp chưa tạo trên hệ thống');
                        return $this->redirect(['index']);
                    }

                    foreach ($sheets as $item) {

                        $highestRow = $item->getHighestRow();
                        $highestColumn = $item->getHighestColumn();

                        for ($row = 11; $row <= $highestRow; $row++) {

                            $rowData = $item->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);

                            $mark = Mark::findOne(['student_id' => $rowData[0][2], 'subject_id' => $rowData[0][1], 'class_id' => $model->class_id, 'semester' => $model->semester]);

                            if (is_null($mark)) {
                                $mark = new Mark();
                                $mark->created_at = time();
                                $mark->created_by = Yii::$app->user->id;
                                $mark->student_id = $rowData[0][2];
                                $mark->subject_id = intval($rowData[0][1]);
                                $mark->class_id = $model->class_id;
                                $mark->semester = $model->semester;
                            } else {
                                $mark->updated_at = time();
                                $mark->updated_by = Yii::$app->user->id;
                            }

                            // set marks
                            $mark_str = '';
                            for ($i = 4; $i < ord($highestColumn) - ord('A'); $i++) {
                                if (is_null($rowData[0][$i])) {
                                    $mark_str = $mark_str . 'N;';
                                } else {
                                    $mark_str = $mark_str . $rowData[0][$i] . ';';
                                }
                            }
                            $mark->marks = $mark_str;

                            if ($mark->save(false)) {
                                $check = 1;
                            }
                        }
                    }

                    if ($check) {
                        Yii::$app->getSession()->setFlash('success', 'Upload thành công');
                    } else {
                        Yii::$app->getSession()->setFlash('error', 'Upload không thành công');
                    }
                } catch (Exception $ex) {
                }
            } else {
                Yii::$app->getSession()->setFlash('error', 'Bạn chưa chọn file tải mẫu để upload');
                return $this->render('upload', [
                    'model' => $model,
                ]);
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * download template for upload action
     */
    public function downloadTemplate($model)
    {

        $file_name = 'Mark_Upload.xls';
        $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_template') . '/';
        $file = $tmp . $file_name;

        if (file_exists($file)) {

            try {

                $inputFileType = \PHPExcel_IOFactory::identify($tmp . $file_name);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($tmp . $file_name);
                $sheet = clone $objPHPExcel->getSheet(0);
                $objPHPExcel->removeSheetByIndex(0);

                // validate choose subject
                if (!is_array($model->subject_id)) {
                    Yii::$app->getSession()->setFlash('error', 'Bạn chưa chọn môn học để tải file mẫu');
                    return;
                }

                // validate class
                $class = Contact::findOne($model->class_id);
                if (is_null($class)) {
                    Yii::$app->getSession()->setFlash('error', 'Lớp chưa tạo trên hệ thống');
                    return;
                }

                // find
                $subjects = Subject::find()->where(['id' => $model->subject_id])->all();
                $students = ContactDetail::find()->where(['contact_id' => $model->class_id])->all();

                foreach ($subjects as $subject) {

                    $sheet_ = clone $sheet;

                    // set school
                    $school = Contact::findOne($class->path);
                    $title_ = $sheet_->getCell('A2')->getValue();
                    $sheet_->setCellValue('A2', str_replace("[school]", $school->contact_name, $title_));

                    // set subject
                    $year = date("Y") . '-' . (intval(date("Y")) + 1);
                    $title_ = $sheet_->getCell('A3')->getValue();
                    $sheet_->setCellValue('A3', $title_ = str_replace("[subject]", $subject->name, $title_));
                    $sheet_->setCellValue('A3', $title_ = str_replace("[class]", $class->contact_name, $title_));
                    $sheet_->setCellValue('A3', $title_ = str_replace("[semester]", $model->semester == 1 ? "1" : "2", $title_));
                    $sheet_->setCellValue('A3', $title_ = str_replace("[year]", $year, $title_));

                    // set sheet name
                    $title_ = $sheet->getTitle();
                    $sheet_->setTitle($title_ = str_replace("subject", $subject->name, $title_));
                    $sheet_->setTitle($title_ = str_replace("class", $class->contact_name, $title_));

                    $row = 1;
                    foreach ($students as $item) {
                        $sheet_->setCellValue('A' . ($row + 10), $row);
                        $sheet_->setCellValue('B' . ($row + 10), $subject->id);
                        $sheet_->setCellValue('C' . ($row + 10), $item->id);
                        $sheet_->setCellValue('D' . ($row + 10), $item->fullname);
                        $row++;
                    }
                    $objPHPExcel->addSheet($sheet_);
                }

                // set file name upload
                $file_name_upload = "Điểm_";
                if (is_array($model->subject_id) && count($model->subject_id) == 1) {
                    $file_name_upload = $file_name_upload . $subject->name . "_";
                }
                $file_name_upload = $file_name_upload . $class->contact_name . "_";
                $file_name_upload = $file_name_upload . ($model->semester == 1 ? "HK1_" : "HK2_");
                $file_name_upload = $file_name_upload . $year;
                $file_name_upload = $file_name_upload . "_Upload.xls";


                header("Content-Length: " . filesize($file));
                header("Content-type: application/octet-stream");
                header("Content-disposition: attachment; filename=" . basename($file_name_upload));
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                ob_clean();
                flush();

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');

            } catch (Exception $ex) {
            }
        } else {
            Yii::$app->getSession()->setFlash('error', 'File is not exits');
        }
    }

    /**
     *
     */
    public function actionViewUpload()
    {
        $model = new Mark();
        $model->setScenario('admin_create_update');
        return $this->render('upload', [
            'model' => $model,
        ]);
    }

    /**
     * view page export mark
     */
    public function actionViewExport()
    {
        $model = new Mark();
        $model->setScenario('admin_create_update');
        return $this->render('export', [
            'model' => $model,
        ]);
    }

    /**
     * export mark summary
     */
    public function actionExport()
    {
        $model = new Mark();
        $file_name = 'Mark_Export.xls';
        $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_template') . '/';
        $file = $tmp . $file_name;

        if (file_exists($file) && $model->load(Yii::$app->request->post())) {

            try {

                $inputFileType = \PHPExcel_IOFactory::identify($tmp . $file_name);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($tmp . $file_name);
                $sheet_ = $objPHPExcel->getSheet(0);

                $subject = Subject::findOne($model->subject_id);
                $class = Contact::findOne($model->class_id);
                $students = ContactDetail::find()->where(['contact_id' => $model->class_id])->all();

                // validate choose subject
                if (count($class) <= 0) {
                    Yii::$app->getSession()->setFlash('error', 'Bạn chưa chọn môn học');
                    return $this->redirect(['index']);
                }

                // validate choose subject
                if (count($subject) <= 0) {
                    Yii::$app->getSession()->setFlash('error', 'Bạn chưa chọn lớp');
                    return $this->redirect(['index']);
                }


                // set school
                $school = Contact::findOne($class->path);
                $title_ = $sheet_->getCell('A2')->getValue();
                $sheet_->setCellValue('A2', str_replace("[school]", $school->contact_name, $title_));

                // set subject
                $year = date("Y") . '-' . (intval(date("Y")) + 1);
                $title_ = $sheet_->getCell('A3')->getValue();
                $sheet_->setCellValue('A3', $title_ = str_replace("[subject]", $subject->name, $title_));
                $sheet_->setCellValue('A3', $title_ = str_replace("[class]", $class->contact_name, $title_));
                $sheet_->setCellValue('A3', $title_ = str_replace("[semester]", $model->semester == 1 ? "1" : "2", $title_));
                $sheet_->setCellValue('A3', $title_ = str_replace("[year]", $year, $title_));

                // set sheet name
                $title_ = $sheet_->getTitle();
                $sheet_->setTitle($title_ = str_replace("subject", $subject->name, $title_));
                $sheet_->setTitle($title_ = str_replace("class", $class->contact_name, $title_));

                $marks_tmp = Mark::find()->where(['subject_id' => $model->subject_id, 'class_id' => $model->class_id, 'semester' => $model->semester])->all();
                $marks = array();
                foreach ($marks_tmp as $item) {
                    $marks[$item->student_id] = $item;
                }

                $row = 1;
                foreach ($students as $item) {
                    $sheet_->setCellValue('A' . ($row + 5), $row);
                    $sheet_->setCellValue('B' . ($row + 5), $item->fullname);

                    if (!isset($marks[$item->id])) {
                        $row++;
                        continue;
                    }

                    $marks_ = $marks[$item->id]->marks;
                    $marks_ = explode(';', $marks_);
                    for ($i = 0; $i < count($marks_); $i++) {
                        $tmp = '';
                        if (strcmp($marks_[$i], 'N') != 0) {
                            $tmp = $marks_[$i];
                        }
                        $sheet_->setCellValue(chr(ord('C') + $i) . ($row + 5), $tmp);
                    }
                    $row++;
                }

                // set file name upload
                $file_name_upload = "Điểm_";
                $file_name_upload = $file_name_upload . $subject->name . "_";
                $file_name_upload = $file_name_upload . $class->contact_name . "_";
                $file_name_upload = $file_name_upload . ($model->semester == 1 ? "HK1_" : "HK2_");
                $file_name_upload = $file_name_upload . $year . '.xls';

                header("Content-Length: " . filesize($file));
                header("Content-type: application/octet-stream");
                header("Content-disposition: attachment; filename=" . basename($file_name_upload));
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                ob_clean();
                flush();

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');

            } catch (Exception $ex) {
            }
        } else {
            Yii::$app->getSession()->setFlash('error', 'File is not exits');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Mark model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mark the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mark::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
