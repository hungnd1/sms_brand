<?php

use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tất cả danh bạ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Thông tin danh bạ</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php echo Html::a("Tạo danh bạ ", Yii::$app->urlManager->createUrl(['/contact/create']), ['class' => 'btn btn-success']) ?>
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
                            'class' => 'kartik\grid\CheckboxColumn',
                            'headerOptions' => ['class' => 'kartik-sheet-style'],
                        ],
                        [
                            'format' => 'raw',
                            'width'=>'15%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'contact_name',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Contact */
                                    if(Yii::$app->user->identity->level == \common\models\User::USER_LEVEL_ADMIN || Yii::$app->user->identity->type_kh == \common\models\User::TYPE_KH_DOANHNGHIEP){
                                        return Html::a($model->contact_name, ['/contact-detail/index', 'id' => $model->id],['class'=>'label label-primary']);
                                    }else{
                                        return $model->contact_name;
                                    }


                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'description',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Contact */
                                return substr($model->description,0,20);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'created_by',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Contact */
                                return \common\models\User::findOne(['id'=>$model->created_by])->username ;
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
                                /* @var $model \common\models\Contact */
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
    <?php if(Yii::$app->user->identity->level ==  \common\models\User::USER_LEVEL_ADMIN || Yii::$app->user->identity->type_kh ==  \common\models\User::TYPE_KH_TRUONGHOC){ ?>
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Thông tin danh bạ lớp học</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p>
                    <?php  echo Html::a("Tạo danh bạ lớp học ", Yii::$app->urlManager->createUrl(['/contact/create','type'=>1]), ['class' => 'btn btn-success']) ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProviderClass,
                    'id'=>'grid-category-id_',
                    'filterModel' => $searchModel1,
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
                            'width'=>'15%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'contact_name',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Contact */
                                return Html::a($model->contact_name, ['/contact-detail/index', 'id' => $model->id],['class'=>'label label-primary']);

                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'description',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Contact */
                                return substr($model->description,0,20);
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'path',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Contact */
                                return \common\models\Contact::findOne(['id'=>$model->path]) ? \common\models\Contact::findOne(['id'=>$model->path])->contact_name : '' ;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'created_by',
                            'value'=>function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\Contact */
                                return \common\models\User::findOne(['id'=>$model->created_by])->username ;
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
                                /* @var $model \common\models\Contact */
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

    <?php } ?>
</div>
<?php
Modal::begin([
    'header' => '<h4>Thông báo</h4>',
    'id' => 'myModal',
    'size' => ''
]);

echo $this->render('_popup', [
    'message' => $message
]);
Modal::end();
?>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
    $(function() {
        var message = '<?= $message != '' ? $message : 0 ?>';
        if(message != 0){
            $('#myModal').modal('show');
        }
    });
    function closeP(){
        $('#myModal').modal('toggle');
    }
</script>