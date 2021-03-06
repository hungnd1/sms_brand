<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\HistoryContact */

$this->title = 'Tạo chiến dịch';
$this->params['breadcrumbs'][] = ['label' => 'Danh sách chiến dịch', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo chiến dịch
                </div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('_form', [
                    'model' => $model,
                    'type' => $type
                ]) ?>
            </div>
        </div>
    </div>
</div>