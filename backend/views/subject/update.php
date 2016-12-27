<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Subject */

$this->title = 'Cập nhập: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách môn học', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhập';
?>

<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Cập nhật môn học
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