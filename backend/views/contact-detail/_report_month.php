<?php

use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContactDetailSearch */
/* @var $form yii\widgets\ActiveForm */
$dataCreated_by = ArrayHelper::map(\common\models\User::find()->andWhere(['status' => \common\models\User::STATUS_ACTIVE])->all(), 'id', 'username');
?>

<div class="contact-detail-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'options' => ['enctype' => 'multipart/form-data'],
        'action' => ['report-month'],
        'fullSpan' => 8,
        'method' => 'post',
    ]); ?>

    <div class="form-body">
        <table style="margin-left: 25%;">
            <tr>
                <td style="padding-right: 10%;">

                    <?= $form->field($model, 'created_by')->widget(Select2::classname(), [
                        'data' => $dataCreated_by,
                        'options' => ['placeholder' => 'Thành viên'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'width' => '200px'
                        ],
                    ])->label('Thành viên'); ?>
                </td>

                <td style="padding-right: 10%;width: 200px;">
                    <?php
                    echo $form->field($model, 'brandname_id')->widget(Select2::className(), [
                        'data' => \yii\helpers\ArrayHelper::map(\common\models\Brandname::findAll(['status' => \common\models\Brandname::STATUS_ACTIVE]), 'id', 'brandname'),
                        'options' => ['placeholder' => 'Chọn brandname'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Brandname');
                    ?>
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
                    echo $form->field($model, 'month')->widget(\kartik\date\DatePicker::className(), [
                        'options' => ['placeholder' => 'Tháng'],
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'format' => 'M/yyyy',
                            'todayHighlight' => true,
                            'width' => '200px'
                        ]
                    ])->label('Tháng');
                    ?>
                </td>
                <td>
                    <?= $form->field($model, 'status_')->dropDownList(
                        \common\models\HistoryContact::getListStatusAll(), ['class' => 'input-circle']
                    )->label('Trạng thái') ?>
                </td>
            </tr>
            <tr>
                <td style="padding-right: 10%;width: 300px;">
                    <?= $form->field($model, 'network')->checkboxList(ArrayHelper::map(\common\models\Network::findAll(['status' => \common\models\Network::STATUS_ACTIVE]), 'id', 'name'))->label('Nhà mạng') ?>
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
