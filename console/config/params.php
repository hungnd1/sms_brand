<?php
return [
    'adminEmail' => 'admin@example.com',
    'migrate_tvod1' => [
        'catchup_min_day' => 35, // 35 days
        'catchup_process_timeout' => 12, // 12 hours value in seconds
        'content_process_timeout' => 12, // 12 hours value in seconds
        'force_video' => true, // Dung truong hop can chay lai toan bo du lieu video
        'force_catchup' => true, // Dung truong hop can chay lai toan bo du lieu catchup
    ]
];
