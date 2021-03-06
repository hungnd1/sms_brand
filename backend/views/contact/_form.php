<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Contact */
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
    <div class="row">
        <div class="form-group field-content-price" style="padding-left: 27%;font-size: 15px;">
            <?php  echo Html::a("Tải file mẫu ", Yii::$app->urlManager->createUrl(['/contact/download-template']), ['class' => 'btn btn-danger']) ?>
        </div>
        <br><br>
        <?= $form->field($model, 'file')->widget(FileInput::classname(), [
            'options' => ['multiple' => true, 'accept' => '*'],
            'pluginOptions' => [
                'previewFileType' => 'any',
                'showUpload' => false,
                'showPreview' => false,
                'browseLabel' => '',
                'removeLabel' => '',
                'overwriteInitial'=>true
            ]
        ]); ?>
    </div>
    <?= $form->field($model, 'contact_name')->textInput(['maxlength' => 500, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'status')->dropDownList(
        \common\models\Contact::getListStatus(), ['class' => 'input-circle']
    ) ?>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton($model->isNewRecord ? 'Tạo danh bạ' : 'Cập nhật',
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

