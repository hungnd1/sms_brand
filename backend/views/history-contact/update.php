<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HistoryContact */

$this->title = 'Cập nhật chiến dịch: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Danh sách chiến dịch', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->campain_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Cập nhật chiến dịch
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

