<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TemplateComment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-body">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'id' => 'form-create-device',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        //'enableAjaxValidation' => true,
        'enableClientValidation' => true,

    ]); ?>

    <h3 class="form-section"><?=Yii::t("app","Nhận xét mẫu")?></h3>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'status')->dropDownList(
                \common\models\TemplateComment::getListStatus()
            ) ?>
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-2 col-md-10">
            <?= Html::submitButton($model->isNewRecord ? Yii::t("app","Lưu lại") : Yii::t("app","Cập nhật"), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
