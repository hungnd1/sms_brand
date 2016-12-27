<?php

use common\models\User;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
function save_close(){
    jQuery('#close').val(1);
    return false;
}
JS;
$this->registerJs($js, View::POS_END);

?>


<?php $form = ActiveForm::begin([
    'method' => 'post',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'fullSpan' => 12,
    'formConfig' => [
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'showLabels' => true,
        'labelSpan' => 2,
        'deviceSize' => ActiveForm::SIZE_SMALL,
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]);
$formId = $form->id;
?>
<div class="form-body">
    <input type="hidden" name="close" id="close" value="0">
    <?php if ($model->isNewRecord) { ?>
        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Tài khoản', 'maxlength' => 20]) ?>
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'fullname')->textInput(['placeholder' => 'Họ tên', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Nhập mật khẩu có độ dài  tối thiểu 8 kí tự']) ?>
        <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => 'Nhập lại mật khẩu']) ?>
        <?= $form->field($model, 'address')->textInput(['placeholder' => 'Địa chỉ', 'maxlength' => '200']) ?>
        <?= $form->field($model, 'phone_number')->textInput(['placeholder' => 'Số điện thoại', 'maxlength' => '12']) ?>
        <?= $form->field($model, 'status')->dropDownList(User::listStatus()) ?>
        <?= $form->field($model, 'type_kh')->dropDownList(User::listTypeKH()) ?>
        <?php
        if (Yii::$app->user->identity->level == User::USER_LEVEL_ADMIN || Yii::$app->user->identity->level == User::USER_LEVEL_TKDAILYADMIN) {
            echo $form->field($model, 'brandname_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\common\models\User::getBrandname(), 'id', 'brandname'),
                'options' => ['placeholder' => 'Brandname'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Brandname');
        }
        ?>
        <?php
        if (Yii::$app->user->identity->level == User::USER_LEVEL_ADMIN) { ?>
            <?= $form->field($model, 'level')->dropDownList(User::user_role_admin(), ['class' => 'input-circle']) ?>
        <?php } else if (Yii::$app->user->identity->level == User::USER_LEVEL_TKDAILYADMIN) {
            echo $form->field($model, 'level')->dropDownList(User::user_role_daily(), ['class' => 'input-circle']);
        } else if (Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANGADMIN) {
            echo $form->field($model, 'level')->dropDownList(User::user_role_khachhangadmin(), ['class' => 'input-circle']);
        } else if (Yii::$app->user->identity->level == User::USER_LEVEL_TKDAILYCAPDUOI) {
            echo $form->field($model, 'level')->dropDownList(User::user_role_dailycapduoi(), ['class' => 'input-circle']);
        } else if (Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI) {
            echo $form->field($model, 'level')->dropDownList(User::user_role_khachhangdaily(), ['class' => 'input-circle']);
        } else if (Yii::$app->user->identity->level == User::USER_LEVEL_TKKHACHHANG_DAILY) {
            echo $form->field($model, 'level')->dropDownList(User::user_role_khachhangdaily(), ['class' => 'input-circle']);
        }
        ?>

    <?php } else { ?>
        <?= $form->field($model, 'username')->textInput(['readonly' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'maxlength' => 100]) ?>
        <?= $form->field($model, 'address')->textInput(['placeholder' => 'Địa chỉ', 'maxlength' => '200']) ?>
        <?= $form->field($model, 'phone_number')->textInput(['placeholder' => 'Số điện thoại', 'maxlength' => '11']) ?>
        <?php if ($model->level == User::USER_LEVEL_TKKHACHHANGADMIN || $model->level == User::USER_LEVEL_TKKHACHHANG_DAILY
            || $model->level == User::USER_LEVEL_TKKHACHHANG_DAILYCAPDUOI || $model->level == User::USER_LEVEL_TKTHANHVIEN_KHACHHANGDAILY
            || $model->level == User::USER_LEVEL_TKTHANHVIEN_KHADMIN || $model->level == User::USER_LEVEL_TKTHANHVIEN_KHAHHANGDAILYCAPDUOI
        ) { ?>
            <?= $form->field($model, 'number_sms')->textInput(['placeholder' => 'Số tin nhắn tối đa', 'maxlength' => '11']) ?>
        <?php } ?>
        <?= $form->field($model, 'fullname')->textInput(['placeholder' => 'Họ tên', 'maxlength' => 100]) ?>
        <?php
        if (Yii::$app->user->identity->level == User::USER_LEVEL_ADMIN || Yii::$app->user->identity->level == User::USER_LEVEL_TKDAILYADMIN) {
            echo $form->field($model, 'brandname_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\common\models\User::getBrandname(), 'id', 'brandname'),
                'options' => ['placeholder' => 'Brandname'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Brandname');
        }
        ?>
        <?= $form->field($model, 'type_kh')->dropDownList(User::listTypeKH()) ?>
        <!--        Nếu là chính nó thì không cho thay đổi trạng thái-->
        <?php if ($model->id != Yii::$app->user->getId()) { ?>
            <?= $form->field($model, 'status')->dropDownList(User::listStatus()) ?>
        <?php } ?>
    <?php } ?>
</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <!--            --><?php //Html::submitButton($model->isNewRecord ? 'Create and Close' : 'Update and Close',['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'onclick' => 'save_close()']) ?>
            <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::a('Hủy thao tác', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>


