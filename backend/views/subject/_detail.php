<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Subject */

?>
<div class="portlet-body form">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'name',
                'label' => 'Tên',
                'format' => 'html',
                'value' =>  $model->name,
            ],
            [
                'attribute' => 'code',
                'format' => 'html',
                'value' =>  $model->code,
            ],
            [
                'attribute' => 'description',
                'format' => 'html',
                'value' =>  $model->description,
            ],
            [
                'attribute'=>'created_at',
                'label' => 'Ngày tạo',
                'value' => date('d/m/Y H:i:s',$model->created_at),
            ],
            [                      // the owner name of the model
                'attribute'=>'updated_at',
                'label' => 'Ngày cập nhập',
                'value' => date('d/m/Y H:i:s',$model->updated_at),
            ],

        ],
    ]) ?>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Hủy thao tác', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
</div>

