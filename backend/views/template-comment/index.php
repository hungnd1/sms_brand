<?php

use common\models\TemplateComment;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TemplateCommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nhận xét mẫu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"> <?=Yii::t("app","Danh sách nhận xét mẫu")?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p><?= Html::a(Yii::t("app","Tạo nhận xét mẫu"),
                        Yii::$app->urlManager->createUrl(['/template-comment/create']),
                        ['class' => 'btn btn-success']) ?>
                    <?= Html::a(Yii::t("app","Import nhận xét mẫu"),
                        Yii::$app->urlManager->createUrl(['/template-comment/import']),
                        ['class' => 'btn btn-success']) ?>
                </p>

                <?php
                $gridColumn = [
                    [
                        'format' => 'raw',
                        'width' => '150px',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'id',
                        'label' => Yii::t("app","Mã nhận xét"),
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'comment',
                        'label' => Yii::t("app","Nhận xét"),
                        'format' => 'raw'
                    ],
                    [
                        'class' => 'kartik\grid\DataColumn',
                        'attribute' => 'status',
                        'label' => 'Trạng thái',
                        'format' => 'html',
                        'value' => function ($model) {
                            /* @var $model \common\models\TemplateComment */
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
                        'class' => 'kartik\grid\ActionColumn',
                        'header' => Yii::t("app","Tác động"),
                        'template' => '{view}  {update} {delete}',
//                            'dropdown' => true,
                    ],
                ];
                ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'content-index-grid',
                    'filterModel' => $searchModel,
                    'responsive' => true,
                    'pjax' => false,
                    'hover' => true,
                    'columns' => $gridColumn
                ]); ?>

            </div>
        </div>
    </div>
</div>