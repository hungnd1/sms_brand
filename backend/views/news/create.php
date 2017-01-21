<?php


/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = 'Tạo ' . \common\models\News::getTypeName($type);
$this->params['breadcrumbs'][] = ['label' => \common\models\News::getTypeName($type), 'url' => ['index', 'type' => $type]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo <?= \common\models\News::getTypeName($type) ?>
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

