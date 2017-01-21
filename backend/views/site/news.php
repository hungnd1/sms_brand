<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 20-Jan-17
 * Time: 3:43 PM
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
        style="margin:0 auto;width:965px;clear:both;background-image:url(imgs/bg_main_conten.png);background-repeat:repeat-y;">
        <table border="0" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td style="width:706px; padding-left:9px; padding-top:4px;" valign="top">

                    <div style="width:690px;">
                        <!--Bat dau noi dung-->
                        <div
                            style="background-image:url(imgs/bg_tit_tin.jpg);	background-repeat:repeat-x; width:690px; height:25px; float:left;"
                            class="tit_text">Tin t&#7913;c
                        </div>
                        <p align="justify" class="tit_text">&nbsp;</p>
                        <div id="text_conten">
                            <table>
                                <!--bat dau mot tit tin-->
                                <?php if (isset($model) && !empty($model)) {
                                    foreach ($model as $item) {
                                        /** @var $item \common\models\News */
                                        ?>

                                        <tr>
                                            <td align="left"><p align="justify" class="tit_text">
                                                    <a href="<?= Url::toRoute(['site/services', 'id' => $item->id, 'type' => $item->type]) ?>"
                                                       style="text-decoration:none;"><?= $item->display_name ?></a></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table align="left" border="0">
                                                    <tbody>
                                                    <tr>
                                                        <td align="left"><img src="<?= $item->getImageLink() ?>"
                                                                              border="0" width="150"></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <p class="text-conten"
                                                   align="justify"><?= $item->short_description ?></p>
                                            </td>
                                        </tr>

                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td><p style="color: red;">Không có dữ liệu</p></td>
                                    </tr>
                                <?php } ?>

                                <tr>
                                    <td><?php
                                        $pagination = new \yii\data\Pagination(['totalCount' => $pages->totalCount, 'pageSize' => 3]);
                                        echo \yii\widgets\LinkPager::widget([
                                            'pagination' => $pagination,
                                        ]);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--Ket thuc noi dung-->
                    </div>
                <td width="5"></td>
                <td valign="top" style="padding-top: 4px;">

                    <div class="cl2">
                        <div id="tit_login" class="tit_text">S&#7917; d&#7909;ng d&#7883;ch v&#7909;</div>
                        <div id="conten_tit_login">
                            <form id="a" name="a" method="post" action="">
                                <INPUT type="hidden" name="1yyezksvrgzkelork" value="rgu{z5iihy5zvrerumot">
                                <div id="text_user">Username</div>
                                <div id="user_com"><INPUT type="text" class="text_f"
                                                          onkeydown="TextBox_OnKeyDown(this,false)" id="username"
                                                          name="username"></div>
                                <div id="text_pass">Password</div>
                                <div id="user_com"><INPUT type="password" class="text_f" id="passWord" name="password">
                                </div>
                                <div id="btn_login"><a href="javascript: login_click();">
                                        <img title="Sign In"
                                             type="image" alt="Sign In"
                                             src="imgs/btn_login.jpg"
                                             width="73" height="17"
                                             border="0"/></a></div>
                            </form>

                            <div id="user_com">
                                <ul>
                                    <li><a href="">&#272;&#259;ng ký &#273;&#7841;i lý</a></li>
                                </ul>
                            </div>
                            <div id="img5"><img src="imgs/img5.jpg"/></div>
                            <div id="bg_bottom_login"></div>

                        </div>
                    </div>
                    <?= $this->render('adventisment') ?>

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
