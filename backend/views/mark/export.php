<?php


/* @var $this yii\web\View */
/* @var $model common\models\MarkSummary */

$this->title = 'Xuất danh sách điểm';
$this->params['breadcrumbs'][] = ['label' => 'Điểm môn học', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Xuất danh sách điểm
                </div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('_form_export', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
