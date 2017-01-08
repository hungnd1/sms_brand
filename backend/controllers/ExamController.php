<?php

namespace backend\controllers;

use common\models\Contact;
use common\models\ContactDetail;
use common\models\ContactDetailSearch;
use common\models\ExamRooms;
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
    public function actionIndex()
    {
        $dataProvider = new ArrayDataProvider();
        $model = new ExamRooms();
        $exams = Exam::find()->all();
        $rooms = ExamRooms::find()->all();
        $dataRooms = ArrayHelper::map($rooms, 'id', 'name');
        $dataExams = ArrayHelper::map($exams, 'id', 'name');

        if ($model->load(Yii::$app->request->post())) {
            $dataProvider = new ActiveDataProvider([
                'query' => ExamRooms::find()->where(['id' => $model->id]),
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
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

        // exam room
        QueueDetailExamRoom::deleteAll([
            'ip' => Yii::$app->request->getUserIP(),
            'created_by' => Yii::$app->user->id
        ]);
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
        $model = new Exam();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
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
        $identificationNumber->isOderByName = $value['isOderByName'];
        $identificationNumber->isPrefix = $value['isPrefix'];
        $identificationNumber->prefix = $value['prefix'];
        $identificationNumber->isLenght = $value['isLenght'];
        $identificationNumber->lenght = $value['lenght'];

        $session = Yii::$app->getSession();
        $session['identificationNumber_' . Yii::$app->user->id] = $identificationNumber;
    }

    public function actionCreateQueueRooms()
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

        // get identification number
        if (isset($session['identificationNumber_' . Yii::$app->user->id])) {
            $idendificationNumber = $session['identificationNumber_' . Yii::$app->user->id];
        } else {
            $idendificationNumber = new IdentificationNumber();
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
        $index = 1;
        foreach ($classIds as $classId) {

            $class = Contact::findOne($classId);
            $students = ContactDetail::find()->where(['contact_id' => $classId])->all();

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
            foreach ($students as $student) {
                $queueExamStudentRoom = new QueueExamStudentRoom();
                $queueExamStudentRoom->student_id = $student->id;
                $queueExamStudentRoom->student_name = $student->fullname;
                $queueExamStudentRoom->identification = '1111';
                $queueExamStudentRoom->exam_room_id =  $queueExamRoom->id;
                $queueExamStudentRoom->created_at = time();
                $queueExamStudentRoom->created_by= Yii::$app->user->id;
                $queueExamStudentRoom->ip = Yii::$app->request->getUserIP();
                $queueExamStudentRoom->save(false);
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
            $index++;
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
                ->orderBy('room_name')
        ]);

        return $this->renderAjax('queue_detail_exam_room', [
            'queueDetailExamRoom' => $queueDetailExamRoom
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
}
