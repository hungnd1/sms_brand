<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Điểm kỳ thi';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$columns = array(
    // Checkbox
    [
        'class' => '\kartik\grid\CheckboxColumn',
        'width' => '5%'
    ],
    // STT
    [
        'class' => '\kartik\grid\SerialColumn',
        'header' => 'STT',
        'width' => '5%'
    ],
    // SBD
    [
        'format' => 'raw',
        'class' => '\kartik\grid\DataColumn',
        'label' => 'SBD',
        'value' => function ($model) {
            return $model->identification;
        },
    ],
    // Students
    [
        'format' => 'raw',
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Tên thí sinh',
        'value' => function ($model) {
            return $model->student_name;
        },
    ]
);

// add mark summary for subject
foreach ($subjects as $subject) {
    array_push($columns, [
        'format' => 'raw',
        'label' => $subject->name,
        'attribute' => $subject->id,
        'class' => '\kartik\grid\DataColumn',
        'value' => function ($model, $key, $index, $column) {
            $marks = explode(';', $model->marks);
            foreach ($marks as $mark) {
                $tmp = explode(':', $mark);
                if (strcmp($column->attribute, $tmp[0]) == 0) {
                    return $tmp[1];
                }
            }
            return '';
        }
    ]);
}

array_push($columns, [
    'format' => 'raw',
    'class' => '\kartik\grid\DataColumn',
    'label' => 'Tổng điểm',
    'value' => function ($model) {
        return '';
    },
]);
array_push($columns, [
    'format' => 'raw',
    'class' => '\kartik\grid\DataColumn',
    'label' => 'Trung bình',
    'value' => function ($model) {
        return '';
    },
]);
array_push($columns, [
    'format' => 'raw',
    'class' => '\kartik\grid\DataColumn',
    'label' => 'Xếp loại',
    'value' => function ($model) {
        return '';
    },
]);
array_push($columns, [
    'format' => 'raw',
    'class' => '\kartik\grid\DataColumn',
    'label' => 'Xếp hạng',
    'value' => function ($model) {
        return '';
    },
]);
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Điểm kỳ thi</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <?= $model->id ?>

                <p>
                    <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Tải lên điểm phòng thi", Yii::$app->urlManager->createUrl(['/exam/view-upload', 'exam_id' => $model->exam_id]), ['class' => 'btn btn-success']) ?>
                    <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Xuất điểm phòng thi", Yii::$app->urlManager->createUrl(['/exam/view-export', 'exam_id' => $model->exam_id]), ['class' => 'btn btn-success']) ?>
                </p>

                <div style="margin: 25px 0 25px 0">
                    <?= $this->render('_search', [
                        'model' => $model,
                        'dataExams' => $dataExams,
                        'dataRooms' => $dataRooms
                    ]) ?>
                </div>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-exam-mark-id',
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'columns' => $columns
                ]); ?>
            </div>
        </div>
    </div>
</div>