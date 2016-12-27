<?php

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContactDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách sinh nhật '. date('m');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Thông tin chi tiết danh bạ</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <div class="col-md-1">
                    <?= Html::button('Gửi tin', ['class' => 'btn btn-success','onclick'=>'sendsms();']) ?>
                </div>

                <br><br>
                <br><br>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-category-ida',
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
                            'width' => '15%',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'fullname',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return Html::a($model->fullname, ['/contact-detail/view', 'id' => $model->id], ['class' => 'label label-primary']);

                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'gender',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->gender == 1 ? 'Nam' : 'Nữ';
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'address',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->address;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'phone_number',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->phone_number;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'email',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->email;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'company',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return $model->company;
                            },
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'created_by',
                            'value' => function ($model, $key, $index, $widget) {
                                /** @var $model \common\models\ContactDetail */
                                return \common\models\User::findOne(['id' => $model->created_by])->username;
                            },
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'width' => '15%',
                            'label' => 'Ngày sinh',
                            'filterType' => GridView::FILTER_DATE,
                            'attribute' => 'birthday',
                            'value' => function ($model) {
                                return date('d-m-Y', $model->birthday);
                            }
                        ],
                        [
                            'class' => 'kartik\grid\DataColumn',
                            'attribute' => 'status',
                            'label' => 'Trạng thái',
                            'format' => 'html',
                            'value' => function ($model) {
                                /* @var $model \common\models\ContactDetail */
                                return $model->getStatusName();
                            },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' => [0 => 'Không hoạt động', 10 => 'Hoạt động'],
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => 'Tất cả'],
                        ],
                        ['class' => 'kartik\grid\ActionColumn',
                            'template' => '{view}{update}{delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['contact-detail/view', 'id' => $model->id]), [
                                        'title' => 'Thông tin chi tiết',
                                    ]);

                                },
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
        var file_data = $('#contactdetail-file').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            url: '<?= Url::toRoute(['contact-detail/upload']) ?>',
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=="ok"){
                    $.ajax({
                        type:'POST',
                        url:'<?= Url::toRoute(['contact-detail/update-contact']) ?>',
                        beforeSend: function(){
                            //code;
                        },
                        data:{id:id},
                        success: function(data){
                            var responseJSON = jQuery.parseJSON(data);
                            if(responseJSON.status=="ok"){
                                alert('Upload thành công');
                                location.reload();
                            }
                            else{
                                alert('Upload thất bại');
                                location.reload();
                            }
                        }
                    });
                }else{
                    alert('Upload thất bại');
                    location.reload();
                }
            }
        });

    }

    function sendsms(){
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
            alert('Chức năng đang đươc nâng cấp');
        }
    }

    function sharecontact(){
        var cboxes = document.getElementsByName('selection[]');
        var contact_id = document.getElementById('contactdetail-contact_id').value;
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
        }else if(contact_id == '' || contact_id == null){
            alert('Bạn chưa chọn danh bạ nào');
            return;
        }else{
            $.ajax({
                type:'POST',
                url:'<?= Url::toRoute(['contact-detail/share-contact']) ?>',
                beforeSend: function(){
                    //code;
                },
                data:{arr_member:arr,contactId:contact_id},
                success: function(data){
                    var responseJSON = jQuery.parseJSON(data);
                    if(responseJSON.status=="ok"){
                        alert('Chuyển danh bạ thành công');
                        location.reload();
                    }
                    else{
                        alert('Chuyển danh bạ không thành công');
                        location.reload();
                    }
                }
            });
        }
    }
</script>