<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExamRooms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exam-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <div class="form-group">
        <table>
            <tr>
                <td style="padding-right: 10px">
                    <?=
                    $form->field($model, 'exam_id')->widget(Select2::classname(), [
                        'data' => $dataExams,
                        'pluginOptions' => [
                            'allowClear' => false,
                            'width' => '150px'
                        ],
                    ])->label('Kỳ thi');
                    ?>
                </td>
                <td style="padding-right: 10px">
                    <?=
                    $form->field($model, 'id')->widget(Select2::classname(), [
                        'data' => $dataRooms,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '200px'
                        ],
                    ])->label('Phòng thi');
                    ?>
                </td>
            </tr>
        </table>

        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
