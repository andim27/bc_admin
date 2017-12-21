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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/bootstrap.css',
        '/css/animate.css',
        '/css/font-awesome.min.css',
        '/css/font.css',
        '/js/datatables/datatables.css',
        '/js/datepicker/datepicker.css',
        '/js/select2/select2.css',
        '/js/select2/theme.css',
        '/css/fileinput.min.css',
        '/css/app.css',
        '/css/site.css',
        '/js/intro/introjs.css'
    ];
    public $js = [
        '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js',
        '/js/intro/intro.min.js',
        '/js/bootstrap.js',
        '/js/app.js',
        '/js/app.plugin.js',
        '/js/charts/easypiechart/jquery.easy-pie-chart.js',
        '/js/charts/sparkline/jquery.sparkline.min.js',
        '/js/charts/flot/jquery.flot.min.js',
        '/js/charts/flot/jquery.flot.tooltip.min.js',
        '/js/charts/flot/jquery.flot.resize.js',
        '/js/charts/flot/jquery.flot.grow.js',
        '/js/sortable/jquery.sortable.js',
        '/js/datatables/jquery.dataTables.min.js',
        '/js/fileinput.min.js',
        '/js/main/change_shares.js',
        '/js/main/date.js'
    ];
    public $jsOptions = ['position' => yii\web\View::POS_HEAD];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
