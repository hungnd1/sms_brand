<?php

use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContactDetailSearch */
/* @var $form yii\widgets\ActiveForm */
$dataCreated_by = ArrayHelper::map(\common\models\User::find()->andWhere(['status'=>\common\models\User::STATUS_ACTIVE])->all(),'id','username');
?>

<div class="contact-detail-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'options' => ['enctype' => 'multipart/form-data'],
        'action' => ['search'],
        'fullSpan' => 8,
        'method' => 'post',
    ]); ?>

    <div class="form-body" >
        <table style="margin-left: 25%;">
            <tr>
                <td style="padding-right: 10%;">

                <?= $form->field($model,'created_by')->widget(Select2::classname(), [
                        'data' => $dataCreated_by,
                        'options' => ['placeholder' => 'Thành viên'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '200px'
                        ],
                    ])->label('Thành viên'); ?>
                </td>
                <td style="padding-right: 10%;width: 200px;">
                    <?= $form->field($model,'searchphone')->textInput(['maxlength' => 15, 'class' => 'input-circle','width'=>'200px;'])->label('Số điện thoại'); ?>
                </td>
                <td>
                    <?= $form->field($model, 'type')->dropDownList(
                        \common\models\HistoryContact::getListTypeAll(), ['class' => 'input-circle']
                    )->label('Loại tin') ?>
                </td>
            </tr>

            <tr>
                <td style="padding-right: 10%;width: 300px;">
                    <?php
                    echo $form->field($model, 'fromdate')->widget(\kartik\date\DatePicker::className(), [
                        'options' => ['placeholder' => 'Từ ngày'],
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'format' => 'dd/M/yyyy',
                            'todayHighlight' => true,
                            'width' => '200px'
                        ]
                    ])->label('Từ ngày');
                    ?>
                </td>
                <td style="padding-right: 10%;width: 300px;">
                    <?php
                    echo $form->field($model, 'todate')->widget(\kartik\date\DatePicker::className(), [
                        'options' => ['placeholder' => 'Đến ngày'],
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'format' => 'dd/M/yyyy',
                            'todayHighlight' => true,
                            'width' => '200px'
                        ]
                    ])->label('Đến ngày');
                    ?>
                </td>
                <td>
                    <?= $form->field($model, 'status_')->dropDownList(
                        \common\models\HistoryContact::getListStatusAll(), ['class' => 'input-circle']
                    )->label('Trạng thái') ?>
                </td>
            </tr>
            <tr>
                <td>
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                </td>
            </tr>
        </table>


    </div>

    <?php ActiveForm::end(); ?>

</div>
