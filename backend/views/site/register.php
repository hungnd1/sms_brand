<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 21-Jan-17
 * Time: 9:21 PM
 */
use yii\helpers\Url;

?>

<div id="bg_main">
    <div style="height:4px;"></div>
    <div id="top">
        <div id="bu_left_top"></div>
        <div id="bg_top"></div>
        <div id="bu_right_top"></div>

    </div>


    <div
        style="margin:0 auto;width:965px;clear:both;background-image:url(<?= Url::to('@web/imgs/bg_main_conten.png') ?>);background-repeat:repeat-y;">
        <table border="0" cellpadding="0" cellspacing="0" align="center" style="height: 450px;">
            <tr>
                <td style="width:706px; padding-left:9px; padding-top:4px;" valign="top">
                    <div style="width:690px;">
                        <!--Bat dau noi dung-->
                        <div
                            style="background-image:url(<?= Url::to('@web/imgs/bg_tit_tin.jpg') ?>);    background-repeat:repeat-x; width:690px; height:25px; float:left;margin-bottom: 20px;"
                            class="tit_text">Đăng ký</div>

                        <div id="text_content">
                            <form id="subscribe_form">
                                <table>
                                    <tr>
                                        <td><label style="margin-left: 20px;margin-right: 20px;">Tên đăng nhập: </label></td>
                                        <td><input id="username_re" type="text" name="username_re" placeholder="Tên đăng nhập *" required="required"><br></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label style="margin-left: 20px;margin-right: 20px;">Mật khẩu: </label>
                                        </td>
                                        <td>
                                            <input id="password_re" type="password" name="password_re" placeholder="Mật khẩu *" required="required"><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label style="margin-left: 20px;margin-right: 20px;">Email: </label>
                                        </td>
                                        <td>
                                            <input id="email_re" type="email" name="email_re" placeholder="Email *" required="required"><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label style="margin-left: 20px;margin-right: 20px;">Số điện thoại: </label>
                                        </td>
                                        <td>
                                            <input id="phone_re" type="text" name="phone_re" placeholder="Số điện thoại *" required="required"><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button style="margin-left: 20px;margin-right: 20px;" id="subscribe_submit" onclick="register();" name="Đăng ký" type="button" >Đăng ký</button></td>
                                    </tr>


                                </table>
                            </form>
                            <!--Ket thuc noi dung-->
                            <br>
                        </div>
                <td width="5"></td>
                <td valign="top" style="padding-top: 4px;">

                    <div class="cl2">
                        <div id="tit_login" class="tit_text">S&#7917; d&#7909;ng d&#7883;ch v&#7909;</div>
                        <div id="conten_tit_login">
                            <form id="a" name="a" method="post" action="/sms/main">
                                <INPUT type="hidden" name="1yyezksvrgzkelork" value="rgu{z5iihy5zvrerumot">
                                <div id="text_user">Username</div>
                                <div id="user_com"><INPUT type="text" class="text_f"
                                                          onkeydown="TextBox_OnKeyDown(this,false)" id="username"
                                                          name="username"></div>
                                <div id="text_pass">Password</div>
                                <div id="user_com"><INPUT type="password" class="text_f" id="passWord" name="password">
                                </div>
                                <div id="btn_login"><a href="javascript: login_click();"><img title="Sign In"
                                                                                              type="image" alt="Sign In"
                                                                                              src="imgs/btn_login.jpg"
                                                                                              width="73" height="17"
                                                                                              border="0"/></a></div>
                            </form>
                            <!--p class="w3-center"><button onclick="location.href='/sms/login.jsp';" class="w3-btn w3-round-xlarge w3-hover-pink w3-green w3-border w3-border-red">&#272;&#259;ng nh&#7853;p</button></p-->
                            <div id="img5"><img src="imgs/img5.jpg"/></div>
                            <div id="bg_bottom_login"></div>

                            <div id="line"></div>
                        </div>
                    </div>

                </td>
            </tr>
        </table>
    </div>

    <div id="bottom">
        <div id="bu_left_bottom"></div>
        <div id="bg_bottom"></div>
        <div id="bu_right_bottom"></div>
    </div>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script type="text/javascript">

    function register(){
        var email = $('#email_re').val();
        var username = $('#username_re').val();
        var password = $('#password_re').val();
        var phone = $('#phone_re').val();
        if(username == ''){
            alert('Tên đăng nhập không được để trống');
            return;
        }
        if(password == ''){
            alert('Mật khẩu không được để trống');
            return;
        }
        if(email == ''){
            alert('Email không được để trống');
            return;
        }

        if(phone == ''){
            alert('Số điện thoại không được để trống');
            return;
        }
        if(IsEmail(email.trim())==false){
            alert('Email không đúng định dạng');
            return;
        }
        if(validatePhone(phone.trim())==false){
            alert('Số điện thoại đúng định dạng');
            return;
        }

        $.ajax({
            type: "POST",
            url: '<?= Url::toRoute(["site/register"]) ?>',
            data: {
                email:email,
                phone:phone,
                username:username,
                password:password
            },
            success: function(data) {
                var rs = JSON.parse(data);
                if (rs['success']) {
                    alert(rs['message']);
                    location.href = '<?= Url::toRoute(['site/login']) ?>';
                }else {
                    alert(rs['message']);
                    location.reload();
                }
            }
        });
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
            return false;
        }else{
            return true;
        }
    }

    function validatePhone(txtPhone) {
        var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
        if (!filter.test(txtPhone)) {
            return false;
        } else {
            return true;
        }
    }
</script>