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

class HistoryClassUpController extends Controller
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
            $dataProvider = new ActiveDataProvider([
                'query' => Contact::getAllClasses($model->grade, $model->class)
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => Contact::getAllClasses()
            ]);
        }

        return $this->render('index', [
            'model' => $model,
            'grades' => $grades,
            'dataProvider' => $dataProvider,
        ]);
    }
}