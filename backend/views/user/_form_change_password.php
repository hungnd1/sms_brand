<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'method' => 'post',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 12,
    'action' => ['user/reset-password', 'id' => $model->id],
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'showLabels' => true,
        'labelSpan' => 2,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]);
$formId = $form->id;
?>
<div class="form-body">
    <?= $form->field($model,'new_password')->passwordInput(['placeholder' => 'Nhập mật khẩu có độ dài  tối thiểu 8 kí tự'])->label('Mật khẩu mới (*)') ?>
    <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => 'Nhập lại mật khẩu'])->label('Xác nhận mật khẩu  (*)') ?>

</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton('Cập nhật',
                ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Hủy thao tác', ['view', 'id' => $model->id], ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>


