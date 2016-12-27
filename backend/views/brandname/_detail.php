<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Brandname */

?>
<div class="portlet-body form">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'brandname',
            'brand_username',
            'number_sms',
            'price_sms',
            [
                'label' => $model->getAttributeLabel('price_total'),
                'attribute' => 'price_total',
                'value' => \common\models\Brandname::formatNumber($model->price_sms * $model->number_sms). ' VNĐ'
            ],
            [
                'attribute' => 'expired_at',
                'value' => date('d-m-Y H:i:s', $model->created_at)
            ],
            [
                'attribute' => 'created_at',
                'value' => date('d-m-Y H:i:s', $model->created_at)
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d-m-Y H:i:s', $model->updated_at)
            ],
            [
                'attribute'=>'status',
                'label'=>'Trạng thái',
                'format'=>'raw',
                'value'=>($model->status ==\common\models\Brandname::STATUS_ACTIVE)  ?
                    '<span class="label label-success">'.$model->getStatusName().'</span>' :
                    '<span class="label label-danger">'.$model->getStatusName().'</span>',
                'type'=>DetailView::INPUT_SWITCH,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Active',
                        'offText' => 'Delete',
                    ]
                ]
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

