<?php

use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 8,
    'options' => ['enctype' => 'multipart/form-data'],
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_TINY,
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
    <div class="form-body">

        <?= $form->field($model, 'display_name')->textInput(['maxlength' => 250, 'class' => 'input-circle']) ?>
        <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'content')->widget(\common\widgets\CKEditor::className(), [
            'options' => [
                'rows' => 8,
            ],
            'preset' => 'basic'
        ]) ?>


        <?php if ($model->isNewRecord) { ?>
            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'showPreview' => true,
                    'overwriteInitial' => false,
                    'showRemove' => false,
                    'showUpload' => false
                ]
            ]) ?>
        <?php } else { ?>
            <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'previewFileType' => 'any',
                    'initialPreview' => [
                        Html::img(Url::to($model->getThumbnailLink()), ['class' => 'file-preview-image', 'alt' => $model->image, 'title' => $model->image]),
                    ],
                    'showPreview' => true,
                    'initialCaption' => $model->getThumbnailLink(),
                    'overwriteInitial' => true,
                    'showRemove' => false,
                    'showUpload' => false
                ]
            ]) ?>
        <?php } ?>
        <?= $form->field($model, 'status')->dropDownList(\common\models\News::listStatus()) ?>
    </div>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton($model->isNewRecord ? 'Tạo '.\common\models\News::getTypeName($type) : 'Cập nhật',
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Quay lại', ['index','type'=>$type], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>