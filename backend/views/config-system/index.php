<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ConfigSystemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cấu hình hệ thống';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Thông tin cấu hình</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php echo Html::a("Tạo thông tin cấu hình ", Yii::$app->urlManager->createUrl(['/config-system/create']), ['class' => 'btn btn-success']) ?>
                </p>

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
                            'attribute' => 'code',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ConfigSystem */
                                return Html::a($model->code, ['view', 'id' => $model->id],['class'=>'label label-primary']);

                            },
                        ],
                        'attribute' => 'content',
                        [
                            'format' => 'raw',
                            'width'=>'15%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'description',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ConfigSystem */
                                return substr($model->description,0,40);

                            },
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
                            'attribute' => 'status',
                            'label'=>'Trạng thái',
                            'format' => 'html',
                            'value' => function($model){
                                /* @var $model \common\models\ConfigSystem */
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
                            'template' => '{update} {view}',
//                            'dropdown' => true,
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>