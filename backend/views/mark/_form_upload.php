<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Mark */
/* @var $form yii\widgets\ActiveForm */
?>


<script type="text/javascript">

    function show() {
        var class_id = $('#mark-class_id').val();
        alert(class_id);
    }

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
        <div class="form-group field-content-price" style="padding-left: 27%;font-size: 15px;">
            <?= $form->field($model, 'action')->hiddenInput(['id' => 'action', 'value' => 'upload'])->label(false) ?>
            <span style="color: #ff4003; font-size: 14px; font-style: italic">Để đảm bảo thông tin được tải lên chính xác, <br>
                bạn cần điền thông tin vào file mẫu của chúng tôi.<br>
                Chọn tải file mẫu dưới đây:
            </span>
            <p></p>
        </div>
        <?=
        $form->field($model, 'subject_id')->widget(Select2::classname(), [
            'size' => Select2::MEDIUM,
            'data' => \yii\helpers\ArrayHelper::map(\common\models\Subject::find()->all(), 'id', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'width' => '50%'
            ],
            'options' => ['placeholder' => 'Select a subject ...', 'multiple' => true],
        ])->label('Chọn môn học');
        ?>

        <div class="form-group field-content-price" style="padding-left: 27%;font-size: 15px;">
            <?= Html::button('Tải file mẫu', ['class' => 'btn btn-success', 'onclick' => 'submit_form(\'download\')']) ?>
        </div>
    </div>

</div>

<div class="form-body">

    <div class="caption" style="margin: 10px 0 0 10px">
        <i class="fa fa-cogs font-green-sharp"></i>
        <span class="caption-subject font-green-sharp bold uppercase">Tải lên danh sách điểm</span>
    </div>
    <div class="tools">
        <a href="javascript:;" class="collapse">
        </a>
    </div>

    <?=
    $form->field($model, 'semester')->widget(Select2::classname(), [
        'hideSearch' => true,
        'data' => [1 => 'Học kỳ I', 2 => 'Học kỳ II'],
        'pluginOptions' => [
            'allowClear' => false,
            'width' => '50%'
        ],
    ])->label('Chọn học kỳ:');
    ?>

    <?=
    $form->field($model, 'class_id')->widget(Select2::classname(), [
        'id' => 'class_id',
        'data' => \yii\helpers\ArrayHelper::map(\common\models\Contact::getAllClasses(), 'id', 'contact_name'),
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '50%'
        ],
    ])->label('Chọn lớp:');
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
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
