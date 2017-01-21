<?php

use kartik\widgets\Spinner;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Subject */

$this->title = 'Tác vụ năm học';
$this->params['breadcrumbs'][] = 'Lịch sử lên lớp';
?>

<script>
    function showClass(id) {
        $.ajax({
            url: '?r=history-class-up%2Fshow&id=' + id,
            success: function (data) {
                $('#myModalContent').html(data);
                $('#popupClass').modal('show');
            }
        });
    }
</script>

<style>
    .top-notice {
        background-color: #fcfac9;
        border: 1px solid #ede98a;
        color: #796616;
        margin: 1% 0;
        padding: 5px;
        text-align: center;
    }

    .modal-header {
        padding: 10px 10px 0 0;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Lịch sử lên lớp</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <div class="top-notice">
                    <?php $year = \common\models\SMSBrandUtil::getCurrentSchoolYear(); ?>
                    Năm học hiện tại : <?= $year . ' - ' . ($year + 1) ?>.
                    Nhà trường đã thực hiện kết thúc năm học <?= ($year - 1) . ' - ' . $year ?>.
                    Nhà trường
                    <?php
                    if ($schoolYearStatus == 0) echo 'chưa bắt đầu';
                    else if ($schoolYearStatus == 1) echo 'đã bắt đầu';
                    else echo 'đã kết thúc';
                    ?>
                    năm học <?= $year . ' - ' . ($year + 1) ?>
                </div>

                <div style="margin: 25px 0 25px 0">
                    <?= $this->render('_search', [
                        'model' => $model,
                        'grades' => $grades,
                    ]) ?>
                </div>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-history-up-class-id',
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'columns' => [
                        [
                            'class' => '\kartik\grid\SerialColumn',
                            'header' => 'STT',
                            'width' => '5%',
                        ],
                        [
                            'format' => 'raw',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Tên lớp cũ',
                            'value' => function ($model) {
                                return $model->old_class_name;
                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Sĩ số lớp cũ',
                            'value' => function ($model) {
                                return $model->number_old_class_students;
                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Tên lớp mới',
                            'value' => function ($model) {
                                return $model->new_class_name;

                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Sĩ số lớp mới',
                            'value' => function ($model) {
                                return $model->number_new_class_students;
                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '75%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Chi tiết',
                            'value' => function ($model) {
                                $rs = 'Đã chuyển ' . $model->number_old_class_students .
                                    ' học sinh từ lớp ' . $model->old_class_name . ' lên lớp ' . $model->new_class_name .
                                    ' - <a href="javascript:showClass(' . $model->new_class_id . ');" style="color: blue"> Chi tiết</a> <br/>
                                Lớp ' . $model->old_class_name . ' có 0 học sinh bị lưu ban 
                                - <a href="javascript:showClass(' . $model->old_class_id . ');" style="color: blue">Chi tiết</a>';
                                return $rs;

                            },
                        ],
                    ],
                ]); ?>

                <?php
                Modal::begin([
                    'header' => '<p style="font-weight: bold; font-size: 20px; margin-left: 10px">Danh sách chi tiết</p>',
                    'id' => 'popupClass',
                    'closeButton' => [
                        'label' => 'Close',
                        'class' => 'btn btn-danger btn-sm pull-right',
                    ],
                ]);
                echo "<div id='myModalContent'></div>";
                Modal::end();
                ?>
            </div>
        </div>
    </div>

