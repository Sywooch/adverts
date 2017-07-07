<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'Adverts',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'defaultRoute' => 'adverts/advert',
    'components' => [
        'assetManager' => [
            //'linkAssets' => true
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['Admin', 'User']
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'dateFormat' => 'php:d.m.Y',
            'timeFormat' => 'php:d.m.Y H:i',
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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'U5X6dQpeo0XRgHpRy2BQGogBgJbsQf10',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'user' => [
            'class' => 'app\modules\core\web\User',
            'loginUrl' => '/users/auth/login'
        ],
    ],
    'modules' => [
        'adverts' => [
            'class' => 'app\modules\adverts\AdvertsModule'
        ],
        'users' => [
            'class' => 'app\modules\users\usersModule'
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
