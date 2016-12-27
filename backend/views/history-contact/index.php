<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\HistoryContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách chiến dịch';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Thông tin chiến dịch</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id'=>'grid-category-id',
                    'filterModel' => $searchModel,
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'columns' => [
                        [
                            'format' => 'raw',
                            'width'=>'15%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'campain_name',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\HistoryContact */
                                return $model->campain_name;

                            },
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width'=>'15%',
                            'attribute' => 'content',
                            'value' => function($model){
                                return \common\helpers\CUtils::subString($model->content,20);
                            }
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width'=>'15%',
                            'label' => 'Ngày gửi tin',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'send_schedule',
                            'value' => function($model){
                                return $model->send_schedule ? date('d-m-Y H:i:s', $model->send_schedule) : 'Đã gửi luôn';
                            }
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width'=>'15%',
                            'label' => 'Ngày tạo',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'created_at',
                            'value' => function($model){
                                return date('d-m-Y H:i:s', $model->created_at);
                            }
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width'=>'15%',
                            'label' => 'Ngày cập nhật',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'updated_at',
                            'value' => function($model){
                                return date('d-m-Y H:i:s', $model->updated_at);
                            }
                        ],
                        [
                            'class' => 'kartik\grid\DataColumn',
                            'attribute' => 'type',
                            'label'=>'Kiểu tin nhắn',
                            'format' => 'html',
                            'value' => function($model){
                                /* @var $model \common\models\HistoryContact */
                                return $model->getTypeName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => [1 => 'Tin nhắn chăm sóc khách hàng', 2 => 'Tin nhắn quảng cáo'],
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => 'Tất cả'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>