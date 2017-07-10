<?php
    use yii\helpers\Html;
    use app\assets\AppAsset;
    use app\components\LangswitchWidget;
    use app\components\THelper;
    use app\components\SidebarWidget;
    $this->title = THelper::t('admin_panel');
    AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="app">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico?v=1" type="image/x-icon" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?= Yii::$app->view->params['favico'] ?>" >
    <?php $this->head() ?>
    <script type="text/javascript">
        var TRANSLATION = {
            "processing": "<?= THelper::t('datatable_processing') ?>",
            "search": "<?= THelper::t('datatable_search') ?>",
            "lengthMenu": "<?= THelper::t('datatable_length_menu') ?>",
            "info": "<?= THelper::t('datatable_info') ?>",
            "infoEmpty": "<?= THelper::t('datatable_info_empty') ?>",
            "infoFiltered": "<?= THelper::t('datatable_info_filtered') ?>",
            "infoPostFix": "",
            "loadingRecords": "<?= THelper::t('datatable_loading_records') ?>",
            "zeroRecords": "<?= THelper::t('datatable_zero_records') ?>",
            "emptyTable": "<?= THelper::t('datatable_empty_table') ?>",
            "paginate": {
                "first": "<?= THelper::t('datatable_paginate_first') ?>",
                "previous": "<?= THelper::t('datatable_paginate_previous') ?>",
                "next": "<?= THelper::t('datatable_paginate_next') ?>",
                "last": "<?= THelper::t('datatable_paginate_last') ?>"
            },
            "aria": {
                "sortAscending": "<?= THelper::t('datatable_sort_ascending') ?>",
                "sortDescending": "<?= THelper::t('datatable_sort_descending') ?>"
            }
        };
        var LANG = '<?=Yii::$app->language?>';
    </script>
</head>
<body>
<?php $this->beginBody() ?>
    <section class="vbox">
    <header class="bg-dark dk header navbar navbar-fixed-top-xs">
        <div class="navbar-header aside-md">
            <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
                <i class="fa fa-bars"></i>
            </a>
            <?= Html::a('<img alt ="logo admin" src= "' . Yii::$app->view->params['logo'] . '" />', ['/business'], ['class' => 'navbar-brand']) ?>
            <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
                <i class="fa fa-cog"></i>
            </a>
        </div>
        <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user">
            <li class="hidden-xs">
                <div style="padding-top: 7px;"><?= LangswitchWidget::widget() ?></div>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="thumb-sm avatar pull-left">
                        <?php if (Yii::$app->view->params['user']->avatar) { ?>
                            <img src="<?= Yii::$app->view->params['user']->avatar ?>" />
                        <?php } else { ?>
                            <img src="/images/avatar_default.png"/>
                        <?php } ?>
                    </span>
                    <?= Yii::$app->view->params['user']->firstName ?> <?= Yii::$app->view->params['user']->secondName ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInRight">
                    <span class="arrow top"></span>
                    <li>
                        <?= Html::a(THelper::t('exit'), ['/login/logout']) ?>
                    </li>
                </ul>
            </li>
        </ul>
    </header>
    <section>
        <section class="hbox stretch">
            <?= SidebarWidget::widget() ?>
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) { ?>
                            <div class="m-t-md alert alert-<?= $key ?>">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?= $message ?>
                            </div>
                        <?php } ?>
                        <?= $content ?>
                    </section>
                </section>
            </section>
        </section>
    </section>
</section>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>