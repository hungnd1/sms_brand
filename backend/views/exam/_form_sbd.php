<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\IdentificationNumber */
/* @var $form yii\widgets\ActiveForm */
?>

<script>
    function submitConfig() {
        var form = $('#myFormConfigSBD');
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize()
        });
        $('#popupSBD').modal('hide');
    }
</script>

<style>
    .checkbox {
        width: 150px;
    }
</style>

<?php $form = ActiveForm::begin([
    'action' => ['config-sbd'],
    'method' => 'post',
    'id' => 'myFormConfigSBD',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ]
]); ?>

<div class="form-body">

    <table>
        <tr>
            <td collapse="2"><?= $form->field($model, 'isOderByName')->checkbox(['label' => 'Sắp xếp theo họ tên', 'disabled' => true]) ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'isPrefix')->checkbox(['label' => 'Thêm tiền tố']) ?></td>
            <td style="padding-left: 20px"><?= $form->field($model, 'prefix')->textInput(['maxlength' => 300])->label(false) ?></td>
        </tr>
        <tr>
            <td><?= $form->field($model, 'isLenght')->checkbox(['label' => 'Chiều dài đánh số']) ?></td>
            <td style="padding-left: 20px">
                <?php
                $a = [1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'];
                echo $form->field($model, 'lenght')->dropDownList($a)->label(false);
                ?>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 20px">
        <?= Html::button('Đồng ý', ['class' => 'btn btn-success', 'onclick' => 'submitConfig();']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
