<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Contact */

$this->title = 'Thêm mới danh bạ lớp học';
$this->params['breadcrumbs'][] = ['label' => 'Dạnh bạ lớp học', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo danh bạ lớp học
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
