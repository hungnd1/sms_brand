<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Cập nhật Brandname: ' . \common\models\Brandname::findOne(['id'=>$model->brandname_id])->brandname;
$this->params['breadcrumbs'][] = ['label' => 'Brandname', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => \common\models\Brandname::findOne(['id'=>$model->brandname_id])->brandname, 'url' => ['view', 'id' => $model->brandname_id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Cập nhật Brandname
                </div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('updateform', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
