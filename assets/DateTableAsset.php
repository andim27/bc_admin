<?php

namespace app\assets;
use yii\web\AssetBundle;
use yii;

class DateTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/js/datatables/datatables.css',
    ];
    public $js = [
        '/js/datatables/jquery.dataTables.min.js',
    ];
    public $jsOptions = ['position' => yii\web\View::POS_HEAD];
    public $depends = [

    ];
}
