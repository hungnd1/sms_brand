<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\models\Contact;
use common\models\ContactDetail;
use common\models\MarkSummary;
use common\models\Subject;
use common\models\UserActivity;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * MarkSummaryController implements the CRUD actions for MarkSummary model.
 */
class MarkSummaryController extends BaseBEController
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
                'model_type_default' => UserActivity::ACTION_TARGET_MARKSUMMARY,
            ],
        ]);
    }

    /**
     * Lists all MarkSummary models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ArrayDataProvider();
        $model = new MarkSummary();
        $dataSubject = array();
        $classes = Contact::getAllClasses();
        $dataContact = ArrayHelper::map($classes, 'id', 'contact_name');

        if (count($classes) < 1) {
            Yii::$app->getSession()->setFlash('error', 'Lớp chưa được tạo trên hệ thống');
        }

        if (count($classes) > 0) {
            $dataProvider = new ActiveDataProvider([
                'query' => MarkSummary::find()->where(['class_id' => $classes[0]->id, 'semester' => 1]),
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $dataSubject = Subject::find()->where(['id' => $model->subject_id])->all();
            $dataProvider = new ActiveDataProvider([
                'query' => MarkSummary::find()->where(['class_id' => $model->class_id, 'semester' => $model->semester]),
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataSubject' => $dataSubject,
            'model' => $model,
            'dataContact' => $dataContact,
        ]);
    }

    /**
     * view page upload mark summary
     */
    public function actionViewUpload()
    {
        $model = new MarkSummary();
        $model->setScenario('admin_create_update');
        return $this->render('upload', [
            'model' => $model,
        ]);
    }

    /**
     * view page export mark summary
     */
    public function actionViewExport()
    {
        $model = new MarkSummary();
        $model->setScenario('admin_create_update');
        return $this->render('export', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionUpload()
    {

        $model = new MarkSummary();
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
                    // validate class
                    $class = Contact::findOne($model->class_id);
                    if (is_null($class)) {
                        Yii::$app->getSession()->setFlash('error', 'Lớp chưa tạo trên hệ thống');
                        return $this->redirect(['index']);
                    }

                    $inputFileType = \PHPExcel_IOFactory::identify($tmp . $file_name);
                    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($tmp . $file_name);
                    $sheet = $objPHPExcel->getSheet(0);

                    $highestRow = $sheet->getHighestRow();
                    $subjects = Subject::find()->all();
                    $count = count($subjects);

                    for ($row = 12; $row <= $highestRow; $row++) {

                        $rowData = $sheet->rangeToArray('A' . $row . ':' . chr($count + ord('C')) . $row, null, true, false);

                        $mark_summary = MarkSummary::findOne(['student_id' => $rowData[0][1], 'class_id' => $model->class_id, 'semester' => $model->semester]);

                        if (is_null($mark_summary)) {
                            $mark_summary = new MarkSummary();
                            $mark_summary->created_at = time();
                            $mark_summary->created_by = Yii::$app->user->id;
                            $mark_summary->student_id = $rowData[0][1];
                            $mark_summary->class_id = $model->class_id;
                            $mark_summary->semester = $model->semester;
                        } else {
                            $mark_summary->updated_at = time();
                            $mark_summary->updated_by = Yii::$app->user->id;
                        }

                        // set marks
                        $mark_str = '';
                        for ($i = 3; $i < 3 + count($subjects); $i++) {

                            if (!is_null($rowData[0][$i])) {
                                $mark_str = $mark_str . $sheet->getCell(chr(ord('A') + $i) . '10')->getValue() . ':' . $rowData[0][$i] . ';';
                            }
                        }

                        $mark_summary->marks = $mark_str;

                        if ($mark_summary->save(false)) {
                            $check = 1;
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
     *
     */
    public function downloadTemplate($model)
    {

        $file_name = 'Mark_Summary_Upload.xls';
        $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_template') . '/';
        $file = $tmp . $file_name;

        if (file_exists($file)) {

            try {

                $inputFileType = \PHPExcel_IOFactory::identify($tmp . $file_name);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($tmp . $file_name);
                $sheet = $objPHPExcel->getSheet(0);
                $sheet->getColumnDimension('B')->setWidth(0.1);
                $sheet->getRowDimension('10')->setRowHeight(0.1);

                $subjects = Subject::find()->all();
                $class = Contact::findOne($model->class_id);
                $students = ContactDetail::find()->where(['contact_id' => $model->class_id])->all();

                // validate class
                if (is_null($class)) {
                    Yii::$app->getSession()->setFlash('error', 'Lớp chưa tạo trên hệ thống');
                    return;
                }

                // set semester
                $title_ = $sheet->getCell('A1')->getValue();
                $sheet->setCellValue('A1', $title_ = str_replace("[semester]", $model->semester == 1 ? "1" : "2", $title_));

                // set subject
                $year = date("Y") . '-' . (intval(date("Y")) + 1);
                $title_ = $sheet->getCell('A2')->getValue();
                $sheet->setCellValue('A2', $title_ = str_replace("[class]", $class->contact_name, $title_));
                $sheet->setCellValue('A2', $title_ = str_replace("[year]", $year, $title_));

                // set school
                $school = Contact::findOne($class->path);
                $title_ = $sheet->getCell('A3')->getValue();
                $sheet->setCellValue('A3', $title_ = str_replace("[school]", $school->contact_name, $title_));

                // set sheet name
                $title_ = $sheet->getTitle();
                $sheet->setTitle($title_ = str_replace("semester", $model->semester == 1 ? "1" : "2", $title_));
                $sheet->setTitle($title_ = str_replace("class", $class->contact_name, $title_));

                $column = ord('D');
                $styleArray = array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0070C0')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );

                foreach ($subjects as $item) {
                    $sheet->setCellValue(chr($column) . '11', $item->name);
                    $sheet->setCellValue(chr($column) . '10', $item->id);;
                    $sheet->getStyle(chr($column) . '11')->applyFromArray($styleArray);
                    $column++;
                }

                $row = 0;
                foreach ($students as $item) {
                    $sheet->setCellValue('A' . ($row + 12), $row + 1);
                    $sheet->setCellValue('B' . ($row + 12), $item->id);
                    $sheet->setCellValue('C' . ($row + 12), $item->fullname);
                    $row++;
                }

                // set file name upload
                $file_name_upload = "Điểm_Tổngkết_";
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
     * export mark summary
     */
    public function actionExport()
    {
        $model = new MarkSummary();
        $file_name = 'Mark_Summary_List.xls';
        $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_template') . '/';
        $file = $tmp . $file_name;

        if (file_exists($file) && $model->load(Yii::$app->request->post())) {

            try {

                $inputFileType = \PHPExcel_IOFactory::identify($tmp . $file_name);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($tmp . $file_name);
                $sheet = $objPHPExcel->getSheet(0);

                $subjects = Subject::find()->where(['id' => $model->subject_id])->all();
                $class = Contact::findOne($model->class_id);
                $students = ContactDetail::find()->where(['contact_id' => $model->class_id])->all();

                // validate class
                if (is_null($class)) {
                    Yii::$app->getSession()->setFlash('error', 'Lớp chưa tạo trên hệ thống');
                    return;
                }

                // set semester
                $title_ = $sheet->getCell('A1')->getValue();
                $sheet->setCellValue('A1', $title_ = str_replace("[semester]", $model->semester == 1 ? "1" : "2", $title_));
                $sheet->setCellValue('A1', $title_ = str_replace("[class]", $class->contact_name, $title_));
                $sheet->mergeCells('A1:' . chr(ord('C') + count($subjects)) . '1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                // set sheet name
                $sheet->setTitle("TKHK" . $model->semester . '-' . $class->contact_name);

                $column = ord('D');
                $styleHeader = array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0070C0')
                    ),
                    'font' => array(
                        'color' => array('rgb' => 'FFFFFF'),
                        'size' => 13
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                );

                $styleRow = array(
                    'font' => array(
                        'size' => 13
                    ),
                );

                // set mark summary
                $sheet->setCellValue('C2', 'Điểm tổng kết');
                $sheet->setCellValue('C3', 'HK' . $model->semester);
                $sheet->getStyle('C2')->applyFromArray($styleHeader);
                $sheet->getStyle('C3')->applyFromArray($styleHeader);

                // set mark summary subject
                $subjects_column = array();
                foreach ($subjects as $item) {
                    $sheet->setCellValue(chr($column) . '2', $item->name);
                    $sheet->setCellValue(chr($column) . '3', 'HK' . $model->semester);
                    $sheet->getStyle(chr($column) . '2')->applyFromArray($styleHeader);
                    $sheet->getStyle(chr($column) . '3')->applyFromArray($styleHeader);
                    $subjects_column[$item->id] = chr($column);
                    $column++;
                }

                $row = 0;
                $students_id = array();
                foreach ($students as $item) {
                    array_push($students_id, $item->id);
                }

                $marks_summary_tmp = MarkSummary::find()->where(['student_id' => $students_id, 'class_id' => $model->class_id, 'semester' => $model->semester])->all();
                $marks_summary = array();

                foreach ($marks_summary_tmp as $item) {
                    $marks_summary[$item->student_id] = $item;
                }

                foreach ($students as $item) {

                    $sheet->setCellValue('A' . ($row + 4), $row + 1);
                    $sheet->setCellValue('B' . ($row + 4), $item->fullname);
                    $sheet->getStyle('A' . ($row + 4))->applyFromArray($styleRow);
                    $sheet->getStyle('B' . ($row + 4))->applyFromArray($styleRow);

                    if (!isset($marks_summary[$item->id])) {
                        $row++;
                        continue;
                    }

                    $marks = $marks_summary[$item->id]->marks;
                    $marks = explode(';', $marks);
                    foreach ($marks as $mark) {
                        $tmp = explode(':', $mark);
                        if (isset($subjects_column[$tmp[0]])) {
                            $sheet->setCellValue($subjects_column[$tmp[0]] . ($row + 4), $tmp[1]);
                            $sheet->getStyle($subjects_column[$tmp[0]] . ($row + 4))->applyFromArray($styleRow);
                        }
                    }
                    $row++;
                }

                // set file name upload
                $file_name_upload = "Danh_sach_diem_tong_ket_hoc_ky_";
                $file_name_upload = $file_name_upload . ($model->semester) . ".xls";

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
     * Finds the MarkSummary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MarkSummary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MarkSummary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
