<?php

use common\models\MarkType;
use common\widgets\Nav;
use common\widgets\NavBar;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Điểm kỳ thi';
$this->params['breadcrumbs'][] = $this->title;
?>

    <script>
        // show config mark type popup
        function showConfigMarkType() {
            $('#popupMarkType').modal('show');
        }
    </script>

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
        return is_null($model->mark_summary) ? '' : $model->mark_summary;
    },
]);
array_push($columns, [
    'format' => 'raw',
    'class' => '\kartik\grid\DataColumn',
    'label' => 'Trung bình',
    'value' => function ($model) {
        return is_null($model->mark_avg) ? '' : $model->mark_avg;
    },
]);
array_push($columns, [
    'format' => 'raw',
    'class' => '\kartik\grid\DataColumn',
    'label' => 'Xếp loại',
    'value' => function ($model) {
        if (is_null($model->mark_type)) return '';
        if ($model->mark_type == MarkType::MARK_TYPE_GIOI) {
            return 'Giỏi';
        } else if ($model->mark_type == MarkType::MARK_TYPE_KHA) {
            return 'Khá';
        } else if ($model->mark_type == MarkType::MARK_TYPE_TB) {
            return 'Trung bình';
        } else if ($model->mark_type == MarkType::MARK_TYPE_YEU) {
            return 'Yếu';
        } else if ($model->mark_type == MarkType::MARK_TYPE_KEM) {
            return 'Kém';
        }
    },
]);
array_push($columns, [
    'format' => 'raw',
    'class' => '\kartik\grid\DataColumn',
    'label' => 'Xếp hạng',
    'value' => function ($model) {
        return is_null($model->mark_rank) ? '' : $model->mark_rank;
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
                    <div>
                        <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Tải lên điểm phòng thi", Yii::$app->urlManager->createUrl(['/exam/view-upload', 'exam_id' => $model->exam_id]), ['class' => 'btn btn-success']) ?>
                        <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Xuất điểm phòng thi", Yii::$app->urlManager->createUrl(['/exam/view-export', 'exam_id' => $model->exam_id]), ['class' => 'btn btn-success']) ?>
                        <?= Html::button('Cấu hình xếp loại', ['class' => 'btn btn-success', 'onclick' => 'showConfigMarkType();']) ?>
                        <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Tính toán điểm", Yii::$app->urlManager->createUrl(['/exam/calculator', 'exam_id' => $model->exam_id]), ['class' => 'btn btn-success']) ?>
                    </div>

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

<?php
Modal::begin([
    'header' => '<p style="font-weight: bold; font-size: 18px; text-align: center">Cấu hình xếp loại</p>',
    'options' => [
        'id' => 'popupMarkType',
        'tabindex' => false // important for Select2 to work properly
    ],
]);

$model = new \common\models\MarkType();
$markTypes = MarkType::find()->all();
foreach ($markTypes as $markType) {
    if ($markType->type == MarkType::MARK_TYPE_GIOI) {
        $model->mark_gioi = $markType->mark;
    } else if ($markType->type == MarkType::MARK_TYPE_KHA) {
        $model->mark_kha = $markType->mark;
    } else if ($markType->type == MarkType::MARK_TYPE_TB) {
        $model->mark_tb = $markType->mark;
    } else if ($markType->type == MarkType::MARK_TYPE_YEU) {
        $model->mark_yeu = $markType->mark;
    } else {
        $model->mark_kem = $markType->mark;
    }
}

echo $this->render('_form_mark_type', [
    'model' => $model
]);
Modal::end();
?>