<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ConfigSystem */

$this->title = 'Tạo cấu hình hệ thống';
$this->params['breadcrumbs'][] = ['label' => 'Cấu hình hệ thống', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo cấu hình hệ thống
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
