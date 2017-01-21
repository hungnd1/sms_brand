<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SchoolYear */
/* @var $form yii\widgets\ActiveForm */

$grade = Html::getInputId($model, 'grade');
$class = Html::getInputId($model, 'class');

$js = <<<JS
    $("#$grade").change(function () {
        $("#my_form").submit();
    });

    $("#$class").change(function () {
        $("#my_form").submit();
    });

    $('#button_end_school_year').click(function(){
    var url = document.getElementById('end_school').value;
       $('#my_form').attr('action', url);
       $("#my_form").submit();
    });

    $('#button_start_school_year').click(function(){
        var url = document.getElementById('start_school').value;
       $('#my_form').attr('action', url);
       $("#my_form").submit();
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="mark-search">

    <?php $form = ActiveForm::begin([
        'id' => 'my_form',
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <div class="form-group">
        <table>
            <tr>
                <input id="start_school" type="hidden" value="<?= \yii\helpers\Url::toRoute(['school-year/start-school-year']) ?>">
                <input id="end_school" type="hidden" value="<?= \yii\helpers\Url::toRoute(['school-year/end-school-year']) ?>">
            </tr>
            <tr>
                <td style="padding-right: 10px">
                    <?=
                    $form->field($model, 'grade')->widget(Select2::classname(), [
                        'data' => $grades,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '150px'
                        ],
                        'options' => ['placeholder' => 'Tất cả'],
                    ])->label('Theo khối');
                    ?>
                </td>
                <td style="padding-right: 10px">
                    <?=
                    $form->field($model, 'class')->widget(Select2::classname(), [
                        'data' => $dataContact,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '200px'
                        ],
                        'options' => ['placeholder' => 'Tất cả'],
                    ])->label('Theo lớp');
                    ?>
                </td>
                <td><?= Html::button('Bắt đầu năm học', ['class' => 'btn btn-primary', 'style' => 'margin: 10px 10px 0 0', 'id' => 'button_start_school_year']) ?></td>
                <td><?= Html::button('Kết thúc năm học', ['class' => 'btn btn-primary', 'style' => 'margin: 10px 0 0 0', 'id' => 'button_end_school_year']) ?></td>
            </tr>
        </table>

    </div>

    <?php ActiveForm::end(); ?>

</div>
