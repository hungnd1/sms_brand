<?php

use kartik\grid\GridView;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Mark */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Điểm môn học';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Điểm môn học</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <p>
                    <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Tải lên điểm môn học", Yii::$app->urlManager->createUrl(['/mark/view-upload']), ['class' => 'btn btn-success']) ?>
                    <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Xuất điểm môn học", Yii::$app->urlManager->createUrl(['/mark/view-export']), ['class' => 'btn btn-success']) ?>
                </p>

                <div style="margin: 25px 0 25px 0">
                    <?= $this->render('_search', [
                        'model' => $model,
                        'dataContact' => $dataContact,
                        'dataSubject' => $dataSubject
                    ]) ?>
                </div>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-subject-id',
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'beforeHeader' => [
                        [
                            'columns' => [
                                ['content' => '', 'options' => ['colspan' => 1, 'class' => 'text-center']],
                                ['content' => '', 'options' => ['colspan' => 1, 'class' => 'text-center']],
                                ['content' => '', 'options' => ['colspan' => 1, 'class' => 'text-center ']],
                                ['content' => 'Miệng', 'options' => ['colspan' => 5, 'class' => 'text-center']],
                                ['content' => '15\'', 'options' => ['colspan' => 5, 'class' => 'text-center']],
                                ['content' => '1 Tiết', 'options' => ['colspan' => 5, 'class' => 'text-center']],
                                ['content' => 'Thi HK', 'options' => ['colspan' => 1, 'class' => 'text-center']],
                                ['content' => 'TBHK', 'options' => ['colspan' => 1, 'class' => 'text-center']],
                                ['content' => '', 'options' => ['colspan' => 1, 'class' => 'text-center']],
                            ],
                            'options' => ['class' => 'skip-export'] // remove this row from export
                        ]
                    ],

                    'columns' => [
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
                        // Name
                        [
                            'format' => 'raw',
                            'label' => 'Tên học sinh',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                $student = \common\models\ContactDetail::findOne($model->student_id);
                                return $student->fullname;
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        // Mieng
                        [
                            'format' => 'raw',
                            'label' => '1',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[0], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[0];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '2',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[1], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[1];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '3',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[2], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[2];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '4',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[3], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[3];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '5',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[4], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[4];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        // 15'
                        [
                            'format' => 'raw',
                            'label' => '1',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[5], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[5];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '2',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[6], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[6];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '3',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[7], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[7];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '4',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[8], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[8];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '5',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[9], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[9];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        // 1 tiết
                        [
                            'format' => 'raw',
                            'label' => '1',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[10], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[10];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '2',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[11], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[11];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '3',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[12], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[12];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '4',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[13], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[13];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        [
                            'format' => 'raw',
                            'label' => '5',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[14], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[14];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        // Thi HK
                        [
                            'format' => 'raw',
                            'label' => '1',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[15], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[15];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        // TBHK
                        [
                            'format' => 'raw',
                            'label' => '1',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                if (strcmp(explode(';', $model->marks)[16], 'N') == 0) {
                                    return '';
                                }
                                return explode(';', $model->marks)[16];
                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                        //Xếp hạng
                        [
                            'format' => 'raw',
                            'label' => 'Xếp hạng',
                            'width' => '5%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'id',
                            'value' => function ($model) {
                                return '';

                            },
                            'headerOptions' => ['style' => 'text-align:center'],
                            'mergeHeader' => true,
                            'enableSorting' => false,
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
