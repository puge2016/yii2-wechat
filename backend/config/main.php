<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],

    'modules' => [
        'admin' => [
            'class' => 'backend\modules\admin\Module',
            'layout' => 'left-menu',
//            'controllerMap' => [
//                'account' => 'app\controllers\UserController',
//                'article' => [
//                    'class' => 'app\controllers\PostController',
//                    'enableCsrfValidation' => false,
//                ],
//            ],
        ],
        'wechat' => [
            'class' => 'backend\modules\wechat\WechatModule',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],

        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],

        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'suffix' => '.html', //后缀
            'rules' => [
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
    ],
    'as access' => [
        'class' => 'backend\modules\admin\components\AccessControl',
        'allowActions' => [
            //这里是允许访问的action
            //controller/action
            '*',
            ]
        ],
    'params' => $params,
];
