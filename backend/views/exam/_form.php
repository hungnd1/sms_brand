<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Exam */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .summary {
        display: none;
    }
</style>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'action' => ['create'],
    'formConfig' => [
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]); ?>

<div class="form-body">

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

    <table style="margin-top: 20px" width="100%">
        <tr>
            <td width="20%">
                <table>
                    <tr>
                        <td>Khối</td>
                    </tr>
                </table>
            </td>
            <td style="padding-left: 20px;" width="30%">
                <?= GridView::widget([
                    'dataProvider' => $subjects,
                    'id' => 'grid-subject-id',
                    'columns' => [
                        // Checkbox
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'width' => '5%'
                        ],
                        [
                            'format' => 'raw',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'name',
                            'label' => 'Môn thi',
                            'value' => function ($model) {
                                return $model->name;
                            },
                        ],
                    ],
                ]); ?>
            </td>

            <td width="50%" style="padding-left: 20px">
                <div style="border: 1px solid">
                    <div style="border: 1px solid; background: royalblue;
                    font-size: 14px; color: white; font-weight: bold">Trộn phòng
                        thi
                    </div>
                    <div id="ctl00_MainContent_RadGrid1" class="RadGrid RadGrid_Default grid-no-border" style="height: 175px; width: 100%; max-height: 175px; border: 1px solid rgb(204, 204, 204) !important; background-position: right 0px;" tabindex="0">

                        <table cellspacing="0" class="rgMasterTable" border="0" id="ctl00_MainContent_RadGrid1_ctl00" style="width:100%;table-layout:auto;empty-cells:show;">
                            <thead>
                            <tr>
                                <th scope="col" class="rgHeader hd-none">&nbsp;</th><th scope="col" class="rgHeader hd-none">&nbsp;</th><th scope="col" class="rgHeader hd-none"> </th>
                            </tr>
                            </thead><tbody>
                            <tr class="rgNoRecords">
                                <td colspan="3" style="text-align:left;"><div>No records to display.</div></td>
                            </tr>
                            </tbody>

                        </table><input id="ctl00_MainContent_RadGrid1_ClientState" name="ctl00_MainContent_RadGrid1_ClientState" type="hidden" autocomplete="off">
                    </div>
                </div>
            </td>

        </tr>
    </table>

    <p style="font-weight: bold; font-size: 18px">Danh sách phòng thi</p>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9" style="margin-left: 80%;">
                <?= Html::submitButton('Tạo mới', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>