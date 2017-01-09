<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ExamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Điểm kỳ thi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Điểm kỳ thi</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <p>
                    <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Tải lên điểm phòng thi", Yii::$app->urlManager->createUrl(['/mark/view-upload']), ['class' => 'btn btn-success']) ?>
                    <?php if (!Yii::$app->params['tvod1Only']) echo Html::a("Xuất điểm phòng thi", Yii::$app->urlManager->createUrl(['/mark/view-export']), ['class' => 'btn btn-success']) ?>
                </p>

                <div style="margin: 25px 0 25px 0">
                    <?= $this->render('_search', [
                        'model' => $model,
                        'dataExams' => $dataExams,
                        'dataRooms' => $dataRooms
                    ]) ?>
                </div>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'name',
                        'semester',
                        'start_date',
                        'status',
                        // 'description',
                        // 'created_at',
                        // 'updated_at',
                        // 'created_by',
                        // 'updated_by',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>