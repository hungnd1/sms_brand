<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Network */

$this->title = 'Cập nhật nhà mạng: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Nhà mạng', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Cập nhật nhà mạng
                </div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
