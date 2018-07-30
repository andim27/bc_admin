<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'settings' => [
            'class' => 'app\modules\settings\settings',
        ],
        'business' => [
            'class' => 'app\modules\business\business',
            'layout' => 'start'
        ],

        // for mongodb
        'gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'mongoDbModel' => [
                    'class' => 'yii\mongodb\gii\model\Generator'
                ]
            ],
        ],
        'debug' => [
            'class' => 'yii\\debug\\Module',
            'panels' => [
                'mongodb' => [
                    'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
                ],
            ],
        ],
    ],

    // for mongodb
    'controllerMap' => [
        'mongodb-migrate' => 'yii\mongodb\console\controllers\MigrateController'
    ],

    'components' => array(
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'polopolo',
            'class' => 'app\components\LangRequest'
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles'=>['guest']
        ],
        'language'=>'RU_ru',
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'errors',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
	'log' => [
            'targets' => [
                [
                    'class' => 'codemix\streamlog\Target',
                    'url' => 'php://stdout',
                    'levels' => ['info','trace'],
                    'logVars' => [],
                ],
                [
                    'class' => 'codemix\streamlog\Target',
                    'url' => 'php://stderr',
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                ],
            ],
        ],
//        'db' => require(__DIR__ . '/db.php'),

        // for mongodb
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
//            'dsn' => 'mongodb://gnc:b41uHcdR@bpt-shard-00-00-ezdfa.mongodb.net:27017,bpt-shard-00-01-ezdfa.mongodb.net:27017,bpt-shard-00-02-ezdfa.mongodb.net:27017/gnc?ssl=true&sslWeakCertificateValidation=true&replicaSet=BPT-shard-0&authSource=admin',
            'dsn' => 'mongodb://velton-1.ooo.ua:27018/gnc',
        ],

        'urlManager' => [
            'class' => 'app\components\LangUrlManager',
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'enablePrettyUrl' => true
        ],
        'assetManager' => [],
    ),
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;