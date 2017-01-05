<?php

use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContactDetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-detail-search">

    <?php $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'options' => ['enctype' => 'multipart/form-data'],
        'action' => ['comment-month'],
        'fullSpan' => 8,
        'method' => 'post',
    ]); ?>

    <div class="form-body">
        <table style="margin-left: 25%;">

            <tr>
                <td style="padding-right: 10%;width: 300px;">
                    <?php
                    echo $form->field($model, 'fromdate')->widget(\kartik\date\DatePicker::className(), [
                        'options' => ['placeholder' => 'Tháng'],
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'format' => 'M/yyyy',
                            'todayHighlight' => true,
                            'width' => '200px'
                        ]
                    ])->label('Ngày');
                    ?>
                </td>
                <td style="padding-right: 10%;width: 400px;">
                    <?php
                    echo $form->field($model, 'contact_id')->widget(Select2::className(), [
                        'data' => \yii\helpers\ArrayHelper::map(\common\models\Contact::getListContact(), 'id', 'contact_name'),
                        'options' => ['placeholder' => 'Chọn lớp học'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Theo lớp');
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->field($model, 'fullname')->textInput()->label('Tìm theo tên');
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Html::submitButton('Tìm kiếm', ['class' => 'btn btn-primary']) ?>
                </td>
            </tr>
        </table>


    </div>

    <?php ActiveForm::end(); ?>

</div>
