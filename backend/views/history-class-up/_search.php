<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SchoolYear */
/* @var $form yii\widgets\ActiveForm */

$grade = Html::getInputId($model, 'grade');

$js = <<<JS
    $("#$grade").change(function () {
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
            </tr>
        </table>

    </div>

    <?php ActiveForm::end(); ?>

</div>
