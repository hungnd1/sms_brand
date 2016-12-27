<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\ContactDetail */

?>
<div class="portlet-body form">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fullname',
            'phone_number',
            'address',
            'email',
            'company',
            'notes',
            [
                'attribute' => 'gender',
                'value' => $model->gender == 1 ? 'Nam':'Nữ'
            ],
            [
                'attribute' => 'birthday',
                'value' => date('d-m-Y', $model->birthday)
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
                'value'=>($model->status ==\common\models\ContactDetail::STATUS_ACTIVE)  ?
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
                <?= Html::a('Hủy thao tác', ['index','id'=>$model->contact_id], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
</div>

