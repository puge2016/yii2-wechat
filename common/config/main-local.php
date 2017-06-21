<?php
return [
    'components' => [
        'db'    => [
            'class'         => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=YII2_DB',
            'username'      => 'app',
            'password'      => 'app@055221',
            'charset'       => 'utf8',
            'tablePrefix'   => 'wechat_'
        ],
        'log' => [
            //trace（用于开发调试时记录日志，需要把YII_DEBUG设置为true），
            //error（用于记录不可恢复的错误信息)，
            //warning（用于记录一些警告信息)
            //info(用于记录一些系统行为如管理员操作提示)

            'traceLevel'    => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,
            'targets'       => [

                'email'         => [
                    'class'             => 'yii\log\EmailTarget',
                    'levels'            => ['error', 'warning', 'info'],
                    'categories'        => ['mail_log'],
                    'logVars'           => [],
                    'message'           => [
                        'from'          => ['839427653@qq.com'],
                        'to'            => ['839427653@qq.com'],
                        'subject'       => '新日志消息'. date('Y-m-d H:i:s',time()) ,
                    ],
                ],

                'db'            => [
                    //数据库存储日志对象
                    'class'             => 'yii\log\DbTarget',
                    'levels'            => ['error', 'warning', 'info'],
                    'categories'        => ['db_log'],
                    'logVars'           => [],  //捕获请求参数 如 '_GET', '_POST', '_FILES', '_COOKIE', '_SESSION','_SERVER'
                    'logTable'          => '{{%system_log}}',
                ],

                'warning_file'  => [
                    'logFile'           => '@app/runtime/logs/warning.log',
                    'class'             => 'yii\log\FileTarget',
                    'levels'            => ['warning'],
                    'logVars'           => [],
                ],

                'info_file'     => [
                    'logFile'           => '@app/runtime/logs/info.log',
                    'class'             => 'yii\log\FileTarget',
                    'levels'            => ['info'],
                    'logVars'           => [],
                    'exportInterval'    => 1 ,
                ],
                'info_wechat'     => [
                    'maxFileSize'       => 1024*20, // 1024*20, k
                    'fileMode'          => 0755,
                    'maxLogFiles'       => 100,
                    'rotateByCopy'      => false,
                    'prefix'            => function() {
                        //日志格式自定义 回调方法
                        if (Yii::$app === null) {
                             return '';
                            }
                        $request    = Yii::$app->getRequest();
                        $ip         = $request->getUserIP() ;
                        $controller = Yii::$app->controller->id;
                        $action     = Yii::$app->controller->action->id;
                        $user       = Yii::$app->has('user', true) ? Yii::$app->get('user') : null;
                        $userID     = $user ? $user->getId(false) : '-';
                        return "[ip:$ip][controller:$controller-action:$action][userID:$userID ]";
                        },
                    'logFile'           => '@app/runtime/logs/wechat_'.date('Ymd').'.log', //自定义文件路径
                    'class'             => 'yii\log\FileTarget',
                    'levels'            => ['info'],
                    'categories'        => ['wechat'],
                    'logVars'           => [],
                    'exportInterval'    => 1 ,
                ],

                'trace_file'    => [
                    'logFile'           => '@app/runtime/logs/trace.log',
                    'class'             => 'yii\log\FileTarget',
                    'levels'            => ['trace'],
                    'logVars'           => [],
                ],

                'profile_file'  => [
                    'logFile'           => '@app/runtime/logs/profile.log',
                    'class'             => 'yii\log\FileTarget',
                    'levels'            => ['profile'],
                    'logVars'           => [],
                ],

            ],
        ],

        'redis'                 => [
            'class'                     => 'yii\redis\Connection',
            'hostname'                  => '127.0.0.1',
            'port'                      => 6379,
            'database'                  => 0,
            'password'                  => 'redis@055221',
        ],

        'mailer'                => [
            'class'                     => 'yii\swiftmailer\Mailer',
            'viewPath'                  => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport'  => false,
            'transport'         => [
                'class'                 => 'Swift_SmtpTransport',
                'host'                  => 'smtp.qq.com',
                'username'              => '839427653@qq.com',
                'password'              => 'qwsbluoigntpbcdb' ,
                'port'                  => '465',
                'encryption'            => 'ssl',
            ],
        ],

        'cache' => [
            'class' => 'yii\caching\MemCache',
            'servers' => [
                [
                    'host' => 'localhost',
                    'port' => 11211,
                    'weight' => 100,
                ],
                [
                    'host' => 'localhost',
                    'port' => 11211,
                    'weight' => 50,
                ],
            ],
            'useMemcached' => true ,
        ],

    ],
];
