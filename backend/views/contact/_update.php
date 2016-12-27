<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Contact */

$this->title = 'Cập nhật danh bạ lớp học: ' . $model->contact_name;
$this->params['breadcrumbs'][] = ['label' => 'Danh bạ lớp học', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->contact_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Cập nhật danh bạ lớp học
                </div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
