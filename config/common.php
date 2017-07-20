<?php

return [
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
                    'clientId' => '1597163377228520',
                    'clientSecret' => 'f6b971c36a61d5dc4275f81db53d94f4',
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
                    'clientId' => '4909741',
                    'clientSecret' => 'E2T2TUfxvQqoMVupTg8f',
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
    ],
    'params' => require(__DIR__ . '/params.php'),
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
