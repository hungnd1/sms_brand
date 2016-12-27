<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Brandname */

$this->title = 'Cập nhật Brandname: ' . $model->brandname;
$this->params['breadcrumbs'][] = ['label' => 'Brandname', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->brandname, 'url' => ['view', 'id' => $model->id]];
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
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
