<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Network */

$this->title = 'Tạo nhà mạng';
$this->params['breadcrumbs'][] = ['label' => 'Nhà mạng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo nhà mạng
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