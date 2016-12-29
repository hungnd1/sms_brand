<?php

/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 29-Dec-16
 * Time: 11:06 AM
 */
use common\helpers\TBApplication;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 8,
    'options' => ['enctype' => 'multipart/form-data'],
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ],
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]); ?>
<div class="form-body">

    <?= $form->field($model, 'template_id')->dropDownList(ArrayHelper::map(\common\models\TemplateSms::findAll(['status' => \common\models\TemplateSms::STATUS_ACTIVE]), 'id', 'template_content')
        , array('prompt' => 'Chọn template mẫu','onchange' => 'insertContent();')) ?>


    <br><br>
    <div class="row">
        <div class="form-group field-content-price" style="padding-left: 27%;font-size: 15px;">
            <p>Thêm nội dung tin nhắn:</p>

            <table>
                <tr>
                    <?php
                    $i = 0;
                    foreach (TBApplication::listVariableUser() as $data) {
                        $i++;
                        ?>
                        <td style="padding-bottom: 20px;"><input class="btn"
                                                                 style="margin-right: 30px;border-radius: 12px;padding: 0 15px;"
                                                                 type="button" id=""
                                                                 value="<?php echo $data['description']; ?>"
                                                                 onclick="insertEmoticonAtTextareaCursor('insertPattern','<?php echo $data['name'] ?>')"/>
                        </td>
                        <?php
                        if ($i % 5 == 0)
                            echo '</tr><tr>';
                    }
                    ?>
                </tr>
            </table>
            <p style="color: #666666;font-size: 13px;">Hệ thống sẽ hiện thị thông tin của 1 người tương ứng với giá trị
                bên trên.<br>ví dụ: Username = "hungnd1", Số diện thoại: "0987654", ...</p>
            Số ký tự : <span id="lblcount" class="safe" style="color: green; font-weight: bold">0</span> ( Số tin :
            <span id="counter" class="safe" style="color: green; font-weight: bold">0</span>)
        </div>
    </div>
    <br>
    <div class="row">
        <?= $form->field($model, 'content', ['options' => [
            'onkeyup' => "countChar();"]])->textarea(['rows' => 6]) ?>

    </div>
    <br>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::button('Gửi tin', ['onclick'=>'move();', 'class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <br>

    <?php ActiveForm::end(); ?>

