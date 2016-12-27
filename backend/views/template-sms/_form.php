<?php

use common\helpers\TBApplication;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TemplateSms */
/* @var $form yii\widgets\ActiveForm */
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

    <?= $form->field($model, 'template_name')->textInput(['maxlength' => 250, 'class' => 'input-circle']) ?>
    <br><br>
    <div class="row">
        <div class="form-group field-content-price" style="padding-left: 27%;font-size: 15px;">
            <p>Thêm nội dung tin nhắn:</p>

            <table>
                <tr>
                    <?php
                    $i = 0;
                    foreach (TBApplication::listVariableContact() as $data) {
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
                bên trên.<br>ví dụ: Tên = "Nguyễn văn thông", Số diện thoại: "0987654", ...</p>
            Số ký tự : <span id="lblcount" class="safe" style="color: green; font-weight: bold">0</span> ( Số tin : <span id="counter" class="safe" style="color: green; font-weight: bold">0</span>)
        </div>
    </div>
    <br>
    <?= $form->field($model, 'template_content',  ['options' => ['class' => 'col-xs-12',
        'onkeyup' => "countChar();"]])->textarea(['rows' => 6]) ?>





    <?= $form->field($model, 'status')->dropDownList(
        \common\models\TemplateSms::getListStatus(), ['class' => 'input-circle']
    ) ?>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton($model->isNewRecord ? 'Tạo tin nhắn mẫu' : 'Cập nhật',
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <script>
        function insertEmoticonAtTextareaCursor(ID,text) {
            ID="insertPattern";
            var message = $('textarea#templatesms-template_content').val();
            var input = document.getElementById('templatesms-template_content'); // or $('#myinput')[0]
            var strPos = 0;
            var br = ((input.selectionStart || input.selectionStart == '0') ?
                "ff" : (document.selection ? "ie" : false ) );
            if (br == "ie") {
                input.focus();
                var range = document.selection.createRange();
                range.moveStart ('character', -input.value.length);
                strPos = range.text.length;
            }
            else if (br == "ff") strPos = input.selectionStart;
            var front = (input.value).substring(0,strPos);
            var back = (input.value).substring(strPos,input.value.length);
            input.value=front+text+back;
            strPos = strPos + text.length;
            if (br == "ie") {
                input.focus();
                var range = document.selection.createRange();
                range.moveStart ('character', -input.value.length);
                range.moveStart ('character', strPos);
                range.moveEnd ('character', 0);
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
                len = $('#templatesms-template_content').val().length,
                lbl = $('#lblcount');
            var ch=0;
            if(min <0) {
                lbl.text(0);
            } else {
                ch = min + len;
                lbl.text(ch);
            }
            var sotin=0;
            if(ch==0)
                sotin=0;
            else
                sotin = parseInt(ch)/160+1;
            $('#counter').text(Math.floor(sotin));
        }

    </script>
