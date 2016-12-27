<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContactDetail */

$this->title = 'Cập nhật chi tiết danh bạ: ' . $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Chi tiết danh bạ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Cập nhật chi tiết
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
