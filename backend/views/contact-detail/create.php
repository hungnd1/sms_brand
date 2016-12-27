<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ContactDetail */

$this->title = 'Tạo chi tiết danh bạ';
$this->params['breadcrumbs'][] = ['label' => 'Chi tiết danh bạ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo chi tiết danh bạ
                </div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('_form', [
                    'model' => $model,
                    'id'=>$id
                ]) ?>
            </div>
        </div>
    </div>
</div>