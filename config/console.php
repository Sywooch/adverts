<?php

$config = [
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'fishvision\migrate\controllers\MigrateController',
            'autoDiscover' => true,
            'migrationPaths' => [
                '@app/modules/users',
            ],
        ],
        /*
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
         */
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return \yii\helpers\ArrayHelper::merge(require(__DIR__ . '/common.php'), $config);