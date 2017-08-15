<?php

require(__DIR__ . '/bootstrap.php');

$webConfig = [
    'defaultRoute' => 'adverts/advert',
    'as endSideBehavior' => 'app\modules\core\behaviors\EndSideBehavior',
    'controllerMap' => [
        'switch-end-side' => 'app\modules\core\controllers\SwitchEndSideController'
    ],
    'components' => [
        'assetManager' => [
            //'linkAssets' => true,
            'bundles' => [
                'yii\web\YiiAsset' => [
                    'sourcePath' => '@app/modules/core/assets/src',
                    'js' => ['js/yii.js'],
                ],
                'yii\widgets\ActiveFormAsset' => [
                    'sourcePath' => '@app/modules/core/assets/src',
                    'js' => ['js/yii.activeForm.js'],
                ],
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'cookieValidationKey' => 'U5X6dQpeo0XRgHpRy2BQGogBgJbsQf10',
        ],
        'response' => [
            'class' => 'app\modules\core\web\Response',
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
            'class' => 'app\modules\adverts\AdvertsModule',
            'as endSideBehavior' => 'app\modules\core\behaviors\EndSideBehavior',
        ],
        'users' => [
            'class' => 'app\modules\users\UsersModule',
            'as endSideBehavior' => 'app\modules\core\behaviors\EndSideBehavior',
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