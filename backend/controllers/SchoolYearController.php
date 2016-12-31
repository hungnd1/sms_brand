<?php
/**
 * Created by PhpStorm.
 * User: mlwayz
 * Date: 12/27/16
 * Time: 12:14 AM
 */

namespace backend\controllers;


use common\models\Contact;
use common\models\ContactDetail;
use common\models\ContactDetailSearch;
use common\models\HistoryUpClass;
use common\models\SchoolYear;
use common\models\SMSBrandUtil;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class SchoolYearController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new SchoolYear();
        $classes = Contact::getAllClasses()->orderBy('contact_name')->all();
        $grades = SMSBrandUtil::getGrades($classes);
        $schoolYearStatus = SchoolYear::getSchoolYearStatus();

        if ($model->load(Yii::$app->request->post())) {
            $classes = Contact::getAllClasses($model->grade)->orderBy('contact_name')->all();
            $dataProvider = new ActiveDataProvider([
                'query' => Contact::getAllClasses($model->grade, $model->class)->orderBy('contact_name')
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => Contact::getAllClasses()->orderBy('contact_name')
            ]);
        }

        $dataContact = ArrayHelper::map($classes, 'contact_name', 'contact_name');

        return $this->render('index', [
            'model' => $model,
            'dataContact' => $dataContact,
            'grades' => $grades,
            'schoolYearStatus' => $schoolYearStatus,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionEndSchoolYear()
    {
        $model = new SchoolYear();

        if ($model->load(Yii::$app->request->post())) {

            $classes = Contact::getAllClasses($model->grade, $model->class)
                ->orderBy(['contact_name' => SORT_DESC])
                ->all();

            foreach ($classes as $class) {

                // count number students
                $numStudents = ContactDetailSearch::countContactDetailByContactName($class->contact_name);

                if ($class->school_year_status == Contact::END_SCHOOL_YEAR || $numStudents == 0) {
                    continue;
                }

                //find id class by class name
                $newClass = Contact::findOne(['contact_name' => Contact::getNewClassFromOldClass($class->contact_name)]);
                $isNewClass = false;
                if (is_null($newClass)) {
                    $newClass = new Contact();
                    $newClass->contact_name = Contact::getNewClassFromOldClass($class->contact_name);
                    $newClass->status = Contact::STATUS_ACTIVE;
                    $newClass->created_at = time();
                    $newClass->updated_at = time();
                    $newClass->path = $class->path;
                    $newClass->created_by = Yii::$app->user->id;
                    $newClass->save(false);
                    $isNewClass = true;
                }

                // update again contact detail
                $historyUpClass = new HistoryUpClass();
                $historyUpClass->old_class_id = $class->id;
                $historyUpClass->old_class_name = $class->contact_name;
                $historyUpClass->number_old_class_students = $numStudents;
                $historyUpClass->new_class_id = $newClass->id;
                $historyUpClass->new_class_name = $newClass->contact_name;
                $historyUpClass->number_new_class_students = $numStudents;
                $historyUpClass->status = 1;
                $historyUpClass->year = date("Y");
                $historyUpClass->created_at = time();
                $historyUpClass->created_by = Yii::$app->user->id;
                $historyUpClass->save(false);

                ContactDetail::updateAll(['contact_id' => $newClass->id], 'contact_id = ' . $class->id);

                // update end school year
                $class->school_year_status = Contact::END_SCHOOL_YEAR;
                $class->save(false);
                if ($isNewClass){
                    $newClass->school_year_status = Contact::END_SCHOOL_YEAR;
                    $newClass->save(false);
                }
            }
        }
        Yii::$app->getSession()->setFlash('success', 'Kết thúc năm học thành công');
        return $this->actionIndex();
    }

    /**
     * @return string
     */
    public function actionStartSchoolYear()
    {
        $model = new SchoolYear();

        if ($model->load(Yii::$app->request->post())) {

            $classes = Contact::getAllClasses($model->grade, $model->class)->all();

            foreach ($classes as $class) {
                $class->school_year_status = Contact::START_SCHOOL_YEAR;
                $class->save(false);
            }
        }
        Yii::$app->getSession()->setFlash('success', 'Bắt đầu năm học thành công');
        return $this->actionIndex();
    }
}