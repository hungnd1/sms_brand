<?php

namespace backend\controllers;

use common\models\Contact;
use common\models\ContactDetail;
use common\models\ContactDetailSearch;
use common\models\DetailExamRoom;
use common\models\ExamRoom;
use common\models\ExamRooms;
use common\models\ExamStudentRoom;
use common\models\IdentificationNumber;
use common\models\Mark;
use common\models\QueueDetailExamRoom;
use common\models\QueueExamRoom;
use common\models\QueueExamRooms;
use common\models\QueueExamStudentRoom;
use common\models\Subject;
use Exception;
use kartik\widgets\ActiveForm;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use Yii;
use common\models\Exam;
use common\models\ExamSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\validators\InlineValidator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\Session;
use yii\web\UploadedFile;

/**
 * ExamController implements the CRUD actions for Exam model.
 */
class ExamController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Exam models.
     * @return mixed
     */
    public function actionViewExamMarkRoom()
    {
        // data exam
        $exams = Exam::find()->orderBy('name')->all();
        $dataExams = ArrayHelper::map($exams, 'id', 'name');
        if (count($exams) < 1) {
            Yii::$app->getSession()->setFlash('error', 'Kì thi chưa được tạo trên hệ thống');
        }

        $model = new ExamStudentRoom();
        $examRoomId = -1;
        if ($model->load(Yii::$app->request->post())) {
            // data room
            $rooms = ExamRoom::find()->where(['exam_id' => $model->exam_id])->orderBy('name')->all();
            $examRoomId = $model->exam_room_id;
            if (strcmp($model->action, 'action4exam_id') == 0) {
                $examRoomId = (count($rooms) < 1) ? -1 : $rooms[0]->id;
            }

            $dataProvider = new ActiveDataProvider([
                'query' => ExamStudentRoom::find()->where(['exam_room_id' => $examRoomId]),
            ]);
        } else {
            // data room
            $rooms = ExamRoom::find()->where(['exam_id' => $exams[0]->id])->orderBy('name')->all();
            $model->exam_id = $exams[0]->id;
            $examRoomId = (count($rooms) < 1) ? -1 : $rooms[0]->id;
            $dataProvider = new ActiveDataProvider([
                'query' => ExamStudentRoom::find()->where(['exam_room_id' => $examRoomId]),
            ]);
        }

        // find subjects
        $subjectExamRoomIds = DetailExamRoom::find()
            ->select('subject_id')
            ->where(['exam_room_id' => $examRoomId])
            ->all();
        $subjectIds = array();
        foreach ($subjectExamRoomIds as $subjectExamRoomId) {
            array_push($subjectIds, $subjectExamRoomId['subject_id']);
        }
        $subjects = Subject::find()
            ->where(['id' => $subjectIds])
            ->orderBy('name')
            ->all();
        $dataRooms = ArrayHelper::map($rooms, 'id', 'name');
        return $this->render('exam_mark', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dataRooms' => $dataRooms,
            'dataExams' => $dataExams,
            'subjects' => $subjects
        ]);
    }

    /**
     * @return string
     */
    public function actionViewCreate()
    {
        $model = new Exam();

        $session = Yii::$app->getSession();
        if (isset($session['identificationNumber_' . Yii::$app->user->id])) {
            $idendificationNumber = $session['identificationNumber_' . Yii::$app->user->id];
        } else {
            $idendificationNumber = new IdentificationNumber();
        }

        // classes
        $classes = new ActiveDataProvider([
            'query' => Contact::getAllClasses()->orderBy('contact_name')
        ]);
        $classes->pagination = false;

        // subjects
        $subjects = new ActiveDataProvider([
            'query' => Subject::find()
        ]);
        $subjects->pagination = false;

        // delete temp table exams
        $this->deleteTempTableExams();

        // exam room
        $queueDetailExamRoom = new ActiveDataProvider([
            'query' => QueueDetailExamRoom::find(-1)
        ]);

        return $this->render('create', [
            'model' => $model,
            'identificationNumber' => $idendificationNumber,
            'subjects' => $subjects,
            'classes' => $classes,
            'queueDetailExamRoom' => $queueDetailExamRoom
        ]);
    }

    /**
     * Displays a single Exam model.
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
     * Creates a new Exam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $exam = new Exam();
        if ($exam->load(Yii::$app->request->post())) {

            // create exam
            $exam->created_by = Yii::$app->user->id;
            $exam->created_at = time();
            $exam->start_date = strtotime($exam->start_date);
            $exam->status = Exam::EXAM_YET_STARTED;
            $exam->save();

            // create exam room
            $queueExamRooms = QueueExamRoom::find([
                'ip' => Yii::$app->request->getUserIP(),
                'created_by' => Yii::$app->user->id
            ])->all();
            $queueExamRoomMap2examRoom = array();
            foreach ($queueExamRooms as $queueExamRoom) {
                $examRoom = new ExamRoom();
                $examRoom->created_at = time();
                $examRoom->created_by = Yii::$app->user->id;
                $examRoom->name = $queueExamRoom->name;
                $examRoom->exam_id = $exam->id;
                $examRoom->save();
                $queueExamRoomMap2examRoom[$queueExamRoom->id] = $examRoom->id;
            }

            // create exam room detail
            $queueDetailExamRooms = QueueDetailExamRoom::find([
                'ip' => Yii::$app->request->getUserIP(),
                'created_by' => Yii::$app->user->id
            ])->all();
            foreach ($queueDetailExamRooms as $queueDetailExamRoom) {
                $detailExamRoom = new DetailExamRoom();
                $detailExamRoom->created_at = time();
                $detailExamRoom->created_by = Yii::$app->user->id;
                $detailExamRoom->subject_id = $queueDetailExamRoom->subject_id;
                $detailExamRoom->location = $queueDetailExamRoom->location;
                $detailExamRoom->supervisory = $queueDetailExamRoom->supervisory;
                $detailExamRoom->exam_hour = $queueDetailExamRoom->exam_hour;
                $detailExamRoom->exam_date = $queueDetailExamRoom->exam_date;
                $detailExamRoom->exam_room_id = $queueExamRoomMap2examRoom[$queueDetailExamRoom->exam_room_id];
                $detailExamRoom->save();
            }

            // create exam student room
            $queueExamStudentRooms = QueueExamStudentRoom::find([
                'ip' => Yii::$app->request->getUserIP(),
                'created_by' => Yii::$app->user->id
            ])->all();
            foreach ($queueExamStudentRooms as $queueExamStudentRoom) {
                $examStudentRoom = new ExamStudentRoom();
                $examStudentRoom->created_at = time();
                $examStudentRoom->created_by = Yii::$app->user->id;
                $examStudentRoom->student_id = $queueExamStudentRoom->student_id;
                $examStudentRoom->student_name = $queueExamStudentRoom->student_name;
                $examStudentRoom->identification = $queueExamStudentRoom->identification;
                $examStudentRoom->exam_room_id = $queueExamRoomMap2examRoom[$queueExamStudentRoom->exam_room_id];
                $examStudentRoom->save();
            }

            Yii::$app->getSession()->setFlash('success', 'Tạo mới kỳ thi thành công');
        }
        return $this->actionViewCreate();
    }

    /**
     * Updates an existing Exam model.
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
     * Deletes an existing Exam model.
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
     * Finds the Exam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Exam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Exam::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * configuration identification number
     */
    public function actionConfigSbd()
    {
        $value = Yii::$app->request->post('IdentificationNumber');

        $identificationNumber = new IdentificationNumber();
        $identificationNumber->isOderByName = 1;
        $identificationNumber->isPrefix = $value['isPrefix'];
        $identificationNumber->prefix = $value['prefix'];
        $identificationNumber->isLenght = $value['isLenght'];
        $identificationNumber->lenght = $value['lenght'];

        $session = Yii::$app->getSession();
        $session['identificationNumber_' . Yii::$app->user->id] = $identificationNumber;
    }

    /**
     * @return array|string
     */
    public function actionCreateQueueRooms()
    {
        // delete temp table exams
        $this->deleteTempTableExams();

        // get identification number
        $session = Yii::$app->getSession();
        if (isset($session['identificationNumber_' . Yii::$app->user->id])) {
            $identificationNumber = $session['identificationNumber_' . Yii::$app->user->id];
        } else {
            $identificationNumber = new IdentificationNumber();
        }

        $post = Yii::$app->request->post();
        if (!isset($post['classIds']) || !isset($post['subjectIds'])) {
            return [
                'success' => false,
                'message' => 'Bad request'
            ];
        }

        $subjectIds = $post['subjectIds'];
        $classIds = $post['classIds'];
        $indexRoom = 1;
        foreach ($classIds as $classId) {

            $class = Contact::findOne($classId);
            $students = ContactDetail::find()
                ->where(['contact_id' => $classId])
                ->orderBy('fullname')
                ->all();

            // create queue room
            $queueExamRoom = new QueueExamRoom();
            $queueExamRoom->name = $class->contact_name;
            $queueExamRoom->number_student = ContactDetailSearch::countContactDetailByContactName(
                $class->contact_name
            );
            $queueExamRoom->ip = Yii::$app->request->getUserIP();
            $queueExamRoom->created_at = time();
            $queueExamRoom->created_by = Yii::$app->user->id;
            $queueExamRoom->save(false);

            // create queue student room
            $identification = '';
            $indexIdentification = 1;
            if (!is_null($identificationNumber->isPrefix)
                && strcmp($identificationNumber->isPrefix, "1") == 0
            ) {
                $identification = $identification . $identificationNumber->prefix;
            }

            $length = 0;
            if (!is_null($identificationNumber->isLenght)
                && strcmp($identificationNumber->isLenght, "1") == 0
            ) {
                $length = intval($identificationNumber->lenght);
            }

            foreach ($students as $student) {
                $queueExamStudentRoom = new QueueExamStudentRoom();
                $queueExamStudentRoom->student_id = $student->id;
                $queueExamStudentRoom->student_name = $student->fullname;

                // set identification number
                $identificationNumberLenght = strlen('' . $indexIdentification);
                $tmp = $identification;
                if ($length > $identificationNumberLenght) {
                    for ($i = 0; $i < ($length - $identificationNumberLenght); $i++) {
                        $tmp = $tmp . '0';
                    }
                }

                $queueExamStudentRoom->identification = $tmp . $indexIdentification;
                $queueExamStudentRoom->exam_room_id = $queueExamRoom->id;
                $queueExamStudentRoom->created_at = time();
                $queueExamStudentRoom->created_by = Yii::$app->user->id;
                $queueExamStudentRoom->ip = Yii::$app->request->getUserIP();
                $queueExamStudentRoom->save(false);
                $indexIdentification++;
            }

            // create queue detail exam room
            foreach ($subjectIds as $subjectId) {
                $queueDetailExamRoom = new QueueDetailExamRoom();
                $queueDetailExamRoom->subject_id = $subjectId;
                $queueDetailExamRoom->exam_room_id = $queueExamRoom->id;
                $queueDetailExamRoom->created_by = Yii::$app->user->id;
                $queueDetailExamRoom->created_at = time();
                $queueDetailExamRoom->ip = Yii::$app->request->getUserIP();
                $queueDetailExamRoom->save(false);
            }
            $indexRoom++;
        }

        // queue exam rooms
        $queueDetailExamRoom = new ActiveDataProvider([
            'query' => QueueDetailExamRoom::find()
                ->select('queue_detail_exam_room.*, subject.name as subject_name, 
                    queue_exam_room.name as room_name, queue_exam_room.number_student as room_student')
                ->leftJoin('subject', 'queue_detail_exam_room.subject_id = subject.id')
                ->leftJoin('queue_exam_room', 'queue_detail_exam_room.exam_room_id = queue_exam_room.id')
                ->where([
                    'queue_detail_exam_room.ip' => Yii::$app->request->getUserIP(),
                    'queue_detail_exam_room.created_by' => Yii::$app->user->id
                ])
                ->orderBy('room_name', 'subject_name')
        ]);

        // queue exam room
        $queueExamRoom = new ActiveDataProvider([
            'query' => QueueExamRoom::find()->orderBy('name')
        ]);

        return $this->renderAjax('exam_room', [
            'queueDetailExamRoom' => $queueDetailExamRoom,
            'queueExamRoom' => $queueExamRoom
        ]);
    }

    /**
     * @return string
     */
    public function actionShowExamStudentRoom()
    {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $dataProvider = new ActiveDataProvider([
            'query' => QueueExamStudentRoom::find()
                ->where(['exam_room_id' => $id])
        ]);
        $dataProvider->pagination = false;
        return $this->renderAjax('students-list', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     *
     */
    private function deleteTempTableExams()
    {
        // Delete temp exam room
        QueueExamRoom::deleteAll([
            'ip' => Yii::$app->request->getUserIP(),
            'created_by' => Yii::$app->user->id
        ]);

        // Delete temp exam detail room
        QueueDetailExamRoom::deleteAll([
            'ip' => Yii::$app->request->getUserIP(),
            'created_by' => Yii::$app->user->id
        ]);

        // Delete temp exam student room
        QueueExamStudentRoom::deleteAll([
            'ip' => Yii::$app->request->getUserIP(),
            'created_by' => Yii::$app->user->id
        ]);
    }

    /**
     * view page upload mark summary
     */
    public function actionViewUpload()
    {
        $model = new ExamStudentRoom();
        $model->setScenario('admin_create_update');

        $exam_id = Yii::$app->getRequest()->getQueryParam('exam_id');
        $rooms = ExamRoom::find()->where(['exam_id' => $exam_id])->orderBy('name')->all();
        $dataRooms = ArrayHelper::map($rooms, 'id', 'name');

        return $this->render('upload', [
            'model' => $model,
            'dataRooms' => $dataRooms
        ]);
    }

    /**
     * @return string
     */
    public function actionUpload()
    {

        $model = new ExamStudentRoom();
        $check = 0;

        $post = Yii::$app->request->post();

        // download template
        if ($model->load($post) && strcmp($model->action, "download") == 0) {
            $this->downloadTemplate($model);
            return $this->redirect(['view-exam-mark-room']);
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
                    // validate choose exam room
                    $examRoom = ExamRoom::findOne(['id' => $model->exam_room_id]);
                    if (is_null($examRoom)) {
                        Yii::$app->getSession()->setFlash('error', 'Bạn chưa chọn phòng để tải file mẫu');
                        return $this->redirect(['view-exam-mark-room']);
                    }

                    $inputFileType = \PHPExcel_IOFactory::identify($tmp . $file_name);
                    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($tmp . $file_name);
                    $sheet = $objPHPExcel->getSheet(0);

                    $highestRow = $sheet->getHighestRow();
                    $subjectExamRoomIds = DetailExamRoom::find()
                        ->select('subject_id')
                        ->where(['exam_room_id' => $examRoom->id])
                        ->all();
                    $count = count($subjectExamRoomIds);

                    for ($row = 10; $row <= $highestRow; $row++) {

                        $rowData = $sheet->rangeToArray('A' . $row . ':' . chr($count + ord('B')) . $row, null, true, false);
                        $examStudentRoom = ExamStudentRoom::findOne(['identification' => $rowData[0][0], 'exam_room_id' => $examRoom->id]);
                        $examStudentRoom->updated_at = time();
                        $examStudentRoom->updated_by = Yii::$app->user->id;

                        // set marks
                        $mark_str = '';
                        for ($i = 2; $i < 2 + $count; $i++) {
                            if (!is_null($rowData[0][$i])) {
                                $mark_str = $mark_str . $sheet->getCell(chr(ord('A') + $i) . '8')->getValue() . ':' . $rowData[0][$i] . ';';
                            }
                        }
                        $examStudentRoom->marks = $mark_str;
                        if ($examStudentRoom->save(false)) {
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
                return $this->redirect(['view-exam-mark-room']);
            }
        }
        return $this->redirect(['view-exam-mark-room']);
    }

    /**
     *
     */
    public function downloadTemplate($model)
    {
        $file_name = 'Exam_Mark_Upload.xls';
        $tmp = Yii::getAlias('@backend') . '/web/' . Yii::getAlias('@file_template') . '/';
        $file = $tmp . $file_name;

        if (file_exists($file)) {

            try {

                $inputFileType = \PHPExcel_IOFactory::identify($tmp . $file_name);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($tmp . $file_name);
                $sheet = $objPHPExcel->getSheet(0);

                // validate choose exam room
                $examRoom = ExamRoom::findOne(['id' => $model->exam_room_id]);
                $exam = Exam::findOne(['id' => $examRoom->exam_id]);

                if (is_null($examRoom)) {
                    Yii::$app->getSession()->setFlash('error', 'Bạn chưa chọn phòng để tải file mẫu');
                    return;
                }

                // set exam
                $title_ = $sheet->getCell('A1')->getValue();
                $sheet->setCellValue('A1', $title_ = str_replace("[exam]", $exam->name, $title_));

                // set room
                $title_ = $sheet->getCell('A7')->getValue();
                $sheet->setCellValue('A7', $title_ = str_replace("[room]", $examRoom->name, $title_));

                $column = ord('C');
                $styleArray = array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0070C0')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'font' => array(
                        'color' => array('rgb' => 'FFFFFF'),
                        'size' => 9
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                    ),
                );

                // find subjects
                $subjectExamRoomIds = DetailExamRoom::find()
                    ->select('subject_id')
                    ->where(['exam_room_id' => $examRoom->id])
                    ->all();
                $subjectIds = array();
                foreach ($subjectExamRoomIds as $subjectExamRoomId) {
                    array_push($subjectIds, $subjectExamRoomId['subject_id']);
                }
                $subjects = Subject::find()
                    ->where(['id' => $subjectIds])
                    ->orderBy('name')
                    ->all();

                foreach ($subjects as $item) {
                    $sheet->setCellValue(chr($column) . '9', $item->name);
                    $sheet->setCellValue(chr($column) . '8', $item->id);;
                    $sheet->getStyle(chr($column) . '9')->applyFromArray($styleArray);
                    $column++;
                }

                // XL
                //$sheet->setCellValue(chr($column) . '9', 'XL');
                //$sheet->getStyle(chr($column) . '9')->applyFromArray($styleArray);

                $row = 0;
                $examStudentRooms = ExamStudentRoom::find()
                    ->where(['exam_room_id' => $examRoom->id])
                    ->all();
                foreach ($examStudentRooms as $examStudentRoom) {
                    $sheet->setCellValue('A' . ($row + 10), $examStudentRoom->identification);
                    $sheet->setCellValue('B' . ($row + 10), $examStudentRoom->student_name);
                    $row++;
                }

                // set file name upload
                $file_name_upload = "Điểm_thi_";
                $file_name_upload = $file_name_upload . $exam->name;
                $file_name_upload = $file_name_upload . "_" . $examRoom->name;
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
     * view page export mark summary
     */
    public function actionViewExport()
    {
        $model = new ExamStudentRoom();
        $model->setScenario('admin_create_update');

        $exam_id = Yii::$app->getRequest()->getQueryParam('exam_id');
        $rooms = ExamRoom::find()->where(['exam_id' => $exam_id])->orderBy('name')->all();
        $dataRooms = ArrayHelper::map($rooms, 'id', 'name');

        return $this->render('export', [
            'model' => $model,
            'dataRooms' => $dataRooms
        ]);
    }

    /**
     * export mark summary
     */
    public function actionExport()
    {
        $model = new ExamStudentRoom();
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
}
