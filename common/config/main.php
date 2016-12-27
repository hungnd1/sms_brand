<?php


$config = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'vi', /**  set target language to be vi */
    'aliases' => [
        '@file_downloads' => 'static/file_dowloads',
        '@example' => 'static/example',
        '@file_template' => 'static/file_template',
        '@default_site_id' => 1,
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\ApcCache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
//                    'forceTranslation' => true
                ],
                'yii*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
//                    'forceTranslation' => true
                ],
                'zii*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
//                    'forceTranslation' => true
                ],
//                'kvgrid*' => [
                //                    'class' => 'yii\i18n\PhpMessageSource',
                //                    'basePath' => '@common/messages',
                ////                    'forceTranslation' => true
                //                ],
            ],
        ],
    ],
    'timeZone' => 'Asia/Ho_Chi_Minh',
];


if (YII_ENV == 'prod' && extension_loaded('apcu')) {
    $config['components']['cache'] = [
        'class' => 'yii\caching\ApcCache',
        'useApcu' => true,
    ];
}

return $config;
