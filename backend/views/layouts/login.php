<?php
use backend\widgets\Footer;
use backend\widgets\Header;
use common\assets\MetronicLoginAsset;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

MetronicLoginAsset::register($this);
$this->registerJs("Metronic.init();");
$this->registerJs("Layout.init();");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link href="<?= Url::to("@web/ncss.css") ?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?= Url::to("@web/awesome/css/font-awesome.min.css") ?>"/>
    <link rel="stylesheet" href="<?= Url::to("@web/w3.css") ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body >
<?php $this->beginBody() ?>

<?= Header::widget([]) ?>

<?= $content ?>

<?= Footer::widget([]) ?>

<?php $this->endBody() ?>
</body>
<script type="text/javascript">
//    $(function() {
//        var loc = window.location.href;
//        $('.menu-web a').each(function() {
//            $(this).removeClass('current');
////            var link = $(this).find('first').attr('href');
////            alert(loc.indexOf(link));
//            if(loc.indexOf(link) > 0)
//                $(this).addClass('current');
//        });
//    });

</script>
</html>
<?php $this->endPage() ?>

<script type="text/javascript">
    function login_click() {
        var username = document.getElementById('username').value;
        var password = document.getElementById('passWord').value;
        if (username == '') {
            alert('Bạn chưa nhập tên đăng nhập');
            return;
        }
        if (password == '') {
            alert('Bạn chưa nhập mật khẩu');
            return;
        }
        $.ajax({
            type: 'POST',
            url: '<?= Url::toRoute(['site/login-cms']) ?>',
            beforeSend: function () {
                //code;
            },
            data: {username: username, password: password},
            success: function (data) {
                var responseJSON = jQuery.parseJSON(data);
                if (responseJSON.status == "ok") {
                    location.href = '<?= Url::toRoute(['site/index']) ?>';
                }
                else {
                    alert('Đăng nhập không thành công');
                    location.reload();
                }
            }
        });
    }
</script>
