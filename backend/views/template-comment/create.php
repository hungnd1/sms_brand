<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TemplateComment */

$this->title = 'Tạo nhận xét mẫu';
$this->params['breadcrumbs'][] = ['label' => 'Danh sách nhận xét mẫu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i><?=Yii::t("app","Tạo nhận xét mẫu")?>
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

