<?php
/**
 * Created by PhpStorm.
 * User: mlwayz
 * Date: 12/27/16
 * Time: 12:14 AM
 */

namespace backend\controllers;


use common\models\Contact;
use common\models\Mark;
use common\models\SchoolYear;
use common\models\SMSBrandUtil;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
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
        $classes = Contact::getAllClasses()->all();
        $grades = SMSBrandUtil::getGrades($classes);

        if ($model->load(Yii::$app->request->post())) {
            $classes = Contact::getAllClasses($model->grade)->all();
            $dataProvider = new ActiveDataProvider([
                'query' => Contact::getAllClasses($model->grade, $model->class)
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => Contact::getAllClasses()
            ]);
        }

        $dataContact = ArrayHelper::map($classes, 'contact_name', 'contact_name');

        return $this->render('index', [
            'model' => $model,
            'dataContact' => $dataContact,
            'grades' => $grades,
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
            $classes = Contact::getAllClasses($model->grade, $model->class)->all();
            foreach ($classes as $class){
                echo $class->contact_name;
            }
        }
        Yii::$app->getSession()->setFlash('success', 'Kết thúc năm học thành công');
        return $this->actionIndex();
    }
}