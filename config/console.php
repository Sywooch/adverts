<?php

require(__DIR__ . '/bootstrap.php');

$consoleConfig = [
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => 'fishvision\migrate\controllers\MigrateController',
            'autoDiscover' => true,
            'migrationPaths' => [
                '@app/modules/users',
            ],
            'templateFile' => '@app/modules/core/views/migration.php'
        ],
        /*
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
         */
    ],
    'params' => require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $consoleConfig['bootstrap'][] = 'gii';
    $consoleConfig['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return \yii\helpers\ArrayHelper::merge(require(__DIR__ . '/common.php'), $consoleConfig);