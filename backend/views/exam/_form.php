<?php

use common\assets\ToastAsset;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Exam */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

ToastAsset::register($this);
ToastAsset::config($this, [
    'positionClass' => ToastAsset::POSITION_TOP_RIGHT
]);

$tableSubjectId = "tableSubjectId";
$tableClassId = "tableClassId";
$createQueueRooms = \yii\helpers\Url::to(['exam/create-queue-rooms']);
$deleteQueueRooms = \yii\helpers\Url::to(['exam/delete-queue-rooms']);

$js = <<<JS

    // show detail student
    function showExamStudentRoom(id) {
        $.ajax({
            url: '?r=exam%2Fshow-exam-student-room&id=' + id,
            success: function(data) {
                $('#myModalContent').html(data);
            }
        });
        $('#popupClass').modal('show');
    }

    // show config sbd popup
    function showConfigSBD() {
        $('#popupSBD').modal('show');
    }
    
    // delte queue room
    function  deleteQueueRoom(exam_room_id) {
         jQuery.post(
                '{$deleteQueueRooms}',
                {exam_room_id:exam_room_id}
            )
            .done(function(result) {
                $("#grid-subjects-id").html(result);
                var div_room_new = $("#div_rooms_new").html();
                $("#div_rooms").html(div_room_new);
            })
            .fail(function() {
                toastr.error("server error");
        });
    }
    
    // sumbit form
    function submitForm() {
        $("#create-form-id").submit();
    }

    function createRoom(){
        subjects = $("#$tableSubjectId").yiiGridView("getSelectedRows");
        classes = $("#$tableClassId").yiiGridView("getSelectedRows");
    
        jQuery.post(
                '{$createQueueRooms}',
                {subjectIds:subjects, classIds:classes}
            )
            .done(function(result) {
                $("#grid-subjects-id").html(result);
                var div_room_new = $("#div_rooms_new").html();
                $("#div_rooms").html(div_room_new);
            })
            .fail(function() {
                toastr.error("server error");
        });
    }
JS;

$this->registerJs($js, View::POS_END);
?>

<style>
    .summary {
        display: none;
    }

    .exam-room {
        border: solid 1px #DDD;
        height: 99%;
    }

    .exam-room h5 {
        color: #FFF;
        background: #26a69a;
        line-height: 23px;
        padding: 5px;
    }

    h5 {
        margin: 0;
        font-family: "Open Sans", sans-serif;
        font-weight: 600;
        font-size: 14px;
    }

    .div_rooms {
        height: 200px;
        width: 95%;
        margin: 0 0 15px 2.5%;
        border: 1px solid rgb(204, 204, 204) !important;
    }

</style>

<div class="form-body">

    <?php $form = ActiveForm::begin([
        'id' => 'create-form-id',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'action' => ['create'],
        'formConfig' => [
            'deviceSize' => ActiveForm::SIZE_SMALL,
        ]
    ]); ?>

    <table style="margin-top: 20px">
        <tr>
            <td style="padding-right: 10px;">Tên kỳ thi</td>
            <td>
                <?= $form->field($model, 'name')->textInput(['style' => 'width: 150px'])->label(false) ?>
            </td>
            <td style="padding-right: 10px">Thời điểm thi</td>
            <td>
                <?=
                $form->field($model, 'semester')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => [1 => 'Học kỳ I', 2 => 'Học kỳ II'],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'width' => '150px'
                    ],
                ])->label(false);
                ?>
            </td>
            <td style="padding-right: 10px">Ngày bắt đầu</td>
            <td>
                <?=
                $form->field($model, 'start_date')->widget(\kartik\date\DatePicker::className(), [
                    'options' => ['placeholder' => 'Chọn ngày bắt đầu'],
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => 'dd-M-yyyy',
                        'todayHighlight' => true
                    ]
                ])->label(false);
                ?>
            </td>
            <td style="padding-right: 10px">Tiêu chí trộn</td>
            <td>
                <?=
                $form->field($model, 'mixing')->widget(Select2::classname(), [
                    'hideSearch' => true,
                    'data' => [1 => 'Trong khối', 2 => 'Theo lớp'],
                    'pluginOptions' => [
                        'allowClear' => false,
                        'width' => '150px'
                    ],
                ])->label(false);
                ?>
            </td>
        </tr>
    </table>
    <?php ActiveForm::end(); ?>

    <table style="margin-top: 20px" width="100%">
        <tr>
            <td width="20%" valign="top">
                <?= GridView::widget([
                    'id' => $tableClassId,
                    'dataProvider' => $classes,
                    'pjax' => true,
                    'columns' => [
                        // Checkbox
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'width' => '5%'
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Lớp',
                            'value' => function ($model) {
                                return $model->contact_name;
                            },
                        ],
                    ],
                ]); ?>
            </td>
            <td style="padding-left: 20px;" valign="top" width="20%">
                <?= GridView::widget([
                    'id' => $tableSubjectId,
                    'dataProvider' => $subjects,
                    'pjax' => true,
                    'columns' => [
                        // Checkbox
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'width' => '5%'
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'label' => 'Môn thi',
                            'value' => function ($model) {
                                return $model->name;
                            },
                        ],
                    ],
                ]); ?>
            </td>

            <td width="60%" style="padding-left: 20px">
                <div class="exam-room">
                    <h5>Trộn phòng thi</h5>
                    <div style="margin: 5px 0 5px 2.5%">
                        <?= Html::button('Tạo mới', ['class' => 'btn btn-success', 'onclick' => 'createRoom();']) ?>
                        <?= Html::button('Cấu hình SBD', ['class' => 'btn btn-success', 'onclick' => 'showConfigSBD();']) ?>
                    </div>

                    <div class="div_rooms" id="div_rooms">
                        <?= GridView::widget([
                            'dataProvider' => $queueExamRoom,
                            'pjax' => true,
                            'columns' => [
                                // STT
                                [
                                    'class' => '\kartik\grid\SerialColumn',
                                    'header' => 'STT',
                                    'width' => '5%'
                                ],
                                // Name
                                [
                                    'format' => 'raw',
                                    'class' => '\kartik\grid\DataColumn',
                                    'label' => 'DS phòng thi',
                                    'value' => function ($model) {
                                        return $model->name;
                                    }
                                ],
                                // Students
                                [
                                    'format' => 'raw',
                                    'class' => '\kartik\grid\DataColumn',
                                    'label' => 'Số thí sinh',
                                    'value' => function ($model) {
                                        return $model->number_student;
                                    }
                                ],
                                [
                                    'class' => 'kartik\grid\ActionColumn',
                                    'template' => '{delete}',
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <p style="font-weight: bold; font-size: 18px">Danh sách phòng thi</p>
    <td style="padding-left: 20px;" width="30%">
        <?= GridView::widget([
            'dataProvider' => $queueDetailExamRoom,
            'id' => 'grid-subjects-id',
            'pjax' => true,
            'columns' => [
                // STT
                [
                    'class' => '\kartik\grid\SerialColumn',
                    'header' => 'STT',
                    'width' => '5%'
                ],
                // Name
                [
                    'format' => 'raw',
                    'class' => '\kartik\grid\DataColumn',
                    'label' => 'DS phòng thi',
                    'value' => function ($model) {
                        return $model->room_name;
                    },
                    'group' => true,
                ],
                // Students
                [
                    'format' => 'raw',
                    'class' => '\kartik\grid\DataColumn',
                    'label' => 'Học sinh',
                    'value' => function ($model) {
                        $rs = $model->room_student .
                            ' <a href="javascript: showExamStudentRoom(' . $model->exam_room_id . ');" style="text-decoration: none; color: blue">(Chi tiết)</a>';
                        return $rs;
                    },
                    'group' => true,
                    'subGroupOf' => 1
                ],
                // Subjects
                [
                    'format' => 'raw',
                    'class' => '\kartik\grid\DataColumn',
                    'label' => 'Môn thi',
                    'value' => function ($model) {
                        return $model->subject_name;
                    },
                ],
                // Locations
                [
                    'format' => 'raw',
                    'class' => '\kartik\grid\DataColumn',
                    'label' => 'Địa điểm',
                    'value' => function ($model) {
                        return $model->location;
                    },
                ],
                // Supervisory
                [
                    'format' => 'raw',
                    'class' => '\kartik\grid\DataColumn',
                    'label' => 'Giáo viên coi thi',
                    'value' => function ($model) {
                        return $model->supervisory;
                    },
                ],
                // Exam hours
                [
                    'format' => 'raw',
                    'class' => '\kartik\grid\DataColumn',
                    'label' => 'Giờ thi',
                    'value' => function ($model) {
                        return $model->exam_hour;
                    },
                ],
                // Exam dates
                [
                    'format' => 'raw',
                    'class' => '\kartik\grid\DataColumn',
                    'label' => 'Ngày thi',
                    'value' => function ($model) {
                        return $model->exam_date;
                    },
                ],
            ],
        ]); ?>
    </td>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9" style="margin-left: 80%;">
                <?= Html::button('Tạo mới', ['class' => 'btn btn-success', 'onclick' => 'submitForm();']) ?>
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php
    Modal::begin([
        'header' => '<p style="font-weight: bold; font-size: 18px; text-align: center">Cấu hình Số Báo Danh</p>',
        'options' => [
            'id' => 'popupSBD',
            'tabindex' => false // important for Select2 to work properly
        ],
    ]);
    echo $this->render('_form_sbd', [
        'model' => $identificationNumber
    ]);
    Modal::end();
    ?>

    <?php
    Modal::begin([
        'header' => '<p style="font-weight: bold; font-size: 20px; margin-left: 10px">Danh sách chi tiết</p>',
        'id' => 'popupClass',
        'closeButton' => [
            'label' => 'Close',
            'class' => 'btn btn-danger btn-sm pull-right',
        ],
    ]);
    echo "<div id='myModalContent'></div>";
    Modal::end();
    ?>
</div>