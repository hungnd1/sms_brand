<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContactDetail */
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
        <?= $form->field($model, 'fullname')->textInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
        <?= $form->field($model, 'phone_number')->textInput(['maxlength' => 12, 'class' => 'input-circle']) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => 100, 'class' => 'input-circle']) ?>
        <?= $form->field($model, 'company')->textInput(['maxlength' => 250, 'class' => 'input-circle']) ?>
        <?= $form->field($model, 'address')->textarea(['rows' => 2]) ?>
        <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>
        <?php
        echo $form->field($model, 'birthday')->widget(\kartik\date\DatePicker::className(), [
            'options' => ['placeholder' => 'Chọn ngày sinh'],
            'convertFormat' => true,
            'pluginOptions' => [
                'format' => 'dd-M-yyyy',
                'todayHighlight' => true
            ]
        ]);
        ?>
        <?= $form->field($model, 'gender')->dropDownList(
            \common\models\ContactDetail::getListGender(), ['class' => 'input-circle']
        ) ?>
        <?= $form->field($model, 'status')->dropDownList(
            \common\models\ContactDetail::getListStatus(), ['class' => 'input-circle']
        ) ?>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    <?= Html::submitButton($model->isNewRecord ? 'Tạo chi tiết danh bạ' : 'Cập nhật',
                        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <?php if($model->isNewRecord) {?>
                        <?= Html::a('Quay lại', ['index','id'=>$id], ['class' => 'btn btn-default']) ?>
                    <?php }else{ ?>
                        <?= Html::a('Quay lại', ['index','id'=>$model->contact_id], ['class' => 'btn btn-default']) ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>