<?php
//$start = microtime(true);
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = require(__DIR__ . '/../config/web.php');

function hh($data)
{
    yii\helpers\VarDumper::dump($data, 10, true);
    Yii::$app->end();
}

(new yii\web\Application($config))->run();
//echo '<div style="position: fixed;bottom: 0px; background-color: #973634;color:#FFF;">Время выполнения скрипта: '.round(microtime(true) - $start, 4).' сек.</div>';