<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
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

<div class="portlet-body">

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
                'label' => 'Tên học sinh',
                'value' => function ($model) {
                    return $model->fullname;
                },
            ],
            [
                'format' => 'raw',
                'width' => '5%',
                'attribute' => $className,
                'class' => '\kartik\grid\DataColumn',
                'label' => 'Lớp',
                'value' => function ($model, $key, $index, $column) {
                    return $column->attribute;
                },
            ],
        ],
    ]); ?>

</div>


