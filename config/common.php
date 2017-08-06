<?php

$commonConfig = [
    'id' => 'Adverts',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru',
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'app\modules\authclient\clients\Facebook',
                    'clientId' => CLIENT_ID_FACEBOOK,
                    'clientSecret' => CLIENT_SECRET_FACEBOOK,
                ],
                /*'google' => [
                    'class' => 'app\modules\authclient\clients\Google',
                    'clientId' => 'airy-dialect-95610',
                    'clientSecret' => 'airy-dialect-95610',
                ],
                'twitter' => [
                    'class' => 'app\modules\authclient\clients\Twitter',
                    'attributeParams' => [
                        'include_email' => 'true'
                    ],
                    'consumerKey' => '',
                    'consumerSecret' => '',
                ],*/
                'vkontakte' => [
                    'class' => 'app\modules\authclient\clients\VKontakte',
                    'clientId' => CLIENT_ID_VKONTAKTE,
                    'clientSecret' => CLIENT_SECRET_VKONTAKTE,
                    'delay' => 0.7,
                    'delayExecute' => 120,
                    'limitExecute' => 1,
                ],
                /*'yandex' => [
                    'class' => 'app\modules\authclient\clients\Yandex',
                    'clientId' => '',
                    'clientSecret' => '',
                ],*/
            ]
        ],
        'authClientComponent' => [
            'class' => 'app\modules\authclient\components\AuthClientComponent',
        ],
        /*'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['Admin', 'User']
        ],*/
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => require(__DIR__ . '/db.php'),
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
        'vk' => [
            'class' => 'jumper423\VK',
            'clientId' => '11111',
            'clientSecret' => 'n9wsv98svSD867SA7dsda87',
            'delay' => 0.7, // Минимальная задержка между запросами
            'delayExecute' => 120, // Задержка между группами инструкций в очереди
            'limitExecute' => 1, // Количество инструкций на одно выполнении в очереди
            'captcha' => 'captcha', // Компонент по распознованию капчи
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $commonConfig['bootstrap'][] = 'debug';
    $commonConfig['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $commonConfig['bootstrap'][] = 'gii';
    $commonConfig['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $commonConfig;
