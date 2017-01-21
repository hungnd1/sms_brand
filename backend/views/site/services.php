<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 20-Jan-17
 * Time: 3:57 PM
 */
use common\models\News;
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
        <table border="0" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td style="width:706px; padding-left:9px; padding-top:4px;" valign="top">
                    <div style="width:690px;">
                        <!--Bat dau noi dung-->
                        <div
                            style="background-image:url(<?= Url::to('@web/imgs/bg_tit_tin.jpg') ?>);    background-repeat:repeat-x; width:690px; height:25px; float:left;margin-bottom: 20px;"
                            class="tit_text"><?= News::getTypeName($type) ?></div>
                        <?php if ($type == News::TYPE_TINTUC) { ?>
                            <p align="justify" class="tit_text">Facebook v&#432;&#7907;t tr&#7897;i Google &#7903; th&#7883;
                                tr&#432;&#7901;ng qu&#7843;ng cáo di &#273;&#7897;ng</p>
                            <p align="left" class="text_conten"
                               style="margin-left:10px; margin-right:5px; margin-top:6px; margin-bottom:6px;">
                                <i><?= date('d/m/Y', $model->created_at) ?></i></p>
                        <?php } ?>
                        <div id="text_content">
                            <?= $model->content ?>

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
                            <div id="user_com">
                                <ul>
                                    <li><a href="<?= Url::toRoute(['site/register']) ?>">&#272;&#259;ng ký &#273;&#7841;i lý</a></li>
                                </ul>
                            </div>
                            <div id="img5"><img src="imgs/img5.jpg"/></div>
                            <div id="bg_bottom_login"></div>

                            <div id="line"></div>
                            <?= $this->render('adventisment') ?>
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

