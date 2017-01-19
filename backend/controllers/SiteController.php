<?php
namespace backend\controllers;

use backend\models\LoginForm;
use common\models\ContactDetail;
use common\models\HistoryContact;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $message  = '';
        if (Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILY ||
            Yii::$app->user->identity->level == User::USER_LEVEL_ADMIN ||
            Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI ||
            Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANGADMIN
        ) {
            $time_send = User::findOne(['id' => Yii::$app->user->id])->time_send;
            $time_send_before = time() - $time_send * 24 * 60 * 60;

            $listContact = ContactDetail::find()->andWhere(['status'=>ContactDetail::STATUS_ACTIVE,'created_by'=>Yii::$app->user->id])->count();

            $listContactMessage = ContactDetail::find()
                ->innerJoin('history_contact_asm', 'history_contact_asm.contact_id = contact_detail.id')
                ->andWhere(['contact_detail.status' => ContactDetail::STATUS_ACTIVE])
                ->andWhere(['contact_detail.created_by' => Yii::$app->user->id])
                ->andWhere(['history_contact_asm.history_contact_status' => HistoryContact::STATUS_SUCCESS])
                ->andWhere(['<=', 'history_contact_asm.created_at', time()])
                ->andWhere(['>=', 'history_contact_asm.created_at', $time_send_before])
                ->orderBy(['history_contact_asm.created_at' => SORT_DESC])
                ->distinct('contact_detail.id')->count();
            $listContactNoMessage = intval($listContact) - intval($listContactMessage);
            $message = 'Hệ thống phát hiện ra đang  có '. $listContactNoMessage .'/ '.$listContact.' người sử dụng của hệ thống đã quá '.$time_send.' ngày liên tiếp chưa nhận được thông báo gì từ hệ thống gửi tới';
        }
        return $this->render('index', [
            'message' => $message
        ]);
    }

    public function actionLogin()
    {
        $this->layout='login';
        return $this->render('login', [
//            'model' => $model,
        ]);
//        if (!\Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->goBack();
//        } else {
//            return $this->render('login', [
//                'model' => $model,
//            ]);
//        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
