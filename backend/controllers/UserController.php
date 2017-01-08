<?php

namespace backend\controllers;

use api\helpers\Message;
use common\components\ActionLogTracking;
use common\components\ActionProtectSuperAdmin;
use common\helpers\APISMS;
use common\helpers\FileUtils;
use common\helpers\TBApplication;
use common\models\AuthAssignment;
use common\models\AuthItem;
use common\models\Brandname;
use common\models\HistoryContact;
use common\models\User;
use common\models\UserActivity;
use common\models\UserHistory;
use common\models\UserSearch;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseBEController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => ActionProtectSuperAdmin::className(),
                'user' => Yii::$app->user,
                'update_user' => function ($action, $params) {
                    return $model = User::findOne($params['id']);
                },
                'only' => ['update', 'delete', 'view']
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ],
            ],
            [
                'class' => ActionLogTracking::className(),
                'user' => Yii::$app->user,
                'model_type_default' => UserActivity::ACTION_TARGET_USER,
                'post_action' => [
                    ['action' => 'create', 'accept_ajax' => false],
                    ['action' => 'send', 'accept_ajax' => true],
                    ['action' => 'config', 'accept_ajax' => true],
                    ['action' => 'delete', 'accept_ajax' => false],
                    ['action' => 'update', 'accept_ajax' => false],
                    ['action' => 'change-password', 'accept_ajax' => false],
                    ['action' => 'add-auth-item', 'accept_ajax' => true],
                    ['action' => 'revoke-auth-item', 'accept_ajax' => true]
                ],
                'only' => ['create', 'delete', 'update', 'change-password', 'add-auth-item', 'revoke-auth-item', 'send', 'config']
            ],
        ]);
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (isset($_POST['hasEditable'])) {
            $post = Yii::$app->request->post();
            if ($post['editableKey']) {
                $user = User::findOne($post['editableKey']);
                $index = $post['editableIndex'];
                if ($user) {
                    $user->load($post['User'][$index], '');
                    if($user->update()){
                        echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
                    }
                } else {
                    echo \yii\helpers\Json::encode(['output' => '', 'message' => \Yii::t('app', 'Người dùng không tồn tại')]);
                }
            } else {
                echo \yii\helpers\Json::encode(['output' => '', 'message' => '']);
            }
            return;
        }
        $searchModel = new UserSearch();
        $params = Yii::$app->request->queryParams;
        $params['UserSearch']['type'] = User::USER_TYPE_ADMIN;
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $active = 1)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'active' => $active
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $close int
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setScenario('create');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->status = User::STATUS_ACTIVE;
            $model->created_by = Yii::$app->user->id;
            $model->password_reset_token = $model->password;
            $model->setPassword($model->password);
            $model->is_send = 0;
            $model->time_send = 1;
            $model->number_sms = 0;
            $model->generateAuthKey();
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Thêm người dùng thành công');
                return $this->redirect(['index']);

            } else {
                Yii::$app->session->setFlash('error', 'Thêm người dùng không thành công');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /** get giá trị username */
        $username = $model->username;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            /** Set lại username */
            $model->username = $username;
            if ($model->level == User::USER_LEVEL_TKKHACHHANGADMIN || $model->level == User::USER_LEVEL_TKKHACHHANG_DAILY
                || $model->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI || $model->level == User::USER_LEVEL_TKTHANHVIEN_KHACHHANGDAILY
                || $model->level == User::USER_LEVEL_TKTHANHVIEN_KHADMIN || $model->level == User::USER_LEVEL_TKTHANHVIEN_KHAHHANGDAILYCAPDUOI
            ) {
                $numbersms = User::find()->andWhere(['created_by' => Yii::$app->user->id])
                    ->andWhere(['level' => $model->level])
                    ->andWhere('id <> :id', [':id' => $model->id])->all();
                $numbersms_total = 0; //tong so tin nhan cua thanh vien da co cua khach hang do
                foreach ($numbersms as $item) {
                    $numbersms_total += $item->number_sms;
                }
                $numbersms_total = $numbersms_total + $model->number_sms;
                $smsbrand = Brandname::findOne(['id' => Yii::$app->user->identity->brandname_id]);
                if ($numbersms_total > $smsbrand->number_sms) {
                    Yii::$app->session->setFlash('error', 'Người dùng đã vượt quá số tin ' . ($numbersms_total - $smsbrand->number_sms));
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                } else if ($model->update(false)) {
                    Yii::$app->session->setFlash('success', 'Cập nhật người dùng thành công');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else if ($model->update(false)) {
                Yii::$app->session->setFlash('success', 'Cập nhật người dùng thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->getSession()->setFlash('error', Message::MSG_FAIL);
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionInfo()
    {
        $user = Yii::$app->user->identity;
        return $this->render('info', ['model' => $user]);
    }

    public function actionUpdateOwner()
    {

        /**
         * @var $model User
         */
        $model = Yii::$app->user->identity;
//        $model->setScenario('change-password');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            Yii::$app->session->setFlash('success', Message::MSG_UPDATE_PROFILE);
            return $this->redirect(['info']);
        } else {
            Yii::$app->session->setFlash('error', Message::MSG_FAIL);
            return $this->redirect(['info']);
        }
    }

    /**
     * @param $id
     * @return array|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionResetPassword($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('reset-password');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->password = $model->new_password;
            $model->setPassword($model->password);
            $model->generateAuthKey();
            if ($model->update()) {
                Yii::$app->session->setFlash('success', 'Khôi phục mật khẩu thành công');
                return $this->redirect(['view', 'id' => $model->id]);
            }
            Yii::$app->getSession()->setFlash('error', Message::MSG_FAIL);

//            if($model->validatePassword($model->old_password)){
//                $model->password = $model->new_password;
//                $model->setPassword($model->password);
//                $model->generateAuthKey();
//                $model->old_password = $model->new_password;
//                if($model->update()){
//                    Yii::$app->session->setFlash('success', 'Đổi mật khẩu thành công');
//                    return $this->redirect(['view', 'id' => $model->id]);
//                }
//                Yii::$app->getSession()->setFlash('error', Message::MSG_FAIL);
//            }else{
//                Yii::$app->getSession()->setFlash('error', 'Mật khẩu cũ không đúng');
//            }

        }
//        Yii::info($model->getErrors());

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionChangePassword($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('change-password');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validatePassword($model->old_password)) {
                $model->password = $model->new_password;
                $model->setPassword($model->password);
                $model->generateAuthKey();
                $model->old_password = $model->new_password;
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', 'Đổi mật khẩu thành công');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                Yii::$app->getSession()->setFlash('error', Message::MSG_FAIL);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Mật khẩu cũ không đúng');
            }

        }
        Yii::info($model->getErrors());

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionOwnerChangePassword()
    {
        /**
         * @var $model User
         */
        $model = Yii::$app->user->identity;
        $model->setScenario('change-password');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            $model->setPassword($model->new_password);
//            $model->generateAuthKey();
//            $model->old_password = $model->new_password;
//            if($model->update()){
//                Yii::$app->session->setFlash('success', 'Thay đổi mật khẩu user "'.$model->username.'" thành công!');
//                return $this->redirect(['info']);
//            }else{
//                Yii::error($model->getErrors());
//            }
//        } else {
//            Yii::$app->session->setFlash('error', 'Thay đổi mật khẩu user "'.$model->username.'" không thành công!');
//            return $this->redirect(['info']);
//        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validatePassword($model->old_password)) {
                $model->setPassword($model->new_password);
                $model->generateAuthKey();
                $model->old_password = $model->new_password;
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', 'Đổi mật khẩu thành công');
                    return $this->redirect(['info']);
                }
                Yii::$app->getSession()->setFlash('error', Message::MSG_FAIL);
            } else {
                Yii::$app->getSession()->setFlash('error', 'Mật khẩu cũ không đúng');
            }

        }
        return $this->redirect(['info']);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);

        $model = $this->findModel($id);
        if ($model->id == Yii::$app->user->getId()) {
            Yii::$app->session->setFlash('error', 'Bạn không thể thực hiện chức năng này!');
            return $this->redirect(['index']);
        }
        $model->status = User::STATUS_DELETED;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Xóa thành công');
            return $this->redirect(['index']);
        }
//        var_dump($model->getFirstErrors());
//        exit;
        Yii::$app->session->setFlash('error', Message::MSG_FAIL);
        return $this->redirect(['index']);

    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function actionRevokeAuthItem()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $post = Yii::$app->request->post();

        $success = false;
        $message = "Tham số không đúng";

        if (isset($post['user']) && isset($post['item'])) {
            $user = $post['user'];
            $item = $post['item'];

            $mapping = AuthAssignment::find()->andWhere(['user_id' => $user, 'item_name' => $item])->one();
            if ($mapping) {
                if ($mapping->delete()) {
                    $success = true;
                    $message = "Đã xóa quyền '$item' khỏi user '$user'!";
                } else {
                    $message = "Lỗi hệ thống, vui lòng thử lại sau";
                }
            } else {
                $message = "Quyền '$item' chưa được cấp cho user '$user'!";
            }

        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }

    /**
     * add items to user
     * @param  $id - id of user
     * @return mixed
     */
    public function actionAddAuthItem($id)
    {
        /* @var $model User */
        $model = User::findOne(['id' => $id]);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $success = false;
        $message = "User/nhóm quyền không tồn tại'";

        if ($model) {
            $post = Yii::$app->request->post();

            if (isset($post['addItems'])) {
                $items = $post['addItems'];

                $count = 0;

                foreach ($items as $item) {
                    $role = AuthItem::findOne(['name' => $item]);
                    $mapping = new AuthAssignment();
                    $mapping->item_name = $item;
                    $mapping->user_id = $id;
                    if ($mapping->save()) {
                        $count++;
                    }
                }


                if ($count > 0) {
                    $success = true;
                    $message = "Đã thêm $count nhóm quyền cho người dùng '$model->username'";

                }
            }
        }

        return [
            'success' => $success,
            'message' => $message
        ];
    }

    public function actionSend()
    {
        if (isset($_POST['arr_member']) && isset($_POST['content'])) {
            $content = $_POST['content'];
            $user_current = User::findOne(['id' => Yii::$app->user->id]);
            $brand = Brandname::findOne(['id' => $user_current->brandname_id]);
            FileUtils::errorLog("*****BAT DAU GUI TIN CHO USER*****");
            FileUtils::infoLog("*****BAT DAU GUI TIN CHO USER*****");
            $total_sucess = 0;
            for ($i = 0; $i < sizeof($_POST['arr_member']); $i++) {
                FileUtils::errorLog("********Bắt đầu gửi tin nhắn ********");
                $user = new UserHistory();
                $user->type = HistoryContact::TYPE_CSKH;
                $user->brandname_id = $user_current->brandname_id;
                $user->content = $content;
                $user->created_at = time();
                $user->updated_at = time();
                $user->user_id = $_POST['arr_member'][$i];
                $user->member_by = Yii::$app->user->id;
                $phone_number = User::findOne(['id' => $_POST['arr_member'][$i]])->phone_number;
                $contact_content = HistoryContact::getTemplateUser($content, $_POST['arr_member'][$i]);
                $contact_content = TBApplication::removesign($contact_content, '');
                $sotin = strlen($contact_content) / 160;
                if (strlen($contact_content) >= 0 && strlen($contact_content) < 160)
                    $sotin = 1;
                $content_number = round($sotin);
                $user->content_number = $content_number;
                $result_send = 0;
                if ($user_current->number_sms >= $total_sucess) {
                    $callAPI = new APISMS();
                    $result_send = $callAPI->sent($brand->brand_username, $brand->brand_password, $brand->brandname, $phone_number, $contact_content, $_POST['arr_member'][$i]);
                    FileUtils::errorLog(trim($result_send) . " cua sdt " . $phone_number . " co brandname la " . $brand->brandname);
                }
                $user->api_sms_id = $result_send;
                if (trim($result_send) == "0|Success") {
                    FileUtils::errorLog("Gui tin nhan thanh cong co sdt " . $phone_number . " co brandname la " . $brand->brandname);
                    $user->history_contact_status = 1;
                    $total_sucess += $content_number;
                } else {
                    $user->history_contact_status = 0;
                }
                $user->save(false);
                FileUtils::errorLog("**********Kết thúc gửi tin nhắn*********");
            }
            $user_current->number_sms = $user_current->number_sms - $total_sucess;
            if ($user_current->save(false)) {
                FileUtils::errorLog("*****KET THUC THANH CONG  GUI TIN CHO USER*****");
                FileUtils::infoLog("*****KET THUC THANH CONG GUI TIN CHO USER*****");
                echo '{"status":"ok"}';
            } else {
                FileUtils::errorLog("*****KET THUC THAT BAI  GUI TIN CHO USER*****");
                FileUtils::infoLog("*****KET THUC THAT BAI GUI TIN CHO USER*****");
                echo '{"status":"nok"}';
            }

        } else {
            echo '{"status":"nok"}';
        }
    }

    public function actionConfig()
    {
        if (isset($_POST['arr_member'])) {
            $is_kh = 1;
            for ($i = 0; $i < sizeof($_POST['arr_member']); $i++) {
                $user = User::find()->andWhere(['id' => $_POST['arr_member'][$i]])->one();
                if ($user->level != User::USER_LEVEL_TKKHACHHANG_DAILY && $user->level != User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI
                    && $user->level != User::USER_LEVEL_TKKHACHHANGADMIN && $user->level != User::USER_LEVEL_TKTHANHVIEN_KHADMIN
                    && $user->level != User::USER_LEVEL_TKTHANHVIEN_KHACHHANGDAILY && $user->level != User::USER_LEVEL_TKTHANHVIEN_KHAHHANGDAILYCAPDUOI
                ) {
                    $is_kh = 0;
                }
            }
            if ($is_kh == 1) {
                for ($i = 0; $i < sizeof($_POST['arr_member']); $i++) {
                    $user = User::find()->andWhere(['id' => $_POST['arr_member'][$i]])->one();
                    if ($user->is_send == 1) {
                        $user->is_send = 0;
                    } else {
                        $user->is_send = 1;
                    }
                    $user->updated_at = time();
                    $user->save(false);
                }
            }
            if ($is_kh == 1) {
                echo '{"status":"ok"}';
            } else {
                echo '{"status":"nok"}';
            }
        } else {
            echo '{"status":"nok"}';
        }
    }
}
