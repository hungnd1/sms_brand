<?php
use backend\models\LoginForm;
use common\models\News;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="bg_main">
    <div style="height:4px;"></div>
    <div id="top">
        <div id="bu_left_top"></div>
        <div id="bg_top"></div>
        <div id="bu_right_top"></div>

    </div>

    <div
        style="margin:0 auto;width:965px;clear:both;background-image:url(<?= Url::to("@web/imgs/bg_main_conten.png") ?>);background-repeat:repeat-y;">
        <table border="0" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td style="width:706px; padding-left:9px; padding-top:4px;" valign="top" align="center">
                    <div align="center">
                        <a href="" style="text-decoration:none" target="_blank"><img
                                src="<?= Url::to("@web/imgs/Banner2.png") ?>" width="706" height="250" border="0"></a>
                    </div>

                    <div id="box_mk">
                        <div id="conten_box2">
                            <a href="">MOBILE MARKETING LÀ GÌ?</a>
                            <div id="text_conten">
                                <b>Mobile Marketing</b> - hay còn gọi là <b>tiếp thị trên di động</b> là việc sử dụng
                                các kênh di động phục vụ các hoạt động marketing.
                            </div>
                        </div>
                    </div>

                    <div id="box">
                        <div id="bg_box1">
                            <div id="conten_box1" style="text-align: left">
                                <?php if (isset($listService) && !empty($listService)) {
                                    foreach ($listService as $item) {
                                        /** @var $item \common\models\News */
                                        ?>
                                        <a><?= $item->display_name ?></a>
                                        <div id="text_conten">
                                            <?= $item->short_description ?>
                                            <a href="<?= Url::toRoute(['site/services', 'id' => $item->id, 'type' => $item->type]) ?>"
                                               style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; margin-left:0px;"><font
                                                    color="#0077ca">Chi tiết</font></a>
                                        </div>
                                    <?php }
                                } else { ?>

                                    <a>SMS Brandname</a>
                                    <div id="text_conten">
                                        <b>SMS Brandname</b> là gì?<br>
                                        Vì sao nên sử dụng SMS Brandname?<br>
                                        <a href=""
                                           style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; margin-left:0px;"><font
                                                color="#0077ca">Chi tiết</font></a>
                                    </div>
                                <?php } ?>
                            </div>
                            <div id="img1"></div>
                        </div>
                    </div>
                    <div id="box2">
                        <div id="bg_box1">
                            <div id="conten_box1">
                                <?php if (isset($support) && !empty($support)) { ?>
                                    <a href="<?= Url::toRoute(['site/services', 'id' => $support->id, 'type' => $support->type]) ?>"><?= $support->display_name ?></a>
                                    <div id="text_conten">
                                        <?= $support->short_description ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div id="img2"></div>
                        </div>
                    </div>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="padding-top:9px; padding-bottom:4px;">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="box_tintuc">
                                            <div id="tit_tin" class="tit_text">Tin tức</div>
                                            <div id="noidung">
                                                <ul>
                                                    <?php if (isset($listNews) && !empty($listNews)) {
                                                        foreach ($listNews as $item) {
                                                            /** @var  $item \common\models\News */
                                                            ?>
                                                            <li><a href="<?= Url::toRoute(['site/services','id'=>$item->id,'type'=>$item->type]) ?>"
                                                                   style="text-decoration:none;"><?= $item->display_name ?></a>
                                                            </li>
                                                        <?php }
                                                    }else{?>
                                                        <li><a href="" style="text-decoration:none;"><b>Tọa đàm trực tuyến:
                                                                    Doanh nghiệp Việt Nam & Xu hướng Tiếp thị Số</b></a>
                                                        </li>
                                                    <?php } ?>

                                                </ul>
                                            </div>
                                            <div id="img_tin2"><img src="<?= Url::to('@web/imgs/img_tin.png') ?>"/>
                                            </div>

                                            <div id="bottom_tintuc"></div>
                                            <div id="btn_t"><a href="<?= Url::toRoute(['site/news']) ?>"><img
                                                        src="<?= Url::to('@web/imgs/btn_tinkhac.png') ?>"/></a></div>

                                            <div id="tit_daily" class="tit_text">Đại lý</div>
                                            <div id="noidung_daily">
                                                <p align="justify"><font color="#333333" size="2" face="Tahoma">Hệ thống
                                                        hỗ trợ phát triển kinh doanh theo mô hình Tổng đại lý. Có nhiều
                                                        chính sách cước và chiết khấu hấp dẫn dành cho Tổng đại lý khi
                                                        phát triển cửa hàng.</font></p>
                                                <p>&nbsp;</p>
                                                <p><a href="<?= Url::toRoute(['site/services','type'=> News::TYPE_DAILY]) ?>"><img src="<?= Url::to('@web/imgs/btn_dangky.png') ?>"/></a></p>
                                            </div>
                                            <div id="bottom_daily"></div>
                                            <div id="img_daily"><a href="<?= Url::toRoute(['site/services','type'=> News::TYPE_DAILY]) ?>"><img src="<?= Url::to('@web/imgs/img_dangky.png') ?>"/></a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                </td>
                <td width="5"></td>
                <td valign="top" style="padding-top: 4px;">
                    <div class="cl2">
                        <div id="tit_login" class="tit_text">Sử dụng dịch vụ</div>
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
                                    <li><a href="<?= Url::toRoute(['site/register']) ?>">&#272;&#259;ng ký &#273;&#7841;i lý</a></li>
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