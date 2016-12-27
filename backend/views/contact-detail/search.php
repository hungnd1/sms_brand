<?php

use common\models\HistoryContact;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContactDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tìm kiếm';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">Tìm kiếm</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">

                <div style="margin: 25px 0 25px 0">
                    <?= $this->render('_search', [
                        'model' => $model,
                    ]) ?>
                </div>
                <br><br>
                <br><br>
                <?php
                $gridColumns = [
                    [
                        'format' => 'raw',
                        'width' => '15%',
                        'label' => 'Số điện thoại',
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'contact_id',
                        'value' => function ($model, $key, $index, $widget) {
                            /** @var $model \common\models\HistoryContactAsm */
                            return \common\models\ContactDetail::findOne(['id' => $model->contact_id])->phone_number;
                        },
                    ],
                    [
                        'format' => 'raw',
                        'width' => '15%',
                        'class' => '\kartik\grid\DataColumn',
                        'label' => 'Nội dung tin nhắn',
                        'attribute' => 'content',
                        'value' => function ($model, $key, $index, $widget) {
                            /** @var $model \common\models\HistoryContactAsm */
                            return substr(HistoryContact::getTemplateContact(\common\models\HistoryContact::find()
                                ->innerJoin('history_contact_asm', 'history_contact_asm.history_contact_id = history_contact.id')
                                ->innerJoin('contact_detail', 'contact_detail.id = history_contact_asm.contact_id')
                                ->andWhere(['history_contact_asm.id' => $model->id])
                                ->one()->content, $model->contact_id), 0, 60);

                        },
                    ],
                    [
                        'format' => 'raw',
                        'width' => '15%',
                        'class' => '\kartik\grid\DataColumn',
                        'label' => 'Số tin',
                        'attribute' => 'content_number',
                        'value' => function ($model, $key, $index, $widget) {
                            /** @var $model \common\models\HistoryContactAsm */
                            return $model->content_number;

                        },
                    ],
                    [
                        'format' => 'raw',
                        'width' => '15%',
                        'class' => '\kartik\grid\DataColumn',
                        'label' => 'Trạng thái',
                        'attribute' => 'history_contact_status',
                        'value' => function ($model, $key, $index, $widget) {
                            /** @var $model \common\models\HistoryContactAsm */
                            return $model->getStatusName();

                        },
                    ],
                    [
                        'format' => 'raw',
                        'width' => '15%',
                        'class' => '\kartik\grid\DataColumn',
                        'label' => 'Loại tin',
                        'attribute' => 'type',
                        'value' => function ($model, $key, $index, $widget) {
                            /** @var $model \common\models\HistoryContactAsm */
                            return HistoryContact::getListByType(HistoryContact::find()
                                ->innerJoin('history_contact_asm', 'history_contact_asm.history_contact_id = history_contact.id')
                                ->andWhere(['history_contact.id' => $model->history_contact_id])
                                ->one()->type);

                        },
                    ],
                    [
                        'format' => 'raw',
                        'class' => '\kartik\grid\DataColumn',
                        'width' => '15%',
                        'label' => 'Ngày gửi tin',
                        'filterType' => GridView::FILTER_DATE,
                        'attribute' => 'updated_at',
                        'value' => function ($model) {
                            return date('d-m-Y', $model->updated_at);
                        }
                    ],
                ]
                ?>

                <?php $expMenu = ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'showConfirmAlert' => false,
                    'fontAwesome' => true,
                    'showColumnSelector' => true,
                    'dropdownOptions' => [
                        'label' => 'All',
                        'class' => 'btn btn-default'
                    ],
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_EXCEL_X => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_EXCEL => [
                            'label' => 'Excel',
                        ],
                    ],
                    'target' => ExportMenu::TARGET_SELF,
                    'filename' => "Report"
                ])
                ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id' => 'grid-category-ida',
                    'responsive' => true,
                    'pjax' => true,
                    'hover' => true,
                    'columns' => $gridColumns,
                    'panel' => [
                        'type' => GridView::TYPE_DEFAULT,
                    ],
                    'toolbar' => [
                        '{export}',
                        $expMenu,
                        ['content' =>
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['search'], [
                                'data-pjax' => 0,
                                'class' => 'btn btn-default',
                                'title' => Yii::t('kvgrid', 'Reset Grid')
                            ])
                        ],
                    ],
                    'export' => [
                        'label' => "Page",
                        'fontAwesome' => true,
                        'showConfirmAlert' => false,
                        'target' => GridView::TARGET_BLANK,

                    ],
                    'exportConfig' => [
                        GridView::EXCEL => ['label' => 'Excel', 'filename' => "Report"],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function move() {
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
            success: function (data) {
                var responseJSON = jQuery.parseJSON(data);
                if (responseJSON.status == "ok") {
                    $.ajax({
                        type: 'POST',
                        url: '<?= Url::toRoute(['contact-detail/update-contact']) ?>',
                        beforeSend: function () {
                            //code;
                        },
                        data: {id: id},
                        success: function (data) {
                            var responseJSON = jQuery.parseJSON(data);
                            if (responseJSON.status == "ok") {
                                alert('Upload thành công');
                                location.reload();
                            }
                            else {
                                alert('Upload thất bại');
                                location.reload();
                            }
                        }
                    });
                } else {
                    alert('Upload thất bại');
                    location.reload();
                }
            }
        });

    }

    function sendsms() {
        var cboxes = document.getElementsByName('selection[]');
        var len = cboxes.length;
        var arr = [];
        for (var i = 0; i < len; i++) {
            if (cboxes[i].checked) {
                arr.push(cboxes[i].value);
            }
        }
        if (arr.length == 0) {
            alert('Bạn chưa chọn tài khoản nào');
            return;
        } else {
            alert('Chức năng đang đươc nâng cấp');
        }
    }

    function sharecontact() {
        var cboxes = document.getElementsByName('selection[]');
        var contact_id = document.getElementById('contactdetail-contact_id').value;
        var len = cboxes.length;
        var arr = [];
        for (var i = 0; i < len; i++) {
            if (cboxes[i].checked) {
                arr.push(cboxes[i].value);
            }
        }
        if (arr.length == 0) {
            alert('Bạn chưa chọn tài khoản nào');
            return;
        } else if (contact_id == '' || contact_id == null) {
            alert('Bạn chưa chọn danh bạ nào');
            return;
        } else {
            $.ajax({
                type: 'POST',
                url: '<?= Url::toRoute(['contact-detail/share-contact']) ?>',
                beforeSend: function () {
                    //code;
                },
                data: {arr_member: arr, contactId: contact_id},
                success: function (data) {
                    var responseJSON = jQuery.parseJSON(data);
                    if (responseJSON.status == "ok") {
                        alert('Chuyển danh bạ thành công');
                        location.reload();
                    }
                    else {
                        alert('Chuyển danh bạ không thành công');
                        location.reload();
                    }
                }
            });
        }
    }
</script>