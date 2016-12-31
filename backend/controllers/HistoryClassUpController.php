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
use common\models\HistoryUpClass;
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
        $classes = Contact::getAllClasses()->orderBy('contact_name')->all();
        $grades = SMSBrandUtil::getGrades($classes);
        $schoolYearStatus = SchoolYear::getSchoolYearStatus();

        if ($model->load(Yii::$app->request->post())) {
            $dataProvider = new ActiveDataProvider([
                'query' => HistoryUpClass::findByGrade($model->grade)->orderBy('old_class_name')
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => HistoryUpClass::findByGrade()->orderBy('old_class_name')
            ]);
        }

        return $this->render('index', [
            'model' => $model,
            'grades' => $grades,
            'schoolYearStatus' => $schoolYearStatus,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionShow()
    {
        $model = new ContactDetail();
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $class = Contact::findOne($id);
        $dataProvider = new ActiveDataProvider([
            'query' => ContactDetail::find()->where(['contact_id' => $id])
        ]);
        $dataProvider->pagination = false;
        return $this->renderAjax('students-list', [
            'dataProvider' => $dataProvider,
            'className' => $class->contact_name
        ]);
    }
}