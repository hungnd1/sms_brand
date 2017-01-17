<?php

use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
?>

<?= GridView::widget([
    'dataProvider' => $queueDetailExamRoom,
    'id' => 'grid-subjects-id',
    'pjax' => true,
    'columns' => [
        // STT
        [
            'class' => '\kartik\grid\SerialColumn',
            'header' => 'STT',
            'width' => '5%'
        ],
        // Name
        [
            'format' => 'raw',
            'class' => '\kartik\grid\DataColumn',
            'label' => 'DS phòng thi',
            'value' => function ($model) {
                return $model->room_name;
            },
            'group' => true,
        ],
        // Students
        [
            'format' => 'raw',
            'class' => '\kartik\grid\DataColumn',
            'label' => 'Học sinh',
            'value' => function ($model) {
                $rs = $model->room_student .
                    ' <a href="javascript: showExamStudentRoom(' . $model->exam_room_id . ');" style="text-decoration: none; color: blue">(Chi tiết)</a>';
                return $rs;
            },
            'group' => true,
            'subGroupOf' => 1
        ],
        // Subjects
        [
            'format' => 'raw',
            'class' => '\kartik\grid\DataColumn',
            'label' => 'Môn thi',
            'value' => function ($model) {
                return $model->subject_name;
            },
        ],
        // Locations
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'location',
            'label' => 'Địa điểm',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => 'Địa điểm',
                'size' => 'sm',
                'asPopover' => false,
                'formOptions' => [
                    'action' => Url::to(['exam/update-queue-detail-exam-room'])
                ]

            ]
        ],
        // Supervisory
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'supervisory',
            'label' => 'Giáo viên coi thi',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => 'Giáo viên coi thi',
                'size' => 'sm',
                'asPopover' => false,
                'formOptions' => [
                    'action' => Url::to(['exam/update-queue-detail-exam-room'])
                ]
            ]
        ],
        // Exam hours
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'exam_hour',
            'label' => 'Giờ thi',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => 'Giờ thi',
                'asPopover' => false,
                'size' => 'sm',
                'formOptions' => [
                    'action' => Url::to(['exam/update-queue-detail-exam-room'])
                ],
            ]
        ],
        // Exam dates
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'exam_date',
            'label' => 'Ngày thi',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => 'Ngày thi',
                'size' => 'sm',
                'asPopover' => false,
                'inputType' => Editable::INPUT_DATE,
                'formOptions' => [
                    'action' => Url::to(['exam/update-queue-detail-exam-room'])
                ],
                'options' => [
                    'pluginOptions' => ['format' => 'dd-mm-yyyy'] // javascript format
                ]
            ]
        ],
    ],
]); ?>

<div class="div_rooms" id="div_rooms_new" style="width: 500px; display: none">
    <?= GridView::widget([
        'dataProvider' => $queueExamRoom,
        'id' => 'grid-queue-exam-room-id',
        'pjax' => true,
        'columns' => [
            // STT
            [
                'class' => '\kartik\grid\SerialColumn',
                'header' => 'STT',
                'width' => '5%'
            ],
            // Name
            [
                'format' => 'raw',
                'class' => '\kartik\grid\DataColumn',
                'label' => 'DS phòng thi',
                'value' => function ($model) {
                    return $model->name;
                }
            ],
            // Students
            [
                'format' => 'raw',
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Số thí sinh',
                'value' => function ($model) {
                    return $model->number_student;
                }
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript: deleteQueueRoom(' . $model->id . ');', [
                            'title' => Yii::t('app', 'Delete'),
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>


<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => [
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ]
]); ?>

<table width="100%" style="margin: 10px 0 5px 15px; display: none" id="sts_add_new">
    <tr>
        <td width="5%">Khối:</td>
        <td width="17%">
            <?php
                $tmp = array();
                foreach ($grades as $grade){
                    $tmp[$grade] = $grade;
                }
                echo $form->field($queueExamRoomModel, 'grade')->dropDownList($tmp)->label(false);
            ?>
        </td>
        <td width="5%">Số thí sinh: </td>
        <td width="163px">
            <?= $form->field($queueExamRoomModel, 'number_student')->textInput()->label(false) ?>
        </td>
        <td><?= Html::button('Thêm', ['class' => 'btn btn-primary', 'style' => 'margin: 0 0 8px 0', 'onclick' => 'addRoom()']) ?></td>
    </tr>
</table>
<?php ActiveForm::end(); ?>

<div style="margin-left: 17px; display: none" id="sts_grade_new">
    <p>Học sinh chưa thuộc phòng</p>
    <p id="isShowAdd" style="display: none"><?= count($studentInGradeDelete) ?></p>
    <p>
        <?php
        $grades = array_keys($studentInGrade);
        $tmp = '';
        foreach ($grades as $grade) {
            $studentInGradeDeleteCount = isset($studentInGradeDelete[$grade]) ? count($studentInGradeDelete[$grade]) : 0;
            $tmp = $tmp . 'Khối ' . $grade . ': ' . $studentInGradeDeleteCount . '/' . count($studentInGrade[$grade]) . '|';
        }
        echo substr($tmp, 0, -1);
        ?>
    </p>
</div>
