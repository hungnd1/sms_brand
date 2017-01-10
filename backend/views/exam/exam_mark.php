<?php

use yii\helpers\Html;
use kartik\grid\GridView;

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
                    'id' => 'grid-exam-mark-id',
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
                        // SBD
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'SBD',
                            'value' => function ($model) {
                                return $model->identification;
                            },
                        ],
                        // Students
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Tên thí sinh',
                            'value' => function ($model) {
                                return $model->student_name;
                            },
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>