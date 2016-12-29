<?php

use common\models\User;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContactDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách chi tiết của danh bạ ' . \common\models\Contact::findOne(['id' => $id])->contact_name;
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
                <?php $form = ActiveForm::begin([
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    'fullSpan' => 10,
                    'options' => ['enctype' => 'multipart/form-data'],

                    'enableAjaxValidation' => false,
                    'enableClientValidation' => true,
                ]); ?>
                <div class="col-md-2">
                    <?php echo Html::a("Thêm danh sách chi tiết ", Yii::$app->urlManager->createUrl(['/contact-detail/create', 'id' => $id]), ['class' => 'btn btn-success']) ?>
                </div>
                <div class="col-md-1">
                    <?php echo Html::a("Tải file mẫu  ", Yii::$app->urlManager->createUrl(['/contact/download-template']), ['class' => 'btn btn-danger']) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                        'options' => ['multiple' => true, 'accept' => '*'],
                        'pluginOptions' => [
                            'previewFileType' => 'any',
                            'showUpload' => false,
                            'showPreview' => false,
                            'browseLabel' => '',
                            'removeLabel' => '',
                            'overwriteInitial'=>true
                        ]
                    ]); ?>
                </div>
                <div class="col-md-1">
                    <?= Html::button('Tải dữ liệu', ['class' => 'btn btn-success','onclick'=>'move();']) ?>
                </div>
                <div class="col-md-1">
                    <?= Html::button('Gửi tin', ['class' => 'btn btn-success','onclick'=>'showPopup();']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model,'contact_id')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\common\models\Contact::find()
                            ->andWhere(['status'=>\common\models\Contact::STATUS_ACTIVE])
                            ->andWhere(['created_by'=>Yii::$app->user->id])
                            ->andWhere('id <> :id',[':id'=>$id])->all(), 'id', 'contact_name'),
                        'options' => ['placeholder' => 'Danh bạ'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Danh bạ'); ?>
                </div>
                <div class="col-md-1">
                    <?= Html::button('Chuyển danh bạ', ['class' => 'btn btn-success','onclick'=>'sharecontact();']) ?>
                </div>
                <?php ActiveForm::end(); ?>
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
<?php
$model = new \common\models\HistoryContact();
Modal::begin([
    'header' => '<h2 style="text-align: center;">Nhập nội dung</h2>',
    'id' => 'myModal',
    'size' => 'modal-lg',
]);

echo $this->render('_popup', [
    'model' => $model
]);
Modal::end();
?>
<script>
    function move(){
        var id = '<?= $id ?>';
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

    function showPopup() {
        var cboxes = document.getElementsByName('selection[]');
        var brandname = '<?= User::findOne(["id" => Yii::$app->user->id])->brandname_id ? 1 : 0 ?>';
        var len = cboxes.length;
        var is_send = '<?= Yii::$app->user->identity->level == User::USER_LEVEL_ADMIN || Yii::$app->user->identity->is_send == 1 ? 1 : 0  ?>';
        var arr = [];
        for (var i = 0; i < len; i++) {
            if (cboxes[i].checked) {
                arr.push(cboxes[i].value);
            }
        }
        if (!brandname) {
            alert('Bạn chưa được gán brandname nào nên không thể gửi tin');
            return;
        } else if (arr.length == 0) {
            alert('Bạn chưa chọn tài khoản nào');
            return;
        } else if (!is_send) {
            alert('Tài khoản chưa được cấu hình gửi tin');
            return;
        } else {
            $('#myModal').modal('show');
        }

//        alert($('#myModal').hasClass('in'));
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
    function sendsms() {
        var input = document.getElementById('historycontact-content').value;
        var cboxes = document.getElementsByName('selection[]');
        var len = cboxes.length;
        if (input == '') {
            alert('Bạn chưa nhập nội dung gửi');
            return;
        }
        var arr = [];
        for (var i = 0; i < len; i++) {
            if (cboxes[i].checked) {
                arr.push(cboxes[i].value);
            }
        }
        $.ajax({
            type: 'POST',
            url: '<?= Url::toRoute(['history-contact/send']) ?>',
            beforeSend: function () {
                //code;
            },
            data: {arr_member: arr, content: input},
            success: function (data) {
                var responseJSON = jQuery.parseJSON(data);
                if (responseJSON.status == "ok") {
                    alert('Gửi tin nhắn thành công.');
                    location.reload();
                } else {
                    alert('Gửi tin nhắn thất bại');
                    location.reload();
                }
            }
        });
        console.log(arr);
    }
</script>
<script>
    function insertEmoticonAtTextareaCursor(ID, text) {
        ID = "insertPattern";
        var input = document.getElementById('historycontact-content'); // or $('#myinput')[0]
        var strPos = 0;
        var br = ((input.selectionStart || input.selectionStart == '0') ?
            "ff" : (document.selection ? "ie" : false ) );
        if (br == "ie") {
            input.focus();
            var range = document.selection.createRange();
            range.moveStart('character', -input.value.length);
            strPos = range.text.length;
        }
        else if (br == "ff") strPos = input.selectionStart;
        var front = (input.value).substring(0, strPos);
        var back = (input.value).substring(strPos, input.value.length);
        input.value = front + text + back;
        strPos = strPos + text.length;
        if (br == "ie") {
            input.focus();
            var range = document.selection.createRange();
            range.moveStart('character', -input.value.length);
            range.moveStart('character', strPos);
            range.moveEnd('character', 0);
            range.select();
        }
        else if (br == "ff") {
            input.selectionStart = strPos;
            input.selectionEnd = strPos;
            input.focus();
        }
        countChar();
    }
    function countChar() {
        var min = 0,
            len = $('#historycontact-content').val().length,
            lbl = $('#lblcount');
        var ch = 0;
        if (min < 0) {
            lbl.text(0);
        } else {
            ch = min + len;
            lbl.text(ch);
        }
        var sotin = 0;
        if (ch == 0)
            sotin = 0;
        else
            sotin = parseInt(ch) / 160 + 1;
        $('#counter').text(Math.floor(sotin));
    }

    function insertContent() {
        var tem_calue = $("#historycontact-template_id option:selected").text();
        var input = document.getElementById('historycontact-content');
        if ($("#historycontact-template_id").val() != "") {
            input.value = tem_calue;
        }
        else {
            $('#historycontact_content').val('');
        }
        countChar();
    }
</script>