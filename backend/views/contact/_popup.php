<?php

/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 29-Dec-16
 * Time: 11:06 AM
 */
use common\helpers\TBApplication;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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

    <div class="row" style="padding: 0 10px 0 10px;">
            <p><?= $message ?></p>
    </div>
    <br>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9" style="padding-left: 10%;">
                <?= Html::a('Xem danh sách', ['message'], ['class' => 'btn btn-success']) ?>
                <?= Html::button('Bỏ qua', ['onclick'=>'closeP();', 'class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
    <br>
    <?php ActiveForm::end(); ?>

