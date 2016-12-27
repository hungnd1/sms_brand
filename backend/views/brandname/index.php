<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BrandnameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Thông tin brandname';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Thông tin brandname</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php echo Html::a("Tạo brandname ", Yii::$app->urlManager->createUrl(['/brandname/create']), ['class' => 'btn btn-success']) ?>
                    <?php echo Html::a("Gán brandname cho người dùng ", Yii::$app->urlManager->createUrl(['/brandname/owner']), ['class' => 'btn btn-success']) ?>
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
                            'attribute' => 'brandname',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Brandname */
                                return Html::a($model->brandname, ['view', 'id' => $model->id],['class'=>'label label-primary']);

                            },
                        ],
                        'attribute' => 'number_sms',
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'price_sms',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Brandname */
                                return \common\models\Brandname::formatNumber($model->price_sms). ' VNĐ';
                            },
                        ],
//                        [
//                            'class' => '\kartik\grid\DataColumn',
//                            'attribute' => 'brand_member',
//                            'value'=>function ($model, $key, $index, $widget) {
//                                /** @var $model \common\models\Brandname */
//                                return \common\models\User::findOne(['id'=>$model->brand_member])->username ;
//                            },
//                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'price_total',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Brandname */
                                return \common\models\Brandname::formatNumber($model->price_sms * $model->number_sms). ' VNĐ';
                            },
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width'=>'15%',
                            'label' => 'Ngày hết hạn',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'expired_at',
                            'value' => function($model){
                                return date('d-m-Y', $model->expired_at);
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
                            'attribute' => 'status',
                            'label'=>'Trạng thái',
                            'format' => 'html',
                            'value' => function($model){
                                /* @var $model \common\models\Brandname */
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
//                            'dropdown' => true,
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>