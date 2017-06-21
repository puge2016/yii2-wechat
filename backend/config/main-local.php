<?php

$config = [
    "language" => "zh-CN",
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5Hi0F7HLDDQGL8Q5blsqF11YTxRoF8J6',
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//        'allowedIPs'    => ['127.0.0.1','::1','192.168.*.*']
//    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'         => 'yii\gii\Module',
        'allowedIPs'    => ['127.0.0.1','::1','*.*.*.*']
    ];
}

return $config;
