<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TemplateSms */

$this->title = 'Tạo tin nhắn mẫu';
$this->params['breadcrumbs'][] = ['label' => 'Tin nhắn mẫu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo tin nhắn mẫu
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
