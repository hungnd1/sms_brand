<?php

use kartik\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\QueueDetailExamRoom */
/* @var $form yii\widgets\ActiveForm */
?>

<?= GridView::widget([
    'dataProvider' => $queueDetailExamRoom,
    'id' => 'grid-subjects-id',
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
                    ' <a href="javascript: showExamStudentRoom(' . $model->exam_room_id .');" style="text-decoration: none; color: blue">(Chi tiết)</a>';
                return $rs;
            },
            'group' => true,
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
            'format' => 'raw',
            'class' => '\kartik\grid\DataColumn',
            'label' => 'Địa điểm',
            'value' => function ($model) {
                return $model->location;
            },
        ],
        // Supervisory
        [
            'format' => 'raw',
            'class' => '\kartik\grid\DataColumn',
            'label' => 'Giáo viên coi thi',
            'value' => function ($model) {
                return $model->supervisory;
            },
        ],
        // Exam hours
        [
            'format' => 'raw',
            'class' => '\kartik\grid\DataColumn',
            'label' => 'Giờ thi',
            'value' => function ($model) {
                return $model->exam_hour;
            },
        ],
        // Exam dates
        [
            'format' => 'raw',
            'class' => '\kartik\grid\DataColumn',
            'label' => 'Ngày thi',
            'value' => function ($model) {
                return $model->exam_date;
            },
        ],
    ],
]); ?>