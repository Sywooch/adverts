<?php

require(__DIR__ . '/bootstrap.php');

$webConfig = [
    'defaultRoute' => 'adverts/advert',
    'components' => [
        'assetManager' => [
            //'linkAssets' => true
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
        ],
    ],
    'params' => require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $webConfig['bootstrap'][] = 'debug';
    $webConfig['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $webConfig['bootstrap'][] = 'gii';
    $webConfig['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return \yii\helpers\ArrayHelper::merge(require(__DIR__ . '/common.php'), $webConfig);