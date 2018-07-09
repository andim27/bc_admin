<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;
use yii\web\AssetBundle;
use yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GoodsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/app.work.v2.css',
        '/css/helpers.css',
        //'/css/font.css',
        '/js/fuelux/fuelux.css',
        '/js/datatables/datatables.css',
        //'/js/select2/select2.css',
        //'/js/select2/theme.css',

    ];
    public $js = [
        //'//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js',
        //'js/app.v2.js',
        '/js/fuelux/fuelux.js',
        '/js/wysiwyg/jquery.hotkeys.js',
        '/js/wysiwyg/bootstrap-wysiwyg.js',
        '/js/wysiwyg/demo.js',
        'js/select2/select2.min.js',
        '/js/datatables/jquery.dataTables.min.js',
    ];
    public $jsOptions = ['position' => yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
