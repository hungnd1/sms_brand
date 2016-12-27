<?php
return [
    'adminEmail' => 'admin@example.com',
    'semantic_url' => 'http://semantic.tvod.vn/api/film',
    'semantic_url2' => 'http://se.tvod.vn',
    'site_id' => 5,
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'sms_proxy' => [
        'url' => 'http://10.84.73.6:13013/cgi-bin/sendsms',
        'username' => 'tester',
        'password' => 'foobar',
        'debug' => false
    ],
    'access_private' => [
        'user_name' => 'msp_private',
        'password' => 'Msp!@123',
        'ip_privates' => [
            '192.0.0.0/8',
            '10.0.0.0/8',
            '10.84.0.0/16',
            '127.0.0.0/16',
        ],
    ],
    'tvod1Only' => false,
    'payment_gate' => [
        'url' => 'https://pay.smartgate.vn/Checkout',
        'tvod2_api_base_url' => 'http://103.31.126.223/api/web/index.php/',
        'merchant_id' => 'tvod',
        'secret_key' => 'uk3h3f',
        'command' => 'PAY',
        'order_type_digital' => 2,
    ],
    'sms_charging' => [
        'username' => 'tvod2',
        'password' => '123456',
    ],
];
