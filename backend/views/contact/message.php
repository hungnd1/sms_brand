<?php

use common\models\User;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContactDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách người chưa nhận được tin nhắn đã quá ' . \common\models\User::findOne(['id' => Yii::$app->user->id])->time_send;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Danh sách người chưa nhận tin</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-category-ida',
                    'filterModel' => $searchModel,
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'columns' => [
                        [
                            'class' => 'kartik\grid\CheckboxColumn',
                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                        ],
                        [
                            'format' => 'raw',
                            'width' => '15%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'fullname',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return Html::a($model->fullname, ['/contact-detail/view', 'id' => $model->id], ['class' => 'label label-primary']);

                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'gender',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->gender == 1 ? 'Nam' : 'Nữ';
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'address',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->address;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'phone_number',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->phone_number;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'email',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->email;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'company',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->company;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'created_by',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return \common\models\User::findOne(['id' => $model->created_by])->username;
                            },
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width' => '15%',
                            'label' => 'Ngày sinh',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'birthday',
                            'value' => function ($model) {
                                return date('d-m-Y', $model->birthday);
                            }
                        ],
                        [
                            'class' => 'kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'label' => 'Trạng thái',
                            'format' => 'html',
                            'value' => function ($model) {
                                /* @var $model \common\models\ContactDetail */
                                return $model->getStatusName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => [0 => 'Không hoạt động', 10 => 'Hoạt động'],
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => 'Tất cả'],
                        ],
                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{view}{update}{delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['contact-detail/view', 'id' => $model->id]), [
                                        'title' => 'Thông tin chi tiết',
                                    ]);

                                },
                            ]
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>