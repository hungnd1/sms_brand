<?php
use backend\models\LoginForm;
use yii\helpers\Html;
use kartik\form\ActiveForm;
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

    <div style="margin:0 auto;width:965px;clear:both;background-image:url(<?= Url::to("@web/imgs/bg_main_conten.png") ?>);background-repeat:repeat-y;">
        <table border="0" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td style="width:706px; padding-left:9px; padding-top:4px;" valign="top" align="center">
                    <div align="center">
                        <a href="" style="text-decoration:none" target="_blank"><img src="<?= Url::to("@web/imgs/Banner2.png") ?>" width="706" height="250" border="0"></a>
                    </div>

                    <div id="box_mk">
                        <div id="conten_box2">
                            <a href="">MOBILE MARKETING LÀ GÌ?</a>
                            <div id="text_conten">
                                <b>Mobile Marketing</b> - hay còn gọi là <b>tiếp thị trên di động</b> là việc sử dụng các kênh di động phục vụ các hoạt động marketing.
                            </div>
                        </div>
                    </div>

                    <div id="box">
                        <div id="bg_box1">
                            <div id="conten_box1" style="text-align: left">
                                <a>SMS Marketing</a>
                                <div id="text_conten">
                                    <b>SMS Marketing</b> là gì?<br>
                                    Ứng dụng của <b>SMS Marketing</b><br>
                                    Hiệu quả của SMS Marketing<br>
                                    <a href="" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; margin-left:0px;"><font color="#0077ca">Chi tiết</font></a>
                                </div>
                                <a>SMS Brandname</a>
                                <div id="text_conten">
                                    <b>SMS Brandname</b> là gì?<br>
                                    Vì sao nên sử dụng SMS Brandname?<br>
                                    <a href="" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; margin-left:0px;"><font color="#0077ca">Chi tiết</font></a>
                                </div>
                            </div>
                            <div id="img1"></div>
                        </div>
                    </div>
                    <div id="box2">
                        <div id="bg_box1">
                            <div id="conten_box1">
                                <a href="">ĐĂNG KÝ VÀ SỬ DỤNG</a>
                                <div id="text_conten">
                                    Việc đăng ký tài khoản trên hệ thống cực kỳ đơn giản và nhanh chóng, các chức năng trên hệ thống dễ sử dụng, có hướng dẫn cụ thể cho từng đối tượng người dùng.
                                </div>
                            </div>
                            <div id="img2"></div>
                        </div>
                    </div>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="padding-top:9px; padding-bottom:4px;">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="box_tintuc" >
                                            <div id="tit_tin" class="tit_text">Tin tức</div>
                                            <div id="noidung">
                                                <ul>
                                                    <li><a href="" style="text-decoration:none;"><b>Tọa đàm trực tuyến: Doanh nghiệp Việt Nam & Xu hướng Tiếp thị Số</b></a></li>
                                                    <li><a href="" style="text-decoration:none;">Ưu đãi lớn nhân dịp ra mắt tính năng mới</a></li>
                                                    <li><a href="" style="text-decoration:none;">Facebook vượt trội Google ở thị trường quảng cáo di động</a></li>
                                                    <li><a href="" style="text-decoration:none;">Starbucks triển khai chiến dịch SMS Coupon như thế nào?</a></li>
                                                    <li><a href="" style="text-decoration:none;">Thay đổi trong chiến dịch SMS Marketing để không bị coi là Spam</a></li>
                                                    <li><a href="" style="text-decoration:none;">Vì sao SMS Marketing lại mang tiếng là Spam</a></li>
                                                </ul>
                                            </div>
                                            <div id="img_tin2"><img src="<?= Url::to('@web/imgs/img_tin.png') ?>" /></div>

                                            <div id="bottom_tintuc"></div>
                                            <div id="btn_t"><a href=""><img src="<?= Url::to('@web/imgs/btn_tinkhac.png') ?>" /></a></div>

                                            <div id="tit_daily" class="tit_text">Đại lý</div>
                                            <div id="noidung_daily">
                                                <p align="justify"><font color="#333333" size="2" face="Tahoma">Hệ thống hỗ trợ phát triển kinh doanh theo mô hình Tổng đại lý. Có nhiều chính sách cước và chiết khấu hấp dẫn dành cho Tổng đại lý khi phát triển cửa hàng.</font></p>
                                                <p>&nbsp;</p>
                                                <p><img src="<?= Url::to('@web/imgs/btn_dangky.png') ?>" onclick="Register();" /></p>
                                            </div>
                                            <div id="bottom_daily"></div>
                                            <div id="img_daily"><img src="<?= Url::to('@web/imgs/img_dangky.png') ?>" />
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
                            <p class="w3-center">&nbsp;</p>
                            <p class="w3-center">&nbsp;</p>
                            <p class="w3-center"><button onclick="" class="w3-btn w3-round-xlarge w3-hover-red w3-orange w3-border w3-border-red w3-text-white">Đăng nhập</button></p>
                            <p class="w3-center">&nbsp;</p>
                            <p class="w3-center" style="font-size: 11px"><i class="fa fa-sign-in" aria-hidden="true" style="font-size:13px;color:green"></i>
                                <a href="" style="color:#2d76b2; text-decoration:none;"><b> Đăng ký đại lý</b></a></p>

                        </div>

                    </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="231">
                        <tr height="2"><td></td></tr>
                        <tr><td style="border: 1px solid #ccc"><a href="" target="_blank"><img src="<?= Url::to('@web/imgs/raovat.jpg') ?>" border="0" width="230"></a></td></tr>

                        <tr height="2"><td></td></tr>
                        <tr><td style="border: 1px solid #ccc"><a href="" target="_blank"><img src="<?= Url::to('@web/imgs/vaz-ani.gif') ?>" border="0" width="230"></a></td></tr>

                        <tr height="2"><td></td></tr>
                        <tr><td style="border: 1px solid #ccc"><a href="" target="_blank"><img src="<?= Url::to('@web/imgs/logo_aimua24h.jpg') ?>" border="0" width="230"></a></td></tr>

                        <tr height="2"><td></td></tr>
                        <tr><td style="border: 1px solid #ccc"><a href="" target="_blank"><img src="<?= Url::to('@web/imgs/logo_vanhoadoanhnhan.png') ?>" border="0" width="230" height="100"></a></td></tr>

                        <tr height="2"><td></td></tr>
                        <tr><td style="border: 1px solid #ccc"><a href="" target="_blank"><img src="<?= Url::to('@web/imgs/logo_raovathot.gif') ?>" border="0" width="230"></a></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-left:9px; padding-top:9px; padding-bottom:4px;">
                    <table border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td valign="top" align="left" width="710">
                                <div class="fb-like" data-href="https://www.facebook.com/SmsMarketingG3g4" data-send="true" data-width="450" data-show-faces="true"></div>
                            </td>
                            <td valign="top" align="center" style="line-height: 170%" width="250">
                            </td>
                        </tr>
                    </table>
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