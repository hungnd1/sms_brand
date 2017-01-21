<?php
use yii\helpers\Url;
?>
<div id="header">
    <div id="banner">
        <div id="bannerleft"></div>
        <div id="bannerright"></div>
    </div>
</div>
<div id="bg_menu">
    <div id="menu">
        <ul class="menu-web">
            <li><a href="<?= Url::toRoute(['site/login']) ?>" class='current'>Trang chủ</a></li>
            <li><img src="<?= Url::to("@web/imgs/space_menu.jpg")?>" /></li>
            <li><a href="<?= Url::toRoute(['site/news']) ?>" >Tin tức</a></li>
            <li><img src="<?= Url::to("@web/imgs/space_menu.jpg")?>" /></li>
            <li><a href="<?= Url::toRoute(['site/services','type'=>\common\models\News::TYPE_DICHVU]) ?>" >Dịch vụ</a></li>
            <li><img src="<?= Url::to("@web/imgs/space_menu.jpg") ?>" /></li>
            <li><a href="<?= Url::toRoute(['site/services','type'=>\common\models\News::TYPE_DAILY]) ?>" >Đại lý</a></li>
            <li><img src="<?= Url::to("@web/imgs/space_menu.jpg") ?>" /></li>
            <li><a href="<?= Url::toRoute(['site/services','type'=>\common\models\News::TYPE_HUONGDAN]) ?>" >Hướng dẫn</a></li>
            <li><img src="<?= Url::to("@web/imgs/space_menu.jpg")?>" /></li>
            <li><a href="<?= Url::toRoute(['site/services','type'=>\common\models\News::TYPE_LIENHE]) ?>" >Liên hệ</a></li>
            <li><img src="<?= Url::to("@web/imgs/space_menu.jpg")?>" /></li>
        </ul>
    </div>
</div>