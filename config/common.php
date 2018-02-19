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
                'vkontakte' => [
                    'class' => 'app\modules\authclient\clients\VKontakte',
                    'clientId' => CLIENT_ID_VKONTAKTE,
                    'clientSecret' => CLIENT_SECRET_VKONTAKTE,
                    'delay' => 0.7,
                    'delayExecute' => 120,
                    'limitExecute' => 1,
                ],
                'facebook' => [
                    'class' => 'app\modules\authclient\clients\Facebook',
                    'clientId' => CLIENT_ID_FACEBOOK,
                    'clientSecret' => CLIENT_SECRET_FACEBOOK,
                ],
                'google' => [
                    'class' => 'app\modules\authclient\clients\Google',
                    'clientId' => CLIENT_ID_GOOGLE,
                    'clientSecret' => CLIENT_SECRET_GOOGLE,
                ],
                'twitter' => [
                    'class' => 'app\modules\authclient\clients\Twitter',
                    'attributeParams' => [
                        'include_email' => 'true'
                    ],
                    'consumerKey' => CLIENT_ID_TWITTER,
                    'consumerSecret' => CLIENT_SECRET_TWITTER,
                ],
                'yandex' => [
                    'class' => 'app\modules\authclient\clients\Yandex',
                    'clientId' => CLIENT_ID_YANDEX,
                    'clientSecret' => CLIENT_SECRET_YANDEX,
                ],
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
            'class' => 'app\modules\core\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Moscow',
            'dateFormat' => 'php:j.m.Y',
            'timeFormat' => 'php:H:i',
            'datetimeFormat' => 'php:j M Y, H:i',
            'nullDisplay' => '',
            'currencyCode' => null,
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
            'class' => 'app\modules\authclient\clients\VKontakte',
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
