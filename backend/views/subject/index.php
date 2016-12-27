<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Subject */

$this->title = 'Môn học';
$this->params['breadcrumbs'][] = 'Danh sách môn học';
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Môn học</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Thêm môn học ", Yii::$app->urlManager->createUrl(['/subject/create']), ['class' => 'btn btn-success']) ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-subject-id',
                    'filterModel' => $searchModel,
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
                            'attribute' => 'id',
                            'value' => function ($model) {
                                return $model->id;

                            },
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'name',
                            'label' => 'Tên môn',
                            'value' => function ($model) {
                                return $model->name;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'code',
                            'label' => 'Mã môn',
                            'value' => function ($model) {
                                return $model->code;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'description',
                            'label' => 'Mô tả',
                            'value' => function ($model) {
                                return $model->description;
                            },
                        ],
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'template' => '{view} {update}',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

