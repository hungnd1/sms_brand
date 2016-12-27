<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\auth\filters\Yii2Auth;
use common\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý người dùng';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"><?= $this->title?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p><?= Html::a('Tạo người dùng', ['create'], ['class' => 'btn btn-success']) ?>
                <?= Html::button('Gửi thông tin', ['class' => 'btn btn-success','onclick'=>'move();']) ?>
                <?= Html::button('Cấu hình gửi tin', ['class' => 'btn btn-success','onclick'=>'config();']) ?> </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'attribute' => 'username',
                'format' => 'raw',
                'width'=>'10%',
//                'vAlign' => 'middle',
                'value' => function ($model, $key, $index, $widget) {
                    /**
                     * @var $model \common\models\User
                     */
                    $action = "user/view";
                    $res = Html::a('<kbd>'.$model->username.'</kbd>', [$action, 'id' => $model->id ]);
                    return $res;

                },
            ],
            [
                'attribute' => 'phone_number',
                'width'=>'15%',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'level',
                'format'=>'raw',
                'width'=>'15%',
                'value' => function ($model, $key, $index, $widget) {
                    /**
                     * @var $model \common\models\User
                     */
                    if($model->level == User::USER_LEVEL_ADMIN){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }elseif($model->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }elseif($model->level == User::USER_LEVEL_TKKHACHHANG_DAILY){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }elseif($model->level == User::USER_LEVEL_TKDAILYADMIN){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }elseif($model->level == User::USER_LEVEL_TKDAILYCAPDUOI){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }elseif($model->level == User::USER_LEVEL_TKTHANHVIEN_KHACHHANGDAILY){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }elseif($model->level == User::USER_LEVEL_TKTHANHVIEN_KHADMIN){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }elseif($model->level == User::USER_LEVEL_TKKHACHHANGADMIN){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }elseif($model->level == User::USER_LEVEL_TKTHANHVIEN_KHAHHANGDAILYCAPDUOI){
                        return '<span class="label label-success">'.$model->getTypeNameRole().'</span>';
                    }

                },
                'filter' => User::all_role_level(Yii::$app->user->identity->level),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => "Tất cả"],
            ],
            [
                'attribute' => 'fullname',
                'width'=>'10%',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'is_send',
                'width'=>'10%',
                'value' => function($model){
                    /**
                     * @var $model \common\models\User
                     */
                    if($model->is_send == User::IS_SEND_OK){
                        return 'Cho phép gửi tin';
                    }else{
                        return 'Không cho phép gửi tin';
                    }
                },
                'filter' => User::listIsSend(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => "Tất cả"],

            ],
            [
                'attribute' => 'number_sms',
                'width'=>'10%',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute'=>'type_kh',
                'label'=>'Loại khách hàng',
//                'width'=>'180px',
                'width'=>'10%',
                'format'=>'raw',
                'value' => function ($model, $key, $index, $widget) {
                    /**
                     * @var $model \common\models\User
                     */
                    if($model->type_kh == User::TYPE_KH_DOANHNGHIEP){
                        return '<span class="label label-success">'.$model->getTypeNameKh().'</span>';
                    }else{
                        return '<span class="label label-danger">'.$model->getTypeNameKh().'</span>';
                    }

                },
                'filter' => User::listTypeKH(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => "Tất cả"],
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute'=>'status',
                'label'=>'Trạng thái',
//                'width'=>'180px',
                'width'=>'20%',
                'format'=>'raw',
                'value' => function ($model, $key, $index, $widget) {
                    /**
                     * @var $model \common\models\User
                     */
                    if($model->status == User::STATUS_ACTIVE){
                        return '<span class="label label-success">'.$model->getStatusName().'</span>';
                    }else{
                        return '<span class="label label-danger">'.$model->getStatusName().'</span>';
                    }

                },
                'filter' => User::listStatus(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => "Tất cả"],
            ],
            // 'created_at',
            // 'updated_at',
            // 'type',
            // 'site_id',
            // 'content_provider_id',
            // 'parent_id',

            ['class' => 'kartik\grid\ActionColumn',
                'template'=>'{view}{update}{delete}',
                'buttons'=>[
                    'view' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['user/view','id'=>$model->id]), [
                            'title' => 'Thông tin user',
                        ]);

                    },
                    'update' => function ($url,$model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['user/update','id'=>$model->id]), [
                            'title' => 'Cập nhật thông tin user',
                        ]);
                    },
                    'delete' => function ($url,$model) {
//                        Nếu là chính nó thì không cho thay đổi trạng thái
                        if($model->id != Yii::$app->user->getId()){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['user/delete','id'=>$model->id]), [
                                'title' => 'Xóa user',
                                'data-confirm' => Yii::t('app', 'Xóa người dùng này?')
                            ]);
                        }
                    }
                ]
            ],
        ],
    ]); ?>

            </div>
        </div>
    </div>
</div>

<script>
    function move(){
        var cboxes = document.getElementsByName('selection[]');
        var len = cboxes.length;
        var arr = [];
        for (var i=0; i<len; i++) {
            if(cboxes[i].checked) {
                arr.push(cboxes[i].value);
            }
        }
        if(arr.length == 0){
            alert('Bạn chưa chọn tài khoản nào');
            return;
        }else{
            $.ajax({
                type:'POST',
                url:'<?= Url::toRoute(['user/send']) ?>',
                beforeSend: function(){
                    //code;
                },
                data:{arr_member:arr},
                success: function(data){
                    var responseJSON = jQuery.parseJSON(data);
                    if(responseJSON.status=="ok"){
                        alert('Gửi tin nhắn thành công.');
                        location.reload();
                    } else{
                        alert('Gửi tin nhắn thất bại');
                        location.reload();
                    }
                }
            });
        }
        console.log(arr);


    }

    function config(){
        var cboxes = document.getElementsByName('selection[]');
        var len = cboxes.length;
        var arr = [];
        for (var i=0; i<len; i++) {
            if(cboxes[i].checked) {
                arr.push(cboxes[i].value);
            }
        }
        if(arr.length == 0){
            alert('Bạn chưa chọn tài khoản nào');
            return;
        }else{
            $.ajax({
                type:'POST',
                url:'<?= Url::toRoute(['user/config']) ?>',
                beforeSend: function(){
                    //code;
                },
                data:{arr_member:arr},
                success: function(data){
                    var responseJSON = jQuery.parseJSON(data);
                    if(responseJSON.status=="ok"){
                        alert('Cấu hình thành công');
                        location.reload();
                    }
                    else{
                        alert('Chỉ được phép cấu hình tài khoản khách hàng hoặc tài khoản thành viên');
                        location.reload();
                    }
                }
            });
        }
        console.log(arr);


    }
</script>