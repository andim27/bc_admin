<?php
    use app\components\DateHelper;
use app\components\PackHelper;
use app\components\UrlHelper;
use yii\helpers\Html;
    use app\assets\AppAsset;
    use yii\widgets\Breadcrumbs;
    use app\components\LangswitchWidget;
    use app\components\InstructionWidget;
    use app\components\THelper;
    use app\components\NotificationWidget;
    use yii\helpers\Url;
    use app\models\api;
    $this->title = THelper::t('business_center');
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
            LANG = '<?=Yii::$app->language?>';
        </script>
    </head>
    <body>
<?php $this->beginBody() ?>
<section class="vbox">
    <header class="bg-<?= Yii::$app->view->params['a'] ?> dk header navbar navbar-fixed-top-xs">
        <div class="navbar-header aside-md">
            <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
                <i class="fa fa-bars"></i>
            </a>
            <?= Html::a('<img alt ="logo admin" src= "/images/logo_business.png" width = "" height = "" />', ['/business'], ['class' => 'navbar-brand']) ?>
            <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
                <i class="fa fa-cog"></i>
            </a>
        </div>
        <ul class="nav navbar-nav hidden-xs">
            <li>
                <a id="username">
                    <i class="fa fa-user"></i>
                    <span class="font-bold"><?= Yii::$app->view->params['user']->username ?></span>
                </a>
            </li>
            <li>
                <div class="m-t m-l">
                     <a class="dropdown-toggle btn btn-xs btn-blue-one change-layout" title="<?= THelper::t('change_layout') ?>"><i class="fa fa-star-half-o"></i></a>
                </div>
            </li>
            <?= InstructionWidget::widget() ?>
            <li>
                <div class="m-t m-l">
                    <?php
                        $user = Yii::$app->view->params['user'];
                        $dateBuePack = !empty($user->statistics)
                            ? $user->statistics->dateBuyPack
                            : null;

                        if ($dateBuePack && DateHelper::validateISO8601Date($dateBuePack)) {
                            $color = 'btn-blue-one';
                        } else {
                            $color = 'btn-danger';
                        }
                    ?>

                    <a href="<?= Url::base() ?>/<?= Yii::$app->language ?>/business/information/price" class="dropdown-toggle btn btn-xs <?= $color ?> start-business-pack" title="<?= THelper::t('start_business_pack') ?>"><?= THelper::t('start_business_pack') ?></a>
                </div>
            </li>
            <?php if (Yii::$app->view->params['user']->bs) { ?>
                <li>
                    <div class="m-t m-l">
                        <a href="http://lifestyle.businessprocess.biz/remote/backoffice<?=UrlHelper::getLifestyleBcBizAuthURI(Yii::$app->view->params['user'])?>" class="dropdown-toggle btn btn-xs <?= $color ?>" title="<?= THelper::t('enter_to_lifestyle') ?>"><?= THelper::t('enter_to_lifestyle') ?></a>
                    </div>
                </li>
            <?php } ?>

            <?php if (PackHelper::hasPack($user, 'webwellness')) { ?>
                <li>
                    <div class="m-t m-l">
                        <a href="http://clinic.webwellness.net/partner/login<?=UrlHelper::getWebWellnessBcBizAuthURI($user)?>" class="dropdown-toggle btn btn-xs <?= $color ?>" title="<?= THelper::t('enter_to_webwellness') ?>"><?= THelper::t('enter_to_webwellness') ?></a>
                    </div>
                </li>
            <?php } ?>

            <?php if (PackHelper::hasPack($user, 'vipvip')) { ?>
                <li>
                    <div class="m-t m-l">
                        <a href="http://app.vipvip.com/simple-login/<?=UrlHelper::getVipVipAuthURI($user)?>" class="dropdown-toggle btn btn-xs <?= $color ?>" title="<?= THelper::t('enter_to_vipvip') ?>"><?= THelper::t('enter_to_vipvip') ?></a>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user">
            <li class="hidden-xs">
                <div style="padding-top: 7px;"><?= LangswitchWidget::widget() ?></div>
            </li>
            <li class="hidden-xs">
                <?= NotificationWidget::widget() ?>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="thumb-sm avatar pull-left">
                        <?php if (Yii::$app->view->params['user']->avatar) : ?>
                        <img src="<?= Yii::$app->view->params['user']->avatar ?>" />
                        <?php else : ?>
                            <img src="/images/avatar_default.png"/>
                        <?php endif; ?>
                    </span>
                    <?= Yii::$app->view->params['user']->firstName ?> <?= Yii::$app->view->params['user']->secondName ?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInRight">
                    <span class="arrow top"></span>
                    <li>
                        <?= Html::a(THelper::t('profile'), ['/business/setting/profile']) ?>
                    </li>
                    <li>
                        <?= Html::a(THelper::t('finance'), ['/business/finance']) ?>
                    </li>
                    <li>
                        <?= Html::a(THelper::t('all_resources'), ['/business/resource']) ?>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <?= Html::a(THelper::t('exit'), ['/login/logout']) ?>
                    </li>
                </ul>
            </li>
        </ul>
    </header>
    <section>
        <section class="hbox stretch">
            <aside class="bg-<?= Yii::$app->view->params['q'] ?> lter b-r aside-md hidden-print hidden-xs" id="nav">
                <section class="vbox">
                    <header class="header bg-blue-one lter text-center clearfix">
                        <div class="btn-group">
                            <?= \app\components\AddCellWidget::widget() ?>
                            <div class="btn-group hidden-nav-xs">
                                <?php if (Yii::$app->view->params['list']) { ?>
                                    <button type="button" class="btn btn-sm btn-blue-three dropdown-toggle" data-toggle="dropdown">
                                        <?= Yii::$app->view->params['user']->username ?>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu text-left">
                                        <?php foreach(Yii::$app->view->params['list'] as $l) { ?>
                                            <li>
                                                <?= Html::a($l->username, ['/business/default/relogin', 'email' => $l->email], ['title' => $l->username]) ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-sm btn-blue-three dropdown-toggle">
                                        <?= Yii::$app->view->params['user']->username ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </header>
                    <section id="menu_primary" class="w-f scrollable">
                        <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                            <?php require_once('sidebar.php'); ?>
                        </div>
                    </section>
                    <footer id="our-groups" class="footer lt hidden-xs b-t b-<?= Yii::$app->view->params['q'] ?>">
                        <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-<?= Yii::$app->view->params['q'] ?> btn-icon">
                            <i class="fa fa-angle-left text"></i>
                            <i class="fa fa-angle-right text-active"></i>
                        </a>
                        <div class="btn-group hidden-nav-xs">

                            <?php if (Yii::$app->view->params['links']) {
                                if (Yii::$app->view->params['links']->vk) { ?>
                                    <a target="_blank" href="<?= Url::to(Yii::$app->view->params['links']->vk, true) ?>" class="btn btn-icon btn-sm btn-<?= Yii::$app->view->params['q'] . ' ' . Yii::$app->view->params['u'] ?>"><i class="fa fa-vk"></i></a>
                                <?php }
                                if (Yii::$app->view->params['links']->fb) { ?>
                                    <a target="_blank" href="<?= Url::to(Yii::$app->view->params['links']->fb, true) ?>" class="btn btn-icon btn-sm btn-<?= Yii::$app->view->params['q'] . ' ' . Yii::$app->view->params['u'] ?>"><i class="fa fa-facebook"></i></a>
                                <?php }
                                if (Yii::$app->view->params['links']->youtube) { ?>
                                    <a target="_blank" href="<?= Url::to(Yii::$app->view->params['links']->youtube, true) ?>" class="btn btn-icon btn-sm btn-<?= Yii::$app->view->params['q'] . ' ' . Yii::$app->view->params['u'] ?>"><i class="fa fa-youtube-square"></i></a>
                                <?php }
                                if (Yii::$app->view->params['links']->instagram) { ?>
                                    <a target="_blank" href="<?= Url::to(Yii::$app->view->params['links']->instagram, true) ?>" class="btn btn-icon btn-sm btn-<?= Yii::$app->view->params['q'] . ' ' . Yii::$app->view->params['u'] ?>"><i class="fa fa-instagram"></i></a>
                                <?php }
                            } ?>
                        </div>
                    </footer>
                </section>
            </aside>
            <!-- /.aside -->
            <section id="content">
                <section class="vbox">

                    <section class="scrollable padder">
                        <?php
                        echo Breadcrumbs::widget([
                            'options' => ['class' => 'breadcrumb no-border no-radius b-b b-light pull-in'],
                            'homeLink' => [
                                'label' => THelper::t('home'),
                                'url' => '/business',
                                'template' => "<li><i class='fa fa-home'></i> {link}</li>\n"
                            ],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [
                                '',
                            ],

                        ]);
                        ?>

                        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) { ?>
                            <div class="alert alert-<?= $key ?>">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?= $message ?>
                            </div>
                        <?php } ?>

                        <?= $content ?>

                    </section>


                </section>
                <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
            </section>
            <aside class="bg-<?= Yii::$app->view->params['q'] ?> lter b-l aside-md hide" id="notes"> <div class="wrapper"><?=THelper::t('notification')?></div> </aside>
        </section>
    </section>
</section>
<script type="text/javascript">
    $('body').on('click', '.change-layout', function() {
        $.ajax({
            url: '/business/setting/change-layout',
            method: 'get',
            success: function(data) {
                if (data.success) {
                    location.reload();
                }
            }
        });
    });
</script>
<?php $this->endBody() ?>
    </body>
    <script>
        +function ($) {
            $(function(){
                var intro = introJs();

                intro.setOptions({
                    steps: [
                        {
                            element: '.dropdown',
                            intro: '<p class="h4 text-uc"><strong>1: <?=THelper::t('tour_your_name')?></strong></p><p><?=THelper::t('tour_your_name_description')?></p>',
                            position: 'left'
                        },
                        {
                            element: '#notifications',
                            intro: '<p class="h4 text-uc"><strong>2: <?=THelper::t('tour_notifications')?></strong></p><p><?=THelper::t('tour_notifications_description')?></p>',
                            position: 'bottom'
                        },
                        {
                            element: '.language_select',
                            intro: '<p class="h4 text-uc"><strong>3: <?=THelper::t('tour_multilingual')?></strong></p><p><?=THelper::t('tour_multilingual_description')?></p>',
                            position: 'bottom'
                        },
                        {
                            element: '.show-instruction',
                            intro: '<p class="h4 text-uc"><strong>4: <?=THelper::t('tour_instruction')?></strong></p><p><?=THelper::t('tour_instruction_description')?></p>',
                            position: 'bottom'
                        },
                        {
                            element: '.change-layout',
                            intro: '<p class="h4 text-uc"><strong>4: <?=THelper::t('tour_theme')?></strong></p><p><?=THelper::t('tour_theme_description')?></p>',
                            position: 'bottom'
                        },
                        {
                            element: '#username',
                            intro: '<p class="h4 text-uc"><strong>5: <?=THelper::t('tour_username')?></strong></p><p><?=THelper::t('tour_username_description')?></p>',
                            position: 'bottom'
                        },
                        {
                            element: '#add-cell',
                            intro: '<p class="h4 text-uc"><strong>6: <?=THelper::t('tour_add_cell')?></strong></p><p><?=THelper::t('tour_add_cell_description')?></p>',
                            position: 'right'
                        },
                        {
                            element: '.nav-primary',
                            intro: '<p class="h4 text-uc"><strong>7: <?=THelper::t('tour_main_menu')?></strong></p><p><?=THelper::t('tour_main_menu_description')?></p>',
                            position: 'right'
                        },
                        {
                            element: '#notes',
                            intro: '<p class="h4 text-uc"><strong>8: <?=THelper::t('tour_notes')?></strong></p><p><?=THelper::t('tour_notes_description')?></p>',
                            position: 'right'
                        },
                        {
                            element: '#our-groups',
                            intro: '<p class="h4 text-uc"><strong>9: <?=THelper::t('tour_our_groups')?></strong></p><p><?=THelper::t('tour_our_groups_description')?></p>',
                            position: 'top'
                        }
                    ],
                    showBullets: true,
                    nextLabel: '<?=THelper::t('next')?> &rarr;',
                    prevLabel: '&larr; <?=THelper::t('back')?>',
                    skipLabel: '<?=THelper::t('skip')?>',
                    doneLabel: '<?=THelper::t('done')?>'
                });

                if (! getcookie('flag') && (window.innerWidth >= 768)) {
                    intro.start();
                    setcookie('flag', true);
                }

                if (window.innerWidth < 768) {
                    $('#tour').hide();
                } else {
                    $('#tour').show();
                }

                $('#tour').click(function() {
                    if (window.innerWidth >= 768) {
                        intro.start();
                        setcookie('flag', true);
                    }
                });
            });

            function setcookie(a,b) {
                if (a && b) {
                    var date = new Date(new Date().getTime() + 10 * 365 * 24 * 3600 * 1000);
                    document.cookie = a + '=' + b + '; path=/; expires=' + date.toUTCString();
                } else {
                    return false;
                }
            }

            function getcookie(a) {
                var b = new RegExp(a+'=([^;]){1,}');
                var c = b.exec(document.cookie);
                if(c) c = c[0].split('=');
                else return false;
                return c[1] ? c[1] : false;
            }
        }(jQuery);

        var months = {
            "01":"<?=tHelper::t('january')?>",
            "02":"<?=tHelper::t('february')?>",
            "03":"<?=tHelper::t('march')?>",
            "04":"<?=tHelper::t('april')?>",
            "05":"<?=tHelper::t('may')?>",
            "06":"<?=tHelper::t('june')?>",
            "07":"<?=tHelper::t('july')?>",
            "08":"<?=tHelper::t('august')?>",
            "09":"<?=tHelper::t('september')?>",
            "10":"<?=tHelper::t('october')?>",
            "11":"<?=tHelper::t('november')?>",
            "12":"<?=tHelper::t('december')?>"
        }
    </script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter45268404 = new Ya.Metrika({
                        id:45268404,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/45268404" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
<?php
    $session = Yii::$app->session;
    $user = Yii::$app->view->params['user'];

    if (isset($user->sponsor)) {
        $parent = api\User::get($user->sponsor->accountId);
    } else {
        $parent = $user;
    }

?>


<?php if (
        !$session->has('disable_greetings') &&
        (empty($user->statistics->dateBuyPack) ||
        !DateHelper::validateISO8601Date($user->statistics->dateBuyPack))
    ):
    ?>
    <?php
        if(! $session->isActive) {
            $session->open();
        }

        $session->set('disable_greetings', true);

        echo $this->render('@app/views/modals/initial_user_popup', compact('parent'));
    ?>

    <script>
        $('#greetings').modal();
    </script>
<?php endif; ?>

</html>
<?php $this->endPage() ?>

