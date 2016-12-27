<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UserBrandname */

$this->title = 'Tạo brandname';
$this->params['breadcrumbs'][] = ['label' => 'Brandname', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo brandname
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
