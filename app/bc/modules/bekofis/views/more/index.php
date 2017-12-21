<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.08.2015
 * Time: 13:18
 */
use app\components\THelper;

$this->title = THelper::t('more_about_recommender');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->session->getFlash('error');
?>

    <section class="hbox stretch">
        <aside id="subNav" class="aside-md bg-white b-r">
            <div class="wrapper b-b header"><?=THelper::t('language_list')?></div>
            <ul class="nav">
                <?php
                $lang = Yii::$app->language;
                foreach($models as $model) {
                    echo '<li class="b-b b-light"><a data-id="'.$model->id.'" class="lang"><i class="fa fa-chevron-right pull-right m-t-xs text-xs icon-muted"></i>' . $model->title . '</a></li>';
                }
                ?>
                <!--<li class="b-b b-light"><a href="/email-list/create"><i class="fa fa-chevron-right pull-right m-t-xs text-xs icon-muted"></i>������� ����� ������</a></li>-->
            </ul>
        </aside>
        <aside class="f">
            <div class="header b-b clearfix panel panel-default"><?=THelper::t('wysiwyg')?></div>
        </aside>
    </section>
    <!--/'. $lang . '/bekofis/conditions/conditions?id='.$model->id.'-->
<?php $this->registerJsFile('js/main/save.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/wysiwyg/bootstrap-wysiwyg.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/wysiwyg/jquery.hotkeys.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/wysiwyg/demo.js',['depends'=>['app\assets\AppAsset']]); ?>