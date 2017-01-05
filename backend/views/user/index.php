<?php

use common\models\User;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Html;
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
                    <span class="caption-subject font-green-sharp bold uppercase"><?= $this->title ?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <p><?= Html::a('Tạo người dùng', ['create'], ['class' => 'btn btn-success']) ?>
                    <?= Html::button('Gửi thông tin', ['class' => 'btn btn-success', 'onclick' => 'showPopup();']) ?>
                    <?= Html::button('Cấu hình gửi tin', ['class' => 'btn btn-success', 'onclick' => 'config();']) ?> </p>

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
                            'width' => '10%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\User
                                 */
                                $action = "user/view";
                                $res = Html::a('<kbd>' . $model->username . '</kbd>', [$action, 'id' => $model->id]);
                                return $res;

                            },
                        ],
                        [
                            'attribute' => 'phone_number',
                            'width' => '15%',
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'level',
                            'format' => 'raw',
                            'width' => '15%',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\User
                                 */
                                if ($model->level == User::USER_LEVEL_ADMIN) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
                                } elseif ($model->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
                                } elseif ($model->level == User::USER_LEVEL_TKKHACHHANG_DAILY) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
                                } elseif ($model->level == User::USER_LEVEL_TKDAILYADMIN) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
                                } elseif ($model->level == User::USER_LEVEL_TKDAILYCAPDUOI) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
                                } elseif ($model->level == User::USER_LEVEL_TKTHANHVIEN_KHACHHANGDAILY) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
                                } elseif ($model->level == User::USER_LEVEL_TKTHANHVIEN_KHADMIN) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
                                } elseif ($model->level == User::USER_LEVEL_TKKHACHHANGADMIN) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
                                } elseif ($model->level == User::USER_LEVEL_TKTHANHVIEN_KHAHHANGDAILYCAPDUOI) {
                                    return '<span class="label label-success">' . $model->getTypeNameRole() . '</span>';
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
                            'width' => '10%',
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'is_send',
                            'width' => '10%',
                            'value' => function ($model) {
                                /**
                                 * @var $model \common\models\User
                                 */
                                if ($model->is_send == User::IS_SEND_OK) {
                                    return 'Cho phép gửi tin';
                                } else {
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
                            'width' => '10%',
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'type_kh',
                            'label' => 'Loại khách hàng',
//                'width'=>'180px',
                            'width' => '10%',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\User
                                 */
                                if ($model->type_kh == User::TYPE_KH_DOANHNGHIEP) {
                                    return '<span class="label label-success">' . $model->getTypeNameKh() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getTypeNameKh() . '</span>';
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
                            'attribute' => 'status',
                            'label' => 'Trạng thái',
//                'width'=>'180px',
                            'width' => '20%',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $widget) {
                                /**
                                 * @var $model \common\models\User
                                 */
                                if ($model->status == User::STATUS_ACTIVE) {
                                    return '<span class="label label-success">' . $model->getStatusName() . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->getStatusName() . '</span>';
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
                            'template' => '{view}{update}{delete}',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['user/view', 'id' => $model->id]), [
                                        'title' => 'Thông tin user',
                                    ]);

                                },
                                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['user/update', 'id' => $model->id]), [
                                        'title' => 'Cập nhật thông tin user',
                                    ]);
                                },
                                'delete' => function ($url, $model) {
//                        Nếu là chính nó thì không cho thay đổi trạng thái
                                    if ($model->id != Yii::$app->user->getId()) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['user/delete', 'id' => $model->id]), [
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

    function move() {
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
            url: '<?= Url::toRoute(['user/send']) ?>',
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

    function config() {
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
            $.ajax({
                type: 'POST',
                url: '<?= Url::toRoute(['user/config']) ?>',
                beforeSend: function () {
                    //code;
                },
                data: {arr_member: arr},
                success: function (data) {
                    var responseJSON = jQuery.parseJSON(data);
                    if (responseJSON.status == "ok") {
                        alert('Cấu hình thành công');
                        location.reload();
                    }
                    else {
                        alert('Chỉ được phép cấu hình tài khoản khách hàng hoặc tài khoản thành viên');
                        location.reload();
                    }
                }
            });
        }
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