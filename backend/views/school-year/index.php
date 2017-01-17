<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Subject */

$this->title = 'Tác vụ năm học';
$this->params['breadcrumbs'][] = 'Công việc trong năm';
?>

<style>
    .top-notice {
        background-color: #fcfac9;
        border: 1px solid #ede98a;
        color: #796616;
        margin: 1% 0;
        padding: 5px;
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Công việc trong năm</span>
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
                        'dataContact' => $dataContact,
                        'grades' => $grades,
                    ]) ?>
                </div>


                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-school-year-id',
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
                            'width' => '10%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Lớp',
                            'value' => function ($model) {
                                return $model->contact_name;
                            },
                        ],

                        [
                            'format' => 'raw',
                            'width' => '85%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Trạng thái',
                            'value' => function ($model) {
                                $rs = '<p>Đã cập nhập danh sách học sinh</p>';
                                $numStudents = \common\models\ContactDetailSearch::countContactDetailByContactName($model->contact_name);
                                if($numStudents==0){
                                    $rs = '<p>Chưa cập nhập danh sách học sinh</p>';
                                }
                                if ($model->school_year_status == 0) $rs = $rs . '<p>Chưa bắt đầu';
                                else if ($model->school_year_status == 1) $rs = $rs . '<p>Đã bắt đầu';
                                else $rs = $rs . '<p>Đã kết thúc';

                                $rs = $rs . ' năm học</p>';

                                return $rs;
                            },
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>

