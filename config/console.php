<?php

$params = require __DIR__ . '/params.php';
$params = require(__DIR__ . '/bootstrap.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\console\controllers',

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        // for mongodb
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
//            'dsn' => 'mongodb://localhost/gnc',
            'dsn' => 'mongodb://gnc:b41uHcdR@bpt-shard-00-00-ezdfa.mongodb.net:27017/gnc?ssl=true&replicaSet=BPT-shard-0&authSource=admin',
        ],
    ],
    'params' => $params,

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
