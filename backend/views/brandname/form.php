<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserBrandname */
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


    <?php
        echo $form->field($model, 'id')->widget(Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\common\models\User::getUserBrandname(), 'id', 'username'),
            'options' => ['placeholder' => 'Chủ sở hữu'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Chủ sở hữu');

    ?>
    <?php
    echo $form->field($model,'brandname_id')->widget(Select2::className(),[
        'data' => \yii\helpers\ArrayHelper::map(\common\models\User::getBrandname(), 'id', 'brandname'),
        'options' => ['placeholder' => 'Chọn brandname'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>


    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton("Gán brandname ",['class' => 'btn btn-success','value'=>'owner']) ?>
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

