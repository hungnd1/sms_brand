<?php

use common\helpers\TBApplication;
use common\models\Contact;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HistoryContact */
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

    <?= $form->field($model, 'campain_name')->textInput(['maxlength' => 100, 'class' => 'input-circle']) ?>
    <?= $form->field($model, 'type')->dropDownList(\common\models\HistoryContact::getListType(), ['class' => 'input-circle']) ?>
    <?php if (\common\models\Brandname::getBrandname()) { ?>
        <?= $form->field($model, 'brandname_id')->dropDownList(\common\models\Brandname::getBrandname(), array('prompt' => 'Chọn brandname')) ?>
    <?php } else { ?>
        <?= $form->field($model, 'brandname_id')->dropDownList(ArrayHelper::map(['empty'=>'Chọn brandname'], 'id', 'value')) ?>
    <?php } ?>
    <?php
    echo $form->field($model, 'template_id')->widget(Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\common\models\TemplateSms::findAll(['status' => \common\models\TemplateSms::STATUS_ACTIVE]), 'id', 'template_content'),
        'options' => ['placeholder' => 'Chọn tin nhắn mẫu', 'onchange' => 'insertContent();'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Chọn tin nhắn mẫu');
    ?>
    <?php $model->is_send = 0;
    echo $form->field($model, 'is_send')->radioList([0 => 'Gửi luôn', 1 => 'Đặt lịch gửi'], ['inline' => true, 'onchange' => 'isSend();'])->label('Cách thức gửi') ?>
    <div id="is_send" style="display: none;">
        <?php
        echo $form->field($model, 'send_schedule')->widget(\kartik\widgets\DateTimePicker::className(), [
            'options' => ['placeholder' => 'Chọn thời gian gửi tin'],
            'convertFormat' => true,
            'pluginOptions' => [
                'format' => 'dd-M-yyyy H:i:s',
                'todayHighlight' => true
            ]
        ]);
        ?>
    </div>
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
                                                                 onclick="insertEmoticonAtTextareaCursor('<?php echo $data['name'] ?>')"/>
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
            Số ký tự : <span id="lblcount" class="safe" style="color: green; font-weight: bold">0</span> ( Số tin :
            <span id="counter" class="safe" style="color: green; font-weight: bold">0</span>)
        </div>
    </div>
    <br>
    <?= $form->field($model, 'content', ['options' => ['class' => 'col-xs-12',
        'onkeyup' => "countChar();"]])->textarea(['rows' => 6]) ?>
    <?php
    if ($type == 1) {

        $data = ArrayHelper::map(Contact::find()
            ->andWhere(['status' => Contact::STATUS_ACTIVE])
            ->andWhere(['created_by' => Yii::$app->user->id])->all(), 'id', 'contact_name');

        echo $form->field($model, 'contact_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => 'Chọn danh bạ',
                'multiple' => true],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Chọn danh bạ');
    } else { ?>
        <div class="form-body">
            <div class="row">
                <div class="col-md-offset-2 col-md-10 text-right">
                    <?= Html::a(Yii::t('app', "Tải file mẫu"), $model->getTemplateFile()) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'uploadedFile')->fileInput()->label('Dữ liệu file excel') ?>
                </div>
            </div>
        </div>
    <?php }
    ?>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <?= Html::submitButton($model->isNewRecord ? 'Gửi tin' : 'Cập nhật',
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::a('Quay lại', ['index'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

    <script type="text/javascript">
        function isSend() {
            var is_send = $('input:radio[name="HistoryContact[is_send]"]:checked').val();
            if (is_send == 0) {
                document.getElementById('is_send').style.display = 'none';
            } else {
                document.getElementById('is_send').style.display = 'block';
            }
        }

        function insertEmoticonAtTextareaCursor(text) {
            var message = $('textarea#historycontact-content').val();
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