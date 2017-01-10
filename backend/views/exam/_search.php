<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ExamStudentRoom */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$examId = Html::getInputId($model, 'exam_id');
$examRoomId = Html::getInputId($model, 'exam_room_id');

$js = <<<JS
    $("#$examId").change(function () {
        $("#my_form").submit();
    });

    $("#$examRoomId").change(function () {
        $("#my_form").submit();
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="exam-search">

    <?php $form = ActiveForm::begin([
        'action' => ['view-exam-mark-room'],
        'method' => 'post',
        'id' => 'my_form'
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
                    $form->field($model, 'exam_room_id')->widget(Select2::classname(), [
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
    </div>
    <?php ActiveForm::end(); ?>
</div>
