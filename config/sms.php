<?php
return [
    // HTTP 请求的超时时间（秒）
    'timeout'  => 5.0,


    // 模板
    'template' => [
        'shop_perfecting'         => env('SMS_SHOP_PERFECTING'),
        'credits_cash'         => env('SMS_CREDITS_CASH'),
        'shop_perfecting_content' => env('SMS_SHOP_PERFECTING_CONTENT')
    ],

    'yunpian_template' => [
        'id' => env('SMS_YUNPIAN_TEMPLATE')
    ],
    'credits_cash_yunpian_template' => [
        'id' => env('SMS_YUNPIAN_TEMPLATE_CREDITS_CASH')
    ],

    'jump_sms' => env('JUMP_SMS', 0),

    // 默认发送配置
    'default'  => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'aliyun', 'yunpian'
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'aliyun'   => [
            'access_key_id'     => env('SMS_ACCESS_KEY_ID'),
            'access_key_secret' => env('SMS_ACCESS_KEY_SECRET'),
            'sign_name'         => env('SMS_SIGN_NAME'),
        ],
        'yunpian' => [
            'api_key' => env('SMS_YUNPIAN_API_KEY'),
        ]
    ],
];