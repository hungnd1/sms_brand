<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExamStudentRoom */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'id' => 'export',
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
    $form->field($model, 'exam_room_id')->widget(Select2::classname(), [
        'data' => $dataRooms,
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '200px'
        ],
    ])->label('Chọn phòng thi: ');
    ?>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton('Xuất file', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Quay lại', ['view-exam-mark-room'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
