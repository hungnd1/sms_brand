<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Subject */

$this->title = 'Tác vụ năm học';
$this->params['breadcrumbs'][] = 'Lịch sử lên lớp';
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
                    <span class="caption-subject font-green-sharp bold uppercase">Lịch sử lên lớp</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <div class="top-notice">
                    Năm học hiện tại : 2016 - 2017. Nhà trường đã thực hiện kết thúc năm học 2015 - 2016. Nhà trường
                    chưa thực hiện kết thúc năm học 2016 - 2017
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
                                return $model->contact_name;
                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Sĩ số lớp cũ',
                            'value' => function ($model) {
                                return \common\models\ContactDetailSearch::countContactDetailByContactName($model->contact_name);
                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Tên lớp mới',
                            'value' => function ($model) {
                                return \common\models\Contact::getNewClassFromOldClass($model->contact_name);

                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Sĩ số lớp mới',
                            'value' => function ($model) {
                                $newClass = \common\models\Contact::getNewClassFromOldClass($model->contact_name);
                                return \common\models\ContactDetailSearch::countContactDetailByContactName($newClass);
                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '75%',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Chi tiết',
                            'value' => function ($model) {
                                return '';

                            },
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>

