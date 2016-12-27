<?php
/**
 * Created by PhpStorm.
 * User: VS9 X64Bit
 * Date: 23/05/2015
 * Time: 4:37 PM
 */

namespace api\controllers;


use common\helpers\MTParam;
use common\helpers\SMSGW;
use common\models\Content;
use common\models\Service;
use common\models\ServiceProvider;
use common\models\SiteStreamingServerAsm;
use common\models\SmsMtTemplate;
use common\models\Subscriber;
use Yii;

class TestController extends ApiController {
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = [
            'list-content',
            'detail',
            'test-register',
            'test',
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'list-content' => ['GET'],
            'detail' => ['GET'],
            'related' => ['GET'],
        ];
    }
    public function actionTest(){
//        $stamp = mktime(23, 59, 59);
//        echo date('m-d-Y H:i:s',$stamp);
//        echo (int)Yii::getAlias('@default_site_id'); return;

        $url = "http://cache03-hni.cdn.tvod.com.vn/vod/t/x2NcmcZ62WvMN8W4eaivTQ/s/9xcmz93z3l/e/1475264152128/origin42_8089/26/2505/movies/Video/20120109/54b267e42662a66e44e5bf8edfcbac86.ssm/54b267e42662a66e44e5bf8edfcbac86.m3u8";
        $url = Content::makeLink($url,"123.30.23.186");

        return $url;
    }

}