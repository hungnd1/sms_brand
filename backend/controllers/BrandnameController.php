<?php

namespace backend\controllers;

use common\components\ActionLogTracking;
use common\models\Brandname;
use common\models\BrandnameSearch;
use common\models\User;
use common\models\UserActivity;
use common\models\UserBrandname;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BrandnameController implements the CRUD actions for Brandname model.
 */
class BrandnameController extends BaseBEController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            [
                'class' => ActionLogTracking::className(),
                'user' => Yii::$app->user,
                'model_type_default' => UserActivity::ACTION_TARGET_BRANDNAME,
            ],
        ]);
    }

    /**
     * Lists all Brandname models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BrandnameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Brandname model.
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
     * Creates a new Brandname model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Brandname();

        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->created_by = Yii::$app->user->id;
            $model->brand_hash_token = $model->brand_password;
            $model->setPassword($model->brand_password);
            $model->expired_at = strtotime($model->expired_at);
                if (!$model->save()) {
                $model->expired_at = date('d-m-Y', $model->expired_at);
                Yii::$app->session->setFlash('error', 'Thêm brandname khong thành công');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            Yii::$app->session->setFlash('success', 'Thêm brandname thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Brandname model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->expired_at = date('d-m-Y', $model->expired_at);
        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->updated_at = time();
            $model->expired_at = strtotime($model->expired_at);
            if ($model->save(false)) {
                \Yii::$app->getSession()->setFlash('success', 'Cập nhật thành công');

                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Brandname model.
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
     * Finds the Brandname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Brandname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Brandname::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionOwner()
    {
        $model = new UserBrandname();

        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {

            $model_user = User::findOne(['id'=>$model->id]);
            $model_user->brandname_id = $model->brandname_id;
            if (!$model_user->save(false)) {
                Yii::$app->session->setFlash('error', 'Gán brandname không thành công');
                return $this->render('brandname', [
                    'model' => $model,
                ]);
            }
            Yii::$app->session->setFlash('success', 'Gán brandname thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('brandname', [
                'model' => $model,
            ]);
        }
    }

    public function actionOwnerUpdate($id)
    {
        $model = $this->findModelUserBrandname($id);
        $id = $model->id;
        $model->setScenario('admin_create_update');
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isAjax && isset($post['ajax']) && $model->load($post)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if($id == $model->id){
                if (!$model->save(false)) {
                    Yii::$app->session->setFlash('error', 'Cập nhật brandname không thành công');
                    return $this->render('brandname_update', [
                        'model' => $model,
                    ]);
                }
            }else{
                $brandname_id = $model->brandname_id;
                $model->brandname_id = null;
                $userOther = User::findOne(['id'=>$model->id]);
                $userOther->brandname_id = $brandname_id;
                $model->id = $id;
                if (!$userOther->save(false) || !$model->save(false)) {
                    Yii::$app->session->setFlash('error', 'Cập nhật brandname không thành công');
                    return $this->render('brandname_update', [
                        'model' => $model,
                    ]);
                }
            }
            Yii::$app->session->setFlash('success', 'Cập nhật brandname thành công');
            return $this->redirect(['index']);
        } else {
            return $this->render('brandname_update', [
                'model' => $model,
            ]);
        }
    }

    protected function findModelUserBrandname($id)
    {
        if (($model = UserBrandname::findOne(['brandname_id'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
