<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MarkType */
/* @var $form yii\widgets\ActiveForm */
?>

<script>
    function submitMarkType() {
        var form = $('#myFormConfigMarkType');
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize()
        });
        $('#popupMarkType').modal('hide');
    }
</script>

<style>
    .checkbox {
        width: 150px;
    }
</style>

<?php $form = ActiveForm::begin([
    'action' => ['config-mark-type'],
    'method' => 'post',
    'id' => 'myFormConfigMarkType',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ]
]); ?>

<div class="form-body">

    <table width="90%">
        <tr>
            <td><?= $form->field($model, 'mark_gioi')->textInput(['maxlength' => 300])->label('Điểm giỏi:') ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'mark_kha')->textInput(['maxlength' => 300])->label('Điểm khá:') ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'mark_tb')->textInput(['maxlength' => 300])->label('Điểm trung bình:') ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'mark_yeu')->textInput(['maxlength' => 300])->label('Điểm yếu:') ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'mark_kem')->textInput(['maxlength' => 300])->label('Điểm kém:') ?></td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 20px">
        <?= Html::button('Đồng ý', ['class' => 'btn btn-success', 'onclick' => 'submitMarkType();']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
