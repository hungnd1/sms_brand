<?php

use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\helpers\Html;
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

