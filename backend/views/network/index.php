<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NetworkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý đầu số';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Thông tin nhà mạng</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php echo Html::a("Tạo thông tin nhà mạng ", Yii::$app->urlManager->createUrl(['/network/create']), ['class' => 'btn btn-success']) ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-category-id',
                    'filterModel' => $searchModel,
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'columns' => [
                        [
                            'format' => 'raw',
                            'width' => '15%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'name',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Network */
                                return $model->name;

                            },
                        ],
                        [
                            'format' => 'raw',
                            'width' => '15%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'number_network',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Network */
                                return $model->number_network;

                            },
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width' => '15%',
                            'label' => 'Ngày tạo',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'created_at',
                            'value' => function ($model) {
                                return date('d-m-Y H:i:s', $model->created_at);
                            }
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width' => '15%',
                            'label' => 'Ngày cập nhật',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'updated_at',
                            'value' => function ($model) {
                                return date('d-m-Y H:i:s', $model->updated_at);
                            }
                        ],
                        [
                            'class' => 'kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'label' => 'Trạng thái',
                            'format' => 'html',
                            'value' => function ($model) {
                                /* @var $model \common\models\Network */
                                return $model->getStatusName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => [0 => 'Không hoạt động', 10 => 'Hoạt động'],
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => 'Tất cả'],
                        ],
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => '{update}',
//                            'dropdown' => true,
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
