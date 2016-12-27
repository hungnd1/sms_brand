<?php

use common\models\User;
use kartik\detail\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

?>
<div class="portlet-body form">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute' => 'username', 'format' => 'raw', 'value' => '<kbd>' . $model->username . '</kbd>', 'displayOnly' => true],
            [
                'attribute' => 'type',
                'label' => 'Loại người dùng',
                'format' => 'html',
                'value' => $model->getTypeName(),
            ],
            [
                'attribute' => 'type_kh',
                'label' => 'Loại khách hàng',
                'format' => 'html',
                'value' => $model->getTypeNameKh(),
            ],
            [
                'label' => 'Quyền người dùng',
                'format' => 'html',
                'value' => $model->getRolesName(),
            ],
            'email:email',
            'address',
            'fullname',
            'phone_number',
            'number_sms',
            [
                'attribute' => 'type_kh',
                'format' => 'html',
                'value' => $model->getStatusNameKH(),
            ],
            [
                'attribute' => 'is_send',
                'format' => 'html',
                'value' => $model->is_send == 1 ? "Cho phép gửi tin" : "Không được gửi tin",
            ],
            [
                'attribute' => 'brandname_id',
                'format' => 'html',
                'value' => \common\models\Brandname::findOne(['id' => $model->brandname_id]) ? \common\models\Brandname::findOne(['id' => $model->brandname_id])->brandname : 'Chưa được gán brandname',
            ],
            [
                'attribute' => 'status',
                'label' => 'Trạng thái',
                'format' => 'raw',
                'value' => ($model->status == User::STATUS_ACTIVE) ?
                    '<span class="label label-success">' . $model->getStatusName() . '</span>' :
                    '<span class="label label-danger">' . $model->getStatusName() . '</span>',
                'type' => DetailView::INPUT_SWITCH,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Active',
                        'offText' => 'Delete',
                    ]
                ]
            ],
            [                      // the owner name of the model
                'attribute' => 'created_at',
                'label' => 'Ngày tham gia',
                'value' => date('d/m/Y H:i:s', $model->created_at),
            ],
            [                      // the owner name of the model
                'attribute' => 'updated_at',
                'label' => 'Ngày thay đổi thông tin',
                'value' => date('d/m/Y H:i:s', $model->updated_at),
            ],

//                        'type',
//                        'site_id',
//                        'content_provider_id',
//                        'parent_id',
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

