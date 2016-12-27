<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Mark */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mark-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <div class="form-group">
        <table>
            <tr>
                <td style="padding-right: 10px">
                    <?=
                    $form->field($model, 'semester')->widget(Select2::classname(), [
                        'hideSearch' => true,
                        'data' => [1 => 'Học kỳ I', 2 => 'Học kỳ II'],
                        'pluginOptions' => [
                            'allowClear' => false,
                            'width' => '150px'
                        ],
                    ])->label('Theo học kỳ');
                    ?>
                </td>
                <td style="padding-right: 10px">
                    <?=
                    $form->field($model, 'class_id')->widget(Select2::classname(), [
                        'id' => 'class_id',
                        'data' => $dataContact,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '200px'
                        ],
                    ])->label('Theo lớp');
                    ?>
                </td>
                <td>
                    <?=
                    $form->field($model, 'subject_id')->widget(Select2::classname(), [
                        'data' => $dataSubject,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '200px'
                        ],
                    ])->label('Theo môn học');
                    ?>
                </td>
            </tr>
        </table>

        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
