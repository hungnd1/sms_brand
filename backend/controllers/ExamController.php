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
        $exams = Exam::find()->all();
        $dataExams = ArrayHelper::map($exams, 'id', 'name');
        if (count($exams) < 1) {
            Yii::$app->getSession()->setFlash('error', 'Kì thi chưa được tạo trên hệ thống');
        }

        $model = new ExamStudentRoom();
        if ($model->load(Yii::$app->request->post())) {
            // data room
            $rooms = ExamRoom::find()->where(['exam_id' => $model->exam_id])->all();
            $dataProvider = new ActiveDataProvider([
                'query' => ExamStudentRoom::find()->where(['exam_room_id' => $model->exam_room_id]),
            ]);
        } else {
            // data room
            $rooms = ExamRoom::find()->where(['exam_id' => $exams[0]->id])->all();
            $dataProvider = new ActiveDataProvider([
                'query' => ExamStudentRoom::find()->where(['exam_room_id' => (count($rooms) < 1) ? -1 : $rooms[0]->id]),
            ]);
        }
        $dataRooms = ArrayHelper::map($rooms, 'id', 'name');
        return $this->render('exam_mark', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dataRooms' => $dataRooms,
            'dataExams' => $dataExams,
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
}
