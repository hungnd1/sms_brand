<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MarkSummary */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- Tải file mẫu -->
<?php $form = ActiveForm::begin([
    'id' => 'export',
    'options' => ['enctype' => 'multipart/form-data'],
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'action' => ['export'],
    'fullSpan' => 8,
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]); ?>

<div class="form-body">

    <?=
    $form->field($model, 'semester')->widget(Select2::classname(), [
        'hideSearch' => true,
        'data' => [1 => 'Học kỳ I', 2 => 'Học kỳ II'],
        'pluginOptions' => [
            'allowClear' => false,
        ],
    ])->label('Chọn học kỳ:');
    ?>

    <?=
    $form->field($model, 'class_id')->widget(Select2::classname(), [
        'id' => 'class_id',
        'data' => \yii\helpers\ArrayHelper::map(\common\models\Contact::getAllClasses(), 'id', 'contact_name'),
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label('Chọn lớp:');
    ?>

    <?=
    $form->field($model, 'subject_id')->widget(Select2::classname(), [
        'size' => Select2::MEDIUM,
        'data' => \yii\helpers\ArrayHelper::map(\common\models\Subject::find()->all(), 'id', 'name'),
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'options' => ['placeholder' => 'Select a subject ...', 'multiple' => true],
    ])->label('Chọn môn học');
    ?>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton('Xuất file', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
