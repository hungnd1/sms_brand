<?php
use backend\assets\AppAsset;
use common\models\ContactDetail;
use common\models\KodiCategory;
use common\widgets\Alert;
use common\widgets\Nav;
use common\widgets\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$this->registerJs("Metronic.init();");
$this->registerJs("Layout.init();");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="page-header-menu-fixed">
<?php $this->beginBody() ?>
<div class="page-header">
    <?php
    NavBar::begin([
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => [
            'class' => 'page-logo'
        ],
        'renderInnerContainer' => true,
        'innerContainerOptions' => [
            'class' => 'container-fluid'
        ],
        'options' => [
            'class' => 'page-header-top',
        ],
        'containerOptions' => [
            'class' => 'top-menu'
        ],
    ]);
    if (Yii::$app->user->isGuest) {
        $rightItems[] = [
            'encode' => false,
            'label' => '<i class="icon-user"></i><span class="username username-hide-on-mobile">Đăng nhập</span>',
            'url' => Yii::$app->urlManager->createUrl("site/login"),
            'options' => [
                'class' => 'dropdown dropdown-user'
            ],
            'linkOptions' => [
                'class' => "dropdown-toggle",
            ],
        ];
    } else {
        $rightItems[] = [
            'encode' => false,
            'label' => '<img alt="" class="img-circle" src="' . Url::to("@web/img/haha.png") . '"/>
					<span class="username username-hide-on-mobile">
						 ' . Yii::$app->user->identity->username . '
					</span>',
            'options' => ['class' => 'dropdown dropdown-user dropdown-dark'],
            'linkOptions' => [
                'data-hover' => "dropdown",
                'data-close-others' => "true"
            ],
            'url' => 'javascript:;',
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="icon-user"></i> Thông tin tài khoàn </a>',
                    'url' => ['user/info']
                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-key"></i> Đăng xuất',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post'],
                ],
            ]
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav pull-right'],
        'items' => $rightItems,
        'activateParents' => true
    ]);
    NavBar::end();
    ?>

    <?php
    NavBar::begin([
        'renderInnerContainer' => true,
        'innerContainerOptions' => [
            'class' => 'container-fluid'
        ],
        'options' => [
            'class' => 'page-header-menu',
            'style' => 'display: block;'
        ],
        'containerOptions' => [
            'class' => 'hor-menu'
        ],
        'toggleBtn' => false
    ]);
    $menuItems = [


        [
            'label' => 'Hệ thống',
            'url' => 'javascript:;',
            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
            'items' => [
                [
                    'label' => '<i class="icon-key"></i> Quản lý đầu số',
                    'encode' => false,
                    'url' => ['network/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-key"></i> Quản lý quyền',
                    'url' => ['rbac-backend/permission'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-lock-open"></i> QL nhóm quyền',
                    'url' => ['rbac-backend/role'],
                    'require_auth' => true,
                ],
            ]
        ],
        [
            'label' => 'Quản lý tài khoản',
            'url' > 'javascript:;',
            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Thông tin brandname',
                    'url' => ['brandname/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-users"></i> QL người dùng',
                    'url' => ['user/index'],
                    'require_auth' => true,
                ],
            ]
        ],
        [
            'label' => 'Gửi tin',
            'url' => 'javascript:;',
            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Tin nhắn mẫu',
                    'url' => ['template-sms/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Nhật ký gửi SMS',
                    'url' => ['history-contact/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Gửi tin theo danh bạ',
                    'url' => ['history-contact/create', 'type' => 1],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Gửi tin theo file excel',
                    'url' => ['history-contact/create', 'type' => 2],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Sinh nhật trong tháng ' . date('m'),
                    'url' => ['contact-detail/birthday'],
                    'require_auth' => true,
                ],

            ]
        ],

        [
            'label' => 'Quản lý danh bạ',
            'url' => 'javascript:;',
            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Tất cả danh bạ',
                    'url' => ['contact/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="icon-users"></i> Nhận xét',
                    'url' => ['user/index'],
                    'require_auth' => true,
                ],
            ]
        ],
        [
            'label' => 'Báo cáo thông kê và lịch sử,tìm kiếm',
            'url' => 'javascript:;',
            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Báo cáo ngày',
                    'url' => ['contact-detail/report'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Báo cáo tháng',
                    'url' => ['contact-detail/report-month'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Tìm kiếm',
                    'url' => ['contact-detail/search'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class=" icon-eyeglasses"></i> Lịch sử hoạt động',
                    'url' => ['user-activity/index'],
                    'require_auth' => true,
                ],
            ]
        ],
        [
            'label' => 'Quản lý môn học và điểm',
            'url' => 'javascript:;',
            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Danh sách môn học',
                    'url' => ['subject/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Điểm môn học',
                    'url' => ['mark/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Điểm tổng kết',
                    'url' => ['mark-summary/index'],
                    'require_auth' => true,
                ],
            ]
        ],
        [
            'label' => 'Quản lý kỳ thi',
            'url' => 'javascript:;',
            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Tạo mới kỳ thi',
                    'url' => ['exam/create'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Điểm kỳ thi',
                    'url' => ['exam/index'],
                    'require_auth' => true,
                ],
            ]
        ],
        [
            'label' => 'Quản lý năm học',
            'url' => 'javascript:;',
            'options' => ['class' => 'menu-dropdown mega-menu-dropdown'],
            'linkOptions' => ['data-hover' => 'megamenu-dropdown', 'data-close-others' => 'true'],
            'items' => [
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Tác vụ năm học',
                    'url' => ['school-year/index'],
                    'require_auth' => true,
                ],
                [
                    'encode' => false,
                    'label' => '<i class="fa fa-server"></i> Lịch sử lên lớp',
                    'url' => ['history-class-up/index'],
                    'require_auth' => true,
                ],
            ]
        ],
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $menuItems,
        'activateParents' => true
    ]);
    NavBar::end();
    ?>
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!--    <div class="page-head">-->
    <!--        <div class="container-fluid">-->
    <!--            <div class="page-title">-->
    <!--                <h1>--><?php //echo $this->title ?><!--</h1>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <div class="page-content">
        <div class="container-fluid">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'page-breadcrumb breadcrumb'
                ],
                'itemTemplate' => "<li>{link}<i class=\"fa fa-circle\"></i></li>\n",
                'activeItemTemplate' => "<li class=\"active\">{link}</li>\n"
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer footer">
    <div class="container-fluid">
        <p><b>&copy;Copyright <?php echo date('Y'); ?> </b>. All Rights Reserved. <b>Kodi Backend</b>.
            Design By VIVAS Co.,Ltd.</p>
    </div>
</div>
<div class="scroll-to-top">
    <i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<!-- Modal -->
