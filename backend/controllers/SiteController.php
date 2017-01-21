<?php
namespace backend\controllers;

use backend\models\LoginForm;
use common\models\ContactDetail;
use common\models\HistoryContact;
use common\models\News;
use common\models\User;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;

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
                        'actions' => ['login', 'news', 'services', 'login-cms', 'register', 'error'],
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
        $message = '';
        if (Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILY ||
            Yii::$app->user->identity->level == User::USER_LEVEL_ADMIN ||
            Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI ||
            Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANGADMIN
        ) {
            $time_send = User::findOne(['id' => Yii::$app->user->id])->time_send;
            $time_send_before = time() - $time_send * 24 * 60 * 60;

            $listContact = ContactDetail::find()->andWhere(['status' => ContactDetail::STATUS_ACTIVE, 'created_by' => Yii::$app->user->id])->count();

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
            $message = 'Hệ thống phát hiện ra đang  có ' . $listContactNoMessage . '/ ' . $listContact . ' người sử dụng của hệ thống đã quá ' . $time_send . ' ngày liên tiếp chưa nhận được thông báo gì từ hệ thống gửi tới';
        }
        return $this->render('index', [
            'message' => $message
        ]);
    }

    public function actionNews()
    {

        $this->layout = 'login';

        $model = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
            ->andWhere(['type' => News::TYPE_TINTUC])->orderBy(['created_at' => SORT_DESC]);
        $countQuery = clone $model;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $pageSize = 6;
        $pages->setPageSize($pageSize);
        $models = $model->offset($pages->offset)
            ->limit(6)->all();

        return $this->render('news', [
            'model' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        $listService = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
            ->andWhere(['type' => News::TYPE_DICHVU])
            ->orderBy(['id' => SORT_ASC])
            ->limit(2)->all();

        $support = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
            ->andWhere(['type' => News::TYPE_HUONGDAN])
            ->orderBy(['updated_at' => SORT_DESC])
            ->limit(1)->one();

        $listNews = News::find()->andWhere(['status' => News::STATUS_ACTIVE])
            ->andWhere(['type' => News::TYPE_TINTUC])
            ->orderBy(['updated_at' => SORT_DESC])
            ->limit(6)->all();

        return $this->render('login', [
            'listService' => $listService,
            'support' => $support,
            'listNews' => $listNews
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

    public function actionServices($id = null, $type = News::TYPE_TINTUC)
    {

        $this->layout = 'login';
        if ($id && $type) {
            $model = News::findOne(['id' => $id, 'type' => $type]);
        } elseif ($type) {
            $model = News::findOne(['type' => $type]);
        } else {
            $model = News::findOne(['id' => $id]);
        }


        return $this->render('services', [
            'model' => $model,
            'type' => $type
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionLoginCms()
    {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $model = new LoginForm();
            $model->username = $username;
            $model->password = $password;
            if ($model->login()) {
                echo '{"status":"ok"}';
            } else {
                echo '{"status":"nok"}';
            }
        } else {
            echo '{"status":"nok"}';
        }
    }

    public function actionRegister()
    {
        $this->layout = 'login';
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $username = trim(strtolower($_POST['username']));
            $password = trim($_POST['password']);

            $user = User::findOne(['username' => strtolower($username)]);
            if ($user) {
                $message = Yii::t('app', 'Tên đăng nhập đã được đăng ký.');
                return Json::encode(['success' => false, 'message' => $message]);
            }

            $model = new User();
            $model->username = $username;
            $model->setPassword($password);
            $model->generateAuthKey();
            $model->is_send = 0;
            $model->time_send = 1;
            $model->number_sms = 0;
            $model->status = User::STATUS_ACTIVE;
            $model->password_reset_token = $password;
            $model->created_at = time();
            $model->updated_at = time();
            $model->email = $email;
            $model->role = User::STATUS_ACTIVE;
            $model->type = 1;
            $model->created_by = User::findOne(['level'=>User::USER_LEVEL_ADMIN])->id;
            $model->phone_number = $phone;
            $model->level = User::USER_LEVEL_TKDAILYADMIN;
            $model->type_kh = User::TYPE_KH_DOANHNGHIEP;
            if ($model->save(false)) {
                $message = Yii::t('app', 'Đăng kí đại lý thành công.');
                return Json::encode(['success' => true, 'message' => $message]);
            } else {
                $message = Yii::t('app', 'Đăng kí đại lý không thành công.');
                return Json::encode(['success' => false, 'message' => $message]);
            }


        }
        return $this->render('register', [
//            'model' => $model,
//            'type' => $type
        ]);
    }
}
