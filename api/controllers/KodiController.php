<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 04-Aug-16
 * Time: 10:03 AM
 */

namespace api\controllers;


use api\models\ItemKodi;
use api\models\ItemKodiHome;
use common\models\KodiCategory;
use common\models\KodiCategoryItemAsm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\Response;

class KodiController extends ApiController
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
//            'index',
        ];
        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'index' => ['GET'],
        ];
    }

    public function actionTest()
    {
        $res = [];
        $res['film'] = "a";
        $res['music'] = 'b';
        return $res;
    }


}