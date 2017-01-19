<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContactDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Đánh giá nhận xét theo ngày';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Đánh giá nhận xét</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div style="margin: 25px 0 25px 0">
                    <?= $this->render('_comment_day', [
                        'model' => $model,
                    ]) ?>
                </div>
                <br><br>
                <br><br>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-category-ida',
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'columns' => [
//                        [
//                            'class' => 'kartik\grid\CheckboxColumn',
//                            'headerOptions' => ['class' => 'kartik-sheet-style'],
//                        ],
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'fullname',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return Html::a($model->fullname, ['/contact-detail/view', 'id' => $model->id], ['class' => 'label label-primary']);

                            },
                        ],
                        [
                            'class' => 'kartik\grid\EditableColumn',
                            'attribute' => 'comment',
                            'width' => '60%',
                            'label' => 'Nhận xét',
                            'pageSummary' => 'Page Total',
                            'vAlign'=>'middle',
                            'headerOptions'=>['class'=>'kv-sticky-column'],
                            'contentOptions'=>['class'=>'kv-sticky-column'],
                            'editableOptions'=>['header'=>'Nhận xét', 'size'=>'md'],
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Comment */
                                $listComment = explode(',',$model->comment);
                                $string = '';
                                $i = 0;
                                $listComments = \common\models\TemplateComment::find()->andWhere(['in','id',$listComment])
                                    ->andWhere(['status'=>\common\models\TemplateComment::STATUS_ACTIVE])->all();
                                if($listComments){
                                    foreach($listComments as $item){
                                        /** @var $item \common\models\TemplateComment */
                                        $string .= '('.$listComment[$i].') '.$item->comment.' ';
                                        $i++;
                                    }
                                }
                                return $string;
                            }
                        ],
                        [
                            'class' => 'kartik\grid\EditableColumn',
                            'attribute' => 'comment_bonus',
                            'pageSummary' => 'Page Total',
                            'label' => 'Nhận xét bổ sung',
                            'vAlign'=>'middle',
                            'headerOptions'=>['class'=>'kv-sticky-column'],
                            'contentOptions'=>['class'=>'kv-sticky-column'],
                            'editableOptions'=>['header'=>'Nhận xét bổ sung', 'size'=>'md']
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>