<?php
use app\components\THelper;

$this->title = THelper::t('marketing_plan');
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
            </ul>
        </aside>
        <aside class="f">
            <div class="header b-b clearfix panel panel-default"><?=THelper::t('marketing_plan')?><!--Маркетинг-план--></div>
        </aside>
    </section>

<?php $this->registerJsFile('js/main/marketing.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/wysiwyg/bootstrap-wysiwyg.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/wysiwyg/jquery.hotkeys.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/wysiwyg/demo.js',['depends'=>['app\assets\AppAsset']]); ?>