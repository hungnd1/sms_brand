<?php

use common\models\User;
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
    'action' => ['user/update-owner'],
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
    <?= $form->field($model, 'username')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'maxlength' => 100]) ?>
    <?= $form->field($model, 'fullname')->textInput(['placeholder' => 'Họ và tên', 'maxlength' => 255]) ?>
    <?php if (Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILY ||
        Yii::$app->user->identity->level == User::USER_LEVEL_ADMIN ||
        Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI ||
        Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANGADMIN
    ) { ?>
        <?= $form->field($model, 'time_send')->textInput(['placeholder' => 'Thời gian cấu hình', 'maxlength' => 2])->label('Thời gian cấu hình') ?>
    <?php } ?>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <?= Html::submitButton('Cập nhật',
                ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Hủy thao tác', ['view', 'id' => $model->id],
                ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>


