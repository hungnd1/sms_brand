<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExamStudentRoom */
/* @var $form yii\widgets\ActiveForm */
?>


<script type="text/javascript">
    function submit_form(action) {
        $('#action').val(action);
        $("#my_form").submit();
    }
</script>

<!-- Tải file mẫu -->
<?php $form = ActiveForm::begin([
    'id' => 'my_form',
    'options' => ['enctype' => 'multipart/form-data'],
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'action' => ['upload'],
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

    <div class="caption" style="margin: 10px 0 0 10px">
        <i class="fa fa-cogs font-green-sharp"></i>
        <span class="caption-subject font-green-sharp bold uppercase">Tải file mẫu</span>
    </div>
    <div class="tools">
        <a href="javascript:;" class="collapse">
        </a>
    </div>

    <div class="row">
        <?= $form->field($model, 'action')->hiddenInput(['id' => 'action', 'value' => 'upload'])->label(false) ?>
        <div class="form-group field-content-price" style="padding-left: 27%;font-size: 15px">
            <span style="color: #ff4003; font-size: 14px; font-style: italic">Để đảm bảo thông tin được tải lên chính xác, <br>
                bạn cần điền thông tin vào file mẫu của chúng tôi.<br>
                Chọn tải file mẫu dưới đây:
            </span>
            <p></p>
            <?= Html::button('Tải file mẫu', ['class' => 'btn btn-success', 'onclick' => 'submit_form(\'download\')']) ?>
        </div>
    </div>

</div>

<div class="form-body">

    <div class="caption" style="margin: 10px 0 0 10px">
        <i class="fa fa-cogs font-green-sharp"></i>
        <span class="caption-subject font-green-sharp bold uppercase">Tải lên điểm kì thi</span>
    </div>
    <div class="tools">
        <a href="javascript:;" class="collapse">
        </a>
    </div>

    <?=
    $form->field($model, 'exam_room_id')->widget(Select2::classname(), [
        'data' => $dataRooms,
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '200px'
        ],
    ])->label('Chọn phòng thi: ');
    ?>

    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['multiple' => true, 'accept' => '*'],
        'pluginOptions' => [
            'previewFileType' => 'any',
            'showUpload' => false,
            'showPreview' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'overwriteInitial' => true
        ]
    ])->label('Chọn file tải lên:'); ?>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::button('Tải lên', ['class' => 'btn btn-success', 'onclick' => 'submit_form(\'upload\')']) ?>
                <?= Html::a('Quay lại', ['view-exam-mark-room'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
