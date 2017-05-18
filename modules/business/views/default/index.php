<?php
    use app\components\THelper;
    use app\components\UrlHelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('main_title'); ?></h3>
</div>
<?php /*
<div class="row">
    <div class="col-md-12">

    </div>
</div>
<div class="row m-b">
    <div class="col-md-9">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-info" style="color: #4c6cc1"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong></strong>111</span>
                    <small class="text-muted text-uc"><?= THelper::t('main_new_partners') ?></small>
                </div>
            </div>
            <div class="col-sm-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-info" style="color: #4cc0c1"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong>111</strong></span>
                    <small class="text-muted text-uc"><?= THelper::t('main_new_partners_paid') ?></small>
                </div>
            </div>
            <div class="col-sm-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-info" style="color: #c14cba"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong>111</strong></span>
                    <small class="text-muted text-uc"><?= THelper::t('main_partners_excluded') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <section class="panel panel-default m-b-20">
            <header class="panel-heading font-bold"><?=THelper::t('main_partners_graph_1')?></header>
            <div class="panel-body">
                <div id="flot-chart" style="text-align: center;">
                    <i class="fa fa-5x fa-spinner fa-spin"></i>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 padder-v b-r b-light">
            <span class="fa-stack fa-2x pull-left m-r-sm">
                <i class="fa fa-circle fa-stack-2x text-info" style="color: #73c11d"></i>
                <i class="fa fa-male fa-stack-1x text-white"></i>
            </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong></strong>111</span>
                    <small class="text-muted text-uc"><?= THelper::t('main_new_partners') ?></small>
                </div>
            </div>
            <div class="col-sm-4 padder-v b-r b-light">
            <span class="fa-stack fa-2x pull-left m-r-sm">
                <i class="fa fa-circle fa-stack-2x text-info" style="color: #c1005b"></i>
                <i class="fa fa-male fa-stack-1x text-white"></i>
            </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong>111</strong></span>
                    <small class="text-muted text-uc"><?= THelper::t('main_new_partners_paid') ?></small>
                </div>
            </div>
            <div class="col-sm-4 padder-v b-r b-light">
            <span class="fa-stack fa-2x pull-left m-r-sm">
                <i class="fa fa-circle fa-stack-2x text-info" style="color: #00c150"></i>
                <i class="fa fa-male fa-stack-1x text-white"></i>
            </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong>111</strong></span>
                    <small class="text-muted text-uc"><?= THelper::t('main_partners_excluded') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        график 2
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <div class="row m-l-none m-r-none">
            <div class="m-r col-sm-4 padder-v b-r b-b b-l b-t b-light bg-light lter">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-info" style="color: #c1ba04"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong></strong>111</span>
                    <small class="text-muted text-uc"><?= THelper::t('main_new_partners') ?></small>
                </div>
            </div>
            <div class="col-sm-4 padder-v b-r b-b b-l b-t b-light bg-light lter">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-info" style="color: #1308c1"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong>111</strong></span>
                    <small class="text-muted text-uc"><?= THelper::t('main_new_partners_paid') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        график 3
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-info" style="color: #c18051"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong></strong>111</span>
                    <small class="text-muted text-uc"><?= THelper::t('main_new_partners') ?></small>
                </div>
            </div>
            <div class="col-sm-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-info" style="color: #b431c1"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong>111</strong></span>
                    <small class="text-muted text-uc"><?= THelper::t('main_new_partners_paid') ?></small>
                </div>
            </div>
            <div class="col-sm-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-info" style="color: #286fc1"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <div class="clear">
                    <span class="h3 block m-t-xs"><strong>111</strong></span>
                    <small class="text-muted text-uc"><?= THelper::t('main_partners_excluded') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        график 4
    </div>
</div>
 */ ?>
<div class="row">
    <div class="col-md-4">
        <ul class="list-group no-radius">
            <li class="list-group-item">
                <span class="pull-right"><?= $user->statistics->personalIncome ?></span>
                <span class="label bg-primary">1</span>
                <?=THelper::t('personal_award')?>
            </li>
            <li class="list-group-item">
                <span class="pull-right"><?= $user->statistics->structIncome ?></span>
                <span class="label bg-dark">2</span>
                <?=THelper::t('team_award')?>
            </li>
            <li class="list-group-item">
                <span class="pull-right"><?= $user->statistics->mentorBonus ?></span>
                <span class="label bg-77382E">3</span>
                <?=THelper::t('mentor_bonus')?>
            </li>
            <li class="list-group-item">
                <span class="pull-right"><?= $user->statistics->careerBonus ?></span>
                <span class="label bg-009A8C">4</span>
                <?=THelper::t('career_bonus')?>
            </li>
            <li class="list-group-item">
                <span class="pull-right"><?= $user->statistics->executiveBonus ?></span>
                <span class="label bg-AAA100">5</span>
                <?=THelper::t('executive_bonus')?>
            </li>
            <li class="list-group-item">
                <span class="pull-right"><?= $user->statistics->worldBonus ?></span>
                <span class="label bg-AA0900">6</span>
                <?=THelper::t('world_bonus')?>
            </li>
        </ul>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-6 bg-664CC1">
                <div class="padder-v">
                    <span class="m-b-xs h3 block text-white"><?= $user->statistics->autoBonus ?></span>
                    <small class="text-white"><?= THelper::t('main_auto_bonus') ?></small>
                </div>
            </div>
            <div class="col-md-6 bg-593FB5">
                <div class="padder-v">
                    <span class="m-b-xs h3 block text-white"><?= $user->statistics->propertyBonus ?></span>
                    <small class="text-white"><?= THelper::t('main_property_bonus') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /*
<div class="row">
    <div class="col-md-4">
        <section class="panel panel-default m-b-20">
            <div class="text-center wrapper bg-light lt">
                <div class="sparkline inline" data-type="pie" data-height="165" data-slice-colors="['#77c587','#41586e']">
                    <?= $user->statistics->personalIncome ?>, <?= $user->statistics->structIncome ?>
                </div>
            </div>
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->personalIncome ?></span>
                    <span class="label bg-primary">1</span>
                    <?=THelper::t('personal_award')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->structIncome ?></span>
                    <span class="label bg-dark">2</span>
                    <?=THelper::t('team_award')?>
                </li>
            </ul>
        </section>
    </div>
    <div class="col-md-4">
        <section class="panel panel-default m-b-20">
            <div class="text-center wrapper bg-light lt">
                <div class="sparkline inline" data-type="pie" data-height="165" data-slice-colors="['#77c587','#41586e']">
                    <?= $user->statistics->personalIncome ?>, <?= $user->statistics->structIncome ?>
                </div>
            </div>
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->personalIncome ?></span>
                    <span class="label bg-primary">1</span>
                    <?=THelper::t('personal_award')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->structIncome ?></span>
                    <span class="label bg-dark">2</span>
                    <?=THelper::t('team_award')?>
                </li>
            </ul>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        таблица 1
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        таблица 2
    </div>
</div>
 */ ?>
<script>
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

    var labelPaid          = "<?= THelper::t('paid') ?>";
    var labelRegistrations = "<?= THelper::t('registrations') ?>";

//    var registrationsStatisticsPerMoths = <?//= $registrationsStatisticsPerMoths ?>//;
//
//    var graphOptions = {
//        series: {
//            lines: {
//                show: true,
//                lineWidth: 1,
//                fill: true,
//                fillColor: {
//                    colors: [{
//                        opacity: 0.2
//                    }, {
//                        opacity: 0.1
//                    }]
//                }
//            },
//            points: {
//                show: true
//            },
//            shadowSize: 2
//        },
//        grid: {
//            hoverable: true,
//            clickable: true,
//            tickColor: "#f0f0f0",
//            borderWidth: 0
//        },
//        colors: ["#dddddd", "#89cb4e"],
//        xaxis: {
//            ticks: null
//        },
//        yaxis: {
//            ticks: 10,
//            tickDecimals: 0,
//            min: 0
//        },
//        tooltip: true,
//        tooltipOpts: {
//            content: "%y.4 %s",
//            defaultTheme: false,
//            shifts: {
//                x: 0,
//                y: 20
//            }
//        }
//    };

    var floatChart = $('#flot-chart');

    (function() {
        var data = registrationsStatisticsPerMoths;
        floatChart.height(240);
        var d2 = [];
        for (var i = 0; i < data.length; i++) {
            d2.push([i, parseInt(data[i]['paid'])]);
        }
        var d3 = [];
        for (var i = 0; i < data.length; i++) {
            d3.push([i, parseInt(data[i]['registrations'])]);
        }
        var dates = [];
        for (var i = 0; i < data.length; i++) {
            var date_splited = data[i]['date'].split('/');
            var date;
            if (window.innerWidth >= 768) {
                date = months[date_splited[0]];
            } else {
                date = date_splited[0];
            }
            dates.push([i, date]);
        }
        graphOptions.xaxis.ticks = dates;
        floatChart.length && $.plot(floatChart, [{
                data: d2,
                label: labelPaid
            }, {
                data: d3,
                label: labelRegistrations
            }],
            graphOptions
        );
    })();
</script>
<?php /**
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="clearfix text-center m-t">
                        <div class="inline">
                            <div style="width: 160px; height: 130px; line-height: 130px;" class="easypiechart easyPieChart" data-percent="75" data-line-width="5" data-bar-color="#4cc0c1" data-track-color="#f5f5f5" data-scale-color="false" data-size="130" data-line-cap="butt" data-animate="1000">
                                <div class="thumb-lg">
                                    <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png'; ?>" class="img-circle" width="128" height="128">
                                </div>
                                <canvas width="130" height="130"></canvas>
                            </div>
                        </div>
                        <div class="h4 m-t m-b-xs"><?= $user->firstName?> <?= $user->secondName ?></div>
                        <small class="text-muted m-b"><?=THelper::t('status')?>: <?= THelper::t('rank_'.$user->rank) ?></small>
                    </div>
                </div>
                <footer class="panel-footer bg-info text-center">
                    <div class="row pull-out">
                        <div class="col-xs-4">
                            <div class="padder-v">
                                <?php if ($user->firstPurchase > 0){
                                    $firstPurchase = date_diff(date_create(date('d-m-Y H:i:s', $user->firstPurchase)), date_create())->days;
                                } else {
                                    $firstPurchase = 0;
                                } ?>
                                <span class="m-b-xs h3 block text-white"><?= $firstPurchase ?></span>
                                <small class="text-muted"><?=THelper::t('days_in_the_business')?><!--Дней в бизнесе--></small>
                            </div>
                        </div>
                        <div class="col-xs-4 dk">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= $user->rightSideNumberUsers + $user->leftSideNumberUsers ?></span>
                                <small class="text-muted"><?=THelper::t('registrations_in_the_structure')?><!--Регистраций в структуре--></small>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= $user->statistics->partnersWithPurchases ?></span>
                                <small class="text-muted"><?=THelper::t('default_index_partners')?></small>
                            </div>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="panel panel-default">
                <div class="text-center wrapper bg-light lt">
                    <div class=" inline" style="height: 165px; width: 165px;">
                        <?php if($user->avatar) :?>
                              <img src="/images/ranks/rank_<?=$user->rank?>.png"  style="height: 165px; width: 165px;">
                        <?php else :?>
                              <img src="/images/ranks/rank_<?=$user->rank?>.png"  style="height: 165px; width: 165px;">
                        <?php endif ?>
                    </div>
                </div>
                <ul class="list-group no-radius">
                    <li class="list-group-item">
                        <span class="label bg-info">1</span> <?=THelper::t('status')?>: <?= THelper::t('rank_'.$user->rank) ?>
                    </li>
                    <li class="list-group-item">
                        <span class="label bg-info">2</span> <?=THelper::t('login')?>: <?= $user->username ?>
                    </li>
                    <?php if ($user->created) { ?>
                        <li class="list-group-item">
                            <span class="label bg-info">3</span> <?=THelper::t('registration_date')?>: <?= gmdate('d.m.Y', $user->created) ?>
                        </li>
                    <?php } ?>
                </ul>
            </section>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('conditions_of_participation_in_business')?></span>
                </header>
                <div style="position: relative; overflow: hidden; width: auto; height: 100px;" class="slimScrollDiv">
                    <section style="overflow: hidden; width: auto; height: 100px;" class="panel-body slim-scroll">
                        <article class="media">
                            <div class="media-body">
                                <?= THelper::t('business_support') . ': '; ?>
                                <?= ($user->expirationDateBS && $user->expirationDateBS > 0) ? (THelper::t('expiration_date_bs') . ' ' . gmdate('d.m.Y', $user->expirationDateBS)) : '-'; ?><br />
                                <?php if (! $user->autoExtensionBS) { ?>
                                    <?= THelper::t('automatic_extension_of_business_support')?>: <span class="text-color-red"><?= THelper::t('disable') ?></span><br/>
                                <?php } else { ?>
                                    <?= THelper::t('automatic_extension_of_business_support')?>: <span class="text-color-green"><?= THelper::t('enable') ?></span><br/>
                                <?php } ?>
                                <?php if (! $user->personalBonus) { ?>
                                    <?= THelper::t('personal_award_in_the_personal_account')?>: <span class="text-color-red"><?= THelper::t('not_charge') ?></span><br/>
                                <?php } else { ?>
                                    <?= THelper::t('personal_award_in_the_personal_account')?>: <span class="text-color-green"><?= THelper::t('charge') ?></span><br/>
                                <?php } ?>
                                <?php if ($product) { ?>
                                    <?= Thelper::t('business_product'); ?>: <span><?= $product->productName ?></span>
                                <?php } ?>
                            </div>
                        </article>
                    </section>
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('structural_award')?></span>
                </header>
                <div style="position: relative; overflow: hidden; width: auto; height: 100px;" class="slimScrollDiv">
                    <section style="overflow: hidden; width: auto; height: 100px;" class="panel-body slim-scroll">
                        <article class="media">
                            <div class="media-body">
                                <?php if (! $user->qualification) { ?>
                                    <?= THelper::t('personal_skills') ?>: <span class="text-color-red"><?= THelper::t('not_done') ?></span><br />
                                <?php } else { ?>
                                    <?= THelper::t('personal_skills') ?>: <span class="text-color-green"><?= THelper::t('done') ?></span><br />
                                <?php } ?>
                                <?php if (! $user->structBonus) { ?>
                                    <?= THelper::t('structural_award')?>: <span class="text-color-red"><?= THelper::t('not_charge') ?></span>
                                <?php } else { ?>
                                    <?= THelper::t('structural_award')?>: <span class="text-color-green"><?= THelper::t('charge') ?></span>
                                <?php } ?>
                            </div>
                        </article>
                    </section>
                </div>
            </section>
        </div>
    </div>
</div>
<?php if ($promoShow) { ?>
<div class="col-lg-12">
    <section class="panel panel-default">
        <header class="panel-heading">
            <span class="h4"><?= THelper::t('promo_sri_lanka_title') ?></span>
        </header>
        <div class="slimScrollDiv">
            <section class="panel-body slim-scroll">
                <article class="media">
                    <div class="media-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="row">
                                    <div class="col-lg-12 text-center m-b">
                                        <?= Html::dropDownList('number', null, ['1' => THelper::t('promo_sri_lanka_select_one'), '2' => THelper::t('promo_sri_lanka_select_two')], ['class' => 'form-control', 'id' => 'promo-sri-lanka-select']) ?>
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <div class="thumb-lg">
                                            <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png'; ?>" class="img-circle" width="128" height="128">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-lg-12 m-b">
                                        <div class="row">
                                            <div class="col-lg-3 text-center text-danger" style="overflow: hidden; padding-top: 40px;">
                                                <span style="font-size: 16px; font-weight: bold;"><?= THelper::t('promo_sri_lanka_your_price') ?>:</span>
                                                <div class="clearfix"></div>
                                                <span id="promo-sri-lanka-your-price" data-price1="<?= $promoYourPriceOne ?>" data-price2="<?= $promoYourPriceTwo ?>" style="font-size: 40px;"><?= $promoYourPriceOne ?></span>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12 text-center">
                                                        <a href="https://youtu.be/VFo1v3kYMYU" target="_blank"><img src="/images/sri_lanka_player.png" class="img-responsive2"></a>
                                                    </div>
                                                    <div class="col-lg-12 text-center text-danger m-b" style="font-size: 18px; font-weight: bold;">
                                                        <?= THelper::t('promo_sri_lanka_go_by_company') ?>: <?= $qtyCompleteProm ?> / 25
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 text-center text-danger" style="overflow: hidden; padding-top: 40px;">
                                                <span style="font-size: 16px; font-weight: bold;"><?= THelper::t('promo_sri_lanka_travel_price') ?>:</span>
                                                <div class="clearfix"></div>
                                                <span id="promo-sri-lanka-travel-price" data-price1="<?= $promoPriceOne ?>" data-price2="<?= $promoPriceTwo ?>" style="font-size: 40px;"><?= $promoPriceOne ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <div class="progress progress-striped active" style="height: 10px;">
                                            <div class="progress-bar progress-bar-success" role="progressbar" id="promo-sri-lanka-progressbar" data-progress1="<?= $promoProgressOne ?>" data-progress2="<?= $promoProgressTwo ?>" aria-valuenow="<?= $promoProgressOne ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $promoProgressOne ?>%">
                                                <span class="sr-only"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 text-center">
                                <img src="/images/sri_lanka_img2.png" class="img-responsive2">
                            </div>
                        </div>
                    </div>
                </article>
            </section>
        </div>
    </section>
</div>
<?php } ?>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?=THelper::t('schedule_of_structure')?></header>
                <div class="panel-body">
                    <div id="flot-chart" style="text-align: center;">
                        <i class="fa fa-5x fa-spinner fa-spin"></i>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            <section class="panel panel-info">
                <div class="panel-body">
                    <div  class="thumb pull-right m-l">
                        <?php
                        if($parent->rank == 0){
                            $rank1 = THelper::t('undefined');
                        } else {
                            $rank1 = THelper::t('finish');
                        }
                        ?>
                        <?php if($parent->avatar) :?>
                            <img src="<?= $parent->avatar ?>">
                        <?php else :?>
                            <img src="/images/avatar_default.png"/>
                        <?php endif; ?>
                    </div>
                    <div class="clear">
                        <?php if ($parent->firstName && $parent->secondName) { ?>
                            <?= THelper::t('your_mentor') ?>: <span style="color: #4cc3d2"><?= $parent->firstName ?> <?= $parent->secondName ?></span><br>
                        <?php } ?>
                        <?php if ($parent->username) { ?>
                            <?= THelper::t('login') ?>: <span style="color: #4cc3d2"><?= $parent->username ?></span><br>
                        <?php } ?>
                        <?php if ($parent->phoneNumber) { ?>
                            <?= THelper::t('phone') ?>: <span style="color: #4cc3d2"><?= $parent->phoneNumber ?></span><br>
                        <?php } ?>
                        <?php if ($parent->email) { ?>
                            <?= THelper::t('email') ?>: <span style="color: #4cc3d2"><?= $parent->email ?></span><br>
                        <?php } ?>
                        <?php if ($parent->skype) { ?>
                            <?= THelper::t('skype') ?>: <span style="color: #4cc3d2"><?= $parent->skype ?></span><br>
                        <?php } ?>
                        <?=THelper::t('status') ?>: <span style="color: #4cc3d2"><?= THelper::t('rank_' . $parent->rank) ?></span><br>
                        <?php if ($parent->links->site) { ?>
                            <?= THelper::t('website_blog') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->site) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                        <?php if ($parent->links->odnoklassniki) { ?>
                            <?= THelper::t('page_odnoklassniki') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->odnoklassniki) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                        <?php if ($parent->links->vk) { ?>
                            <?= THelper::t('page_vkontakte') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->vk) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                        <?php if ($parent->links->fb) { ?>
                            <?= THelper::t('page_facebook') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->fb) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                        <?php if ($parent->links->youtube) { ?>
                            <?= THelper::t('youtube_channel') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->youtube) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('your_affiliate_links')?><!--Ваши партнерские ссылки--></span>
                </header>
                <div class="panel-body">
                    <p><?=THelper::t('link_to_register')?>: <span id="toclip"><?= $linkToRegister ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip" id="copy-link-wrap"><?=THelper::t('copy')?></a></p>
                    <?php if ($links->site) { ?>
                        <p><?=THelper::t('main_site')?>: <span id="toclip3"><?= $links->site . '?ref=' . $user->username ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip3" id="copy-link-wrap3"><?=THelper::t('copy')?></a></p>
                    <?php } ?>
                    <?php if ($links->market) { ?>
                        <p><?=THelper::t('link_to_shop')?>: <span id="toclip2"><?= $links->market ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip2" id="copy-link-wrap2"><?=THelper::t('copy')?></a></p>
                    <?php } ?>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
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

    var labelPaid          = "<?= tHelper::t('paid') ?>";
    var labelRegistrations = "<?= tHelper::t('registrations') ?>";
    var linkCopiedToClipboardText = "<?= tHelper::t('link_copied_to_clipboard') ?>";

    var registrationsStatisticsPerMoths = <?= $registrationsStatisticsPerMoths ?>

    jQuery(document).ready(function() {
        (function(){
            if ($('#copy-link-wrap')) {
                new Clipboard('#copy-link-wrap');
            }
            if ($('#copy-link-wrap3')) {
                new Clipboard('#copy-link-wrap3');
            }
            if ($('#copy-link-wrap2')) {
                new Clipboard('#copy-link-wrap2');
            }
        })();
    });

    $('#promo-sri-lanka-select').change(function() {
        var value = $(this).val();

        var promoSriLankaYourPrice = $('#promo-sri-lanka-your-price');
        var yourPrice = promoSriLankaYourPrice.data('price' + value);
        promoSriLankaYourPrice.html(yourPrice);

        var promoSriLankaTravelPrice = $('#promo-sri-lanka-travel-price');
        var price = promoSriLankaTravelPrice.data('price' + value);
        promoSriLankaTravelPrice.html(price);

        var promoSriLankaProgressbar = $('#promo-sri-lanka-progressbar');
        var progress = promoSriLankaProgressbar.data('progress' + value);
        promoSriLankaProgressbar.attr('aria-valuenow', progress);
        promoSriLankaProgressbar.css('width', progress + '%');
    });
</script>

<?php $this->registerJsFile('js/main/flot_graph.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('/js/clipboard.min.js'); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); **/ ?>