<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TemplateSmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\TemplateSms */
$this->title = 'Tin nhắn mẫu';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs font-green-sharp"></i>
                        <span class="caption-subject font-green-sharp bold uppercase">Tin nhắn mẫu</span>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse">
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <p>
                        <?php if(!Yii::$app->params['tvod1Only']) echo Html::a("Thêm tin nhắn mẫu ", Yii::$app->urlManager->createUrl(['/template-sms/create']), ['class' => 'btn btn-success']) ?>
                        <?php if(!Yii::$app->params['tvod1Only']) echo Html::a("Tải tin nhắn mẫu ", Yii::$app->urlManager->createUrl(['/template-sms/upload']), ['class' => 'btn btn-success']) ?>
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
                                'width'=>'5%',
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'id',
                                'value'=>function ($model, $key, $index, $widget) {
                                    /** @var $model \common\models\TemplateSms */
                                    return $model->id;

                                },
                            ],
                            [
                                'format' => 'raw',
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'template_name',
                                'value'=>function ($model, $key, $index, $widget) {
                                    /** @var $model \common\models\TemplateSms */
                                    return Html::a($model->template_name, ['view', 'id' => $model->id],['class'=>'label label-primary']);

                                },
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'template_content',
                                'value'=>function ($model, $key, $index, $widget) {
                                    /** @var $model \common\models\TemplateSms */
                                    return substr($model->template_content,0,50).'...';
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
                                    /* @var $model \common\models\TemplateSms */
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
