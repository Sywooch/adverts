<?php

require(__DIR__ . '/bootstrap.php');

$webConfig = [
    'defaultRoute' => 'adverts/advert',
    'as endSideBehavior' => 'app\modules\core\behaviors\EndSideBehavior',
    'controllerMap' => [
        'switch-end-side' => 'app\modules\core\controllers\SwitchEndSideController'
    ],
    'components' => [
        'vkPublisher' => [
            'class' => 'app\modules\authclient\components\VkPublisher'
        ],
        'assetManager' => [
            //'linkAssets' => true,
            'bundles' => [
                'y
                ii\web\YiiAsset' => [
                    'sourcePath' => '@app/modules/core/assets/src',
                    'js' => ['js/yii.js'],
                ],
                'yii\widgets\ActiveFormAsset' => [
                    'sourcePath' => '@app/modules/core/assets/src',
                    'js' => ['js/yii.activeForm.js'],
                ],
            ]
        ],
        'bookmarksManager' => [
            'class' => 'app\modules\core\components\BookmarksManager',
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
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=9312',
            'username' => 'root',
            'password' => '',
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
        'view' => [
            'class' => 'app\modules\core\web\View',
        ]
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