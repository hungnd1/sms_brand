<?php

use kartik\datecontrol\DateControl;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Brandname */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 8,
    'options' => ['enctype' => 'multipart/form-data'],
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]); ?>
<div class="form-body">

    <?= $form->field($model, 'brandname')->textInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'brand_username')->textInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'brand_password')->passwordInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'number_sms')->textInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'price_sms')->textInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
<!--    --><?php
//        echo $form->field($model, 'brand_member')->widget(Select2::classname(), [
//            'data' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->andWhere(['user.status' => \common\models\User::STATUS_ACTIVE])
//                ->all(), 'id', 'username'),
//            'options' => ['placeholder' => 'Chủ sở hữu'],
//            'pluginOptions' => [
//                'allowClear' => true
//            ],
//        ]);
//
//    ?>
    <?php
    echo $form->field($model, 'expired_at')->widget(\kartik\date\DatePicker::className(), [
        'options' => ['placeholder' => 'Chọn ngày hết hạn'],
        'convertFormat' => true,
        'pluginOptions' => [
            'format' => 'dd-M-yyyy',
            'todayHighlight' => true
        ]
    ]);
    ?>
    <?= $form->field($model, 'status')->dropDownList(
        \common\models\Brandname::getListStatus(), ['class' => 'input-circle']
    ) ?>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton($model->isNewRecord ? 'Tạo brandname' : 'Cập nhật',
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

