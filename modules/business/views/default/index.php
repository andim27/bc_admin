<?php

use app\components\THelper;
use app\components\UrlHelper;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use app\models\Users;

$hideStatistic = 0;

$layoutDate = <<< HTML
    <span class="input-group-addon">c</span>
    {input1}
    {separator}
    {input2}
    <a class="input-group-addon" href="javascript:$('.formStatistic').submit();">
        Применить
    </a>
HTML;
?>
    <div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('main_title'); ?></h3>
    </div>

    <?php if(Users::checkRule('show_statistic','sidebar_home') === true){?>
    <section class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="POST" class="formStatistic">
                    <?= DatePicker::widget([
                        'name' => 'from',
                        'value' => $statisticInfo['request']['from'],
                        'type' => DatePicker::TYPE_RANGE,
                        'name2' => 'to',
                        'value2' => $statisticInfo['request']['to'],
                        'separator' => 'по',
                        'layout' => $layoutDate,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'startView'=>'year',
                            'minViewMode'=>'months',
                        ]
                    ]); ?>
                </form>
            </div>
        </div>
    </section>


    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-4c6cc1"></i>
                    <i class="fa fa-users fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=$statisticInfo['newRegistration'];?></strong></span>
                    <small class="text-muted text-uc capsLock">Новых партнеров за период</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-4cc0c1"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=$statisticInfo['ofThemPaid'];?></strong></span>
                    <small class="text-muted text-uc capsLock">Из низ оплаченных</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-c14cba"></i>
                    <i class="fa fa-sign-out fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong>0</strong></span>
                    <small class="text-muted text-uc capsLock">Исключенно</small>
                </a>
            </div>
        </div>
    </section>

    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            График подключения партнеров по заданному времени / из них проплаченных
        </header>
        <div class="panel-body">
            <div id="flot-connect-partners" class="height250"></div>
        </div>
    </section>
    <script type="text/javascript">
        var labelPaid = "<?= THelper::t('paid') ?>";
        var labelRegistrations = "<?= THelper::t('registrations') ?>";

        var arrayConnectPartners = <?=json_encode(array_values($statisticInfo['newRegistrationForMonth']))?>;

        var arrayPaidPartners =  <?=json_encode(array_values($statisticInfo['ofThemPaidForMonth']))?>;

        var dateLabel = <?=json_encode($statisticInfo['dateInterval'])?>;

        $("#flot-connect-partners").length && $.plot($("#flot-connect-partners"), [
                {
                    data: arrayPaidPartners,
                    label: labelPaid
                },{
                data: arrayConnectPartners,
                label: labelRegistrations
                }
            ],
            {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.2
                            }, {
                                opacity: 0.1
                            }]
                        }
                    },
                    points: {
                        show: true
                    },
                    shadowSize: 2
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#f0f0f0",
                    borderWidth: 0
                },
                colors: ["#dddddd","#89cb4e"],
                xaxis: {
                    ticks:dateLabel
                },
                yaxis: {
                    ticks: 10,
                    tickDecimals: 0
                },
                tooltip: true,
                tooltipOpts: {
                    content: "'%s' - %y.4 чел",
                    defaultTheme: false,
                    shifts: {
                        x: 0,
                        y: 20
                    }
                }
            }
        );
    </script>

    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Общий приход</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                    <i class="fa fa-money fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round(($statisticInfo['generalReceiptMoney']-$statisticInfo['receiptVoucher'])), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Приход деньгами</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-file-text-o fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptVoucher']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Приход ваучерами</small>
                </a>
            </div>
        </div>
    </section>

    <!-- приход по проекту Wellness -->
    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney_Wellness']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Общий приход по проекту Wellness</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                    <i class="fa fa-money fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round(($statisticInfo['generalReceiptMoney_Wellness']-$statisticInfo['receiptVoucher_Wellness'])), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Приход деньгами по проекту Wellness</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-file-text-o fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptVoucher_Wellness']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Приход ваучерами по проекту Wellness</small>
                </a>
            </div>
        </div>
    </section>

    <!-- приход по проекту VipVip -->
    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney_VipVip']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Общий приход по проекту VipVip</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                    <i class="fa fa-money fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round(($statisticInfo['generalReceiptMoney_VipVip']-$statisticInfo['receiptVoucher_VipVip'])), 0, ',', ' ');?>  <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Приход деньгами по проекту VipVip</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-file-text-o fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptVoucher_VipVip']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Приход ваучерами по проекту VipVip</small>
                </a>
            </div>
        </div>
    </section>

    <!-- приход по проекту BPT -->
    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney_VipCoin']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Общий приход по проекту VipCoin</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                    <i class="fa fa-money fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round(($statisticInfo['generalReceiptMoney_VipCoin']-$statisticInfo['receiptVoucher_VipCoin'])), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Приход деньгами по проекту VipCoin</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-file-text-o fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptVoucher_VipCoin']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Приход ваучерами по проекту VipCoin</small>
                </a>
            </div>
        </div>
    </section>

    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            График прибылей по заданному критерию
        </header>
        <div class="panel-body">
            <div id="flot-profit" class="height250"></div>
        </div>
    </section>
    <script type="text/javascript">
        var arrayProfit = <?=json_encode(array_values($statisticInfo['generalReceiptMoneyMonth']))?>;

        $("#flot-profit").length && $.plot($("#flot-profit"), [{
                data: arrayProfit
            }],
            {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.2
                            }, {
                                opacity: 0.1
                            }]
                        }
                    },
                    points: {
                        show: true
                    },
                    shadowSize: 2
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#f0f0f0",
                    borderWidth: 0
                },
                colors: ["#65bc76"],
                xaxis: {
                    ticks:dateLabel
                },
                yaxis: {
                    ticks: 10,
                    tickDecimals: 0
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%y.4 euro",
                    defaultTheme: false,
                    shifts: {
                        x: 0,
                        y: 20
                    }
                }
            }
        );
    </script>


    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['onPersonalAccounts']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">На лицевых счетах</small>
                </a>
            </div>

            <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['orderedForWithdrawal']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Заказано на вывод</small>
                </a>
            </div>
        </div>
    </section>


<!--    <section class="panel panel-default">-->
<!--        <header class="panel-heading font-bold">-->
<!--            График остатков на лицевых счетах за заданный период !!!!!!!!!!!!!!!-->
<!--        </header>-->
<!--        <div class="panel-body">-->
<!--            <div id="flot-balances-personal-accounts" class="height250"></div>-->
<!--        </div>-->
<!--    </section>-->
<!--    <script type="text/javascript">-->
<!---->
<!--        var arrayBalancesPersonalAccounts = [-->
<!--            [0, 2.4],-->
<!--            [1, 3.4 ],-->
<!--            [2, 4.5 ]-->
<!---->
<!--        ];-->
<!--        var arrayOrderedForWithdrawal = [-->
<!--            [0, 1.5],-->
<!--            [1, 1 ],-->
<!--            [2, 2.5 ]-->
<!---->
<!--        ];-->
<!---->
<!--        $("#flot-balances-personal-accounts").length && $.plot($("#flot-balances-personal-accounts"), [{-->
<!--                data: arrayBalancesPersonalAccounts,-->
<!--                label: 'На балансе'-->
<!--            }, {-->
<!--                data: arrayOrderedForWithdrawal,-->
<!--                label: 'Заказано на вывод'-->
<!--            }],-->
<!--            {-->
<!--                series: {-->
<!--                    lines: {-->
<!--                        show: true,-->
<!--                        lineWidth: 1,-->
<!--                        fill: true,-->
<!--                        fillColor: {-->
<!--                            colors: [{-->
<!--                                opacity: 0.2-->
<!--                            }, {-->
<!--                                opacity: 0.1-->
<!--                            }]-->
<!--                        }-->
<!--                    },-->
<!--                    points: {-->
<!--                        show: true-->
<!--                    },-->
<!--                    shadowSize: 2-->
<!--                },-->
<!--                grid: {-->
<!--                    hoverable: true,-->
<!--                    clickable: true,-->
<!--                    tickColor: "#f0f0f0",-->
<!--                    borderWidth: 0-->
<!--                },-->
<!--                colors: ["#dddddd","#ff6b3f"],-->
<!--                xaxis: {-->
<!--                    ticks:dateLabel-->
<!--                },-->
<!--                yaxis: {-->
<!--                    ticks: 10,-->
<!--                    tickDecimals: 0-->
<!--                },-->
<!--                tooltip: true,-->
<!--                tooltipOpts: {-->
<!--                    content: "'%s' of %x.1 is %y.4",-->
<!--                    defaultTheme: false,-->
<!--                    shifts: {-->
<!--                        x: 0,-->
<!--                        y: 20-->
<!--                    }-->
<!--                }-->
<!--            }-->
<!--        );-->
<!--    </script>-->

    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-4c6cc1"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['feesCommission']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Начисленно комиссионных</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['issuedCommission']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Выданно комиссионных</small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round(($statisticInfo['feesCommission']-$statisticInfo['issuedCommission'])),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock">Не выдано</small>
                </a>
            </div>
        </div>
    </section>

    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            График товарооборотов / выданных комиссионных
        </header>
        <div class="panel-body">
            <div id="flot-trade-turnover" class="height250"></div>
        </div>
    </section>
    <script type="text/javascript">
        var arrayProfit = <?=json_encode(array_values($statisticInfo['generalReceiptMoneyMonth']))?>;

        var arrayIssuedCommission = <?=json_encode(array_values($statisticInfo['issuedCommissionMonth']))?>;

        $("#flot-trade-turnover").length && $.plot($("#flot-trade-turnover"), [{
                data: arrayProfit,
                label: 'Товарооборотов'
            }, {
                data: arrayIssuedCommission,
                label: 'Выданных комиссионных'
            }],
            {
                series: {
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.2
                            }, {
                                opacity: 0.1
                            }]
                        }
                    },
                    points: {
                        show: true
                    },
                    shadowSize: 2
                },
                grid: {
                    hoverable: true,
                    clickable: true,
                    tickColor: "#f0f0f0",
                    borderWidth: 0
                },
                colors: ["#dddddd","#ff6b3f"],
                xaxis: {
                    ticks:dateLabel
                },
                yaxis: {
                    ticks: 10,
                    tickDecimals: 0
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%s - %y.4 euro",
                    defaultTheme: false,
                    shifts: {
                        x: 0,
                        y: 20
                    }
                }
            }
        );
    </script>


    <section  class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-6 col-md-6 padder-v">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="flot-pie" class="height400"></div>
                    </div>

                </div>
            </div>
            <div class="col-sm-6 col-md-6 padder-v">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="flot-pie2" class="height400"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">

        var da = [
            {
                label: 'Живых денег',
                data: <?=($statisticInfo['generalReceiptMoney']-$statisticInfo['receiptVoucher'])?>
            },
            {
                label: 'Товарооборот',
                data: <?=$statisticInfo['generalReceiptMoney']?>
            }
        ];

        $("#flot-pie").length && $.plot($("#flot-pie"), da, {
            series: {
                pie: {
                    combine: {
                        color: "#999",
                        threshold: 0.05
                    },
                    show: true
                }
            },
            colors: ["#99c7ce","#999999","#bbbbbb","#dddddd","#f0f0f0"],
            legend: {
                show: false
            },
            grid: {
                hoverable: true,
                clickable: false
            },
            tooltip: true,
            tooltipOpts: {
                content: "%s: %p.0%"
            }
        });

        var da2 = [
            {
                label: 'Комиссионых',
                data: <?=$statisticInfo['feesCommission']?>
            },
            {
                label: 'Товарооборот',
                data: <?=$statisticInfo['generalReceiptMoney']?>
            }
        ];
        $("#flot-pie2").length && $.plot($("#flot-pie2"), da2, {
            series: {
                pie: {
                    combine: {
                        color: "#999",
                        threshold: 0.05
                    },
                    show: true
                }
            },
            colors: ["#99c7ce","#999999","#bbbbbb","#dddddd","#f0f0f0"],
            legend: {
                show: false
            },
            grid: {
                hoverable: true,
                clickable: false
            },
            tooltip: true,
            tooltipOpts: {
                content: "%s: %p.0%"
            }
        });
    </script>

    <div class="row">
        <div class="col-md-4">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->personalIncome ?></span>
                    <span class="label bg-primary">1</span>
                    <?= THelper::t('personal_award') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->structIncome ?></span>
                    <span class="label bg-dark">2</span>
                    <?= THelper::t('team_award') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->mentorBonus ?></span>
                    <span class="label bg-77382E">3</span>
                    <?= THelper::t('mentor_bonus') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->careerBonus ?></span>
                    <span class="label bg-009A8C">4</span>
                    <?= THelper::t('career_bonus') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->executiveBonus ?></span>
                    <span class="label bg-AAA100">5</span>
                    <?= THelper::t('executive_bonus') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->worldBonus ?></span>
                    <span class="label bg-AA0900">6</span>
                    <?= THelper::t('world_bonus') ?>
                </li>

                <li class="list-group-item">
                    <span class="pull-right"><?=Users::getStatisticBonusEquity()?></span>
                    <span class="label bg-664CC1">7</span>
                    <?= THelper::t('bonus_equity'); ?>
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

    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            Список товаров с товарооборотом по каждому товару
        </header>
        <div class="table-responsive panel-body">
            <table class="table table-translations table-striped datagrid m-b-sm tableTradeTurnover">
                <thead>
                    <tr>
                        <th>код товара</th>
                        <th><?=THelper::t('name_product')?></th>
                        <th><?=THelper::t('Price')?></th>
                        <th><?=THelper::t('sold_PCs')?></th>
                        <th><?=THelper::t('turnover')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($statisticInfo['tradeTurnover']['listProduct'])){?>
                    <?php foreach ($statisticInfo['tradeTurnover']['listProduct'] as $k=>$item) {?>
                        <tr>
                            <td><?=$k;?></td>
                            <td><?=$item['title'];?></td>
                            <td><?=$item['price'];?></td>
                            <td><?=$item['count'];?></td>
                            <td><?=($item['price'] * $item['count']);?></td>
                        </tr>
                    <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <script type="text/javascript">
        $('.tableTradeTurnover').dataTable({
            language: TRANSLATION,
            lengthMenu: [ 25, 50, 75, 100 ],
            "order": [[ 4, "desc" ]]
        });
    </script>

    
    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            Таблица максимальных чеков за заданный перод
        </header>
        <div class="table-responsive panel-body">
            <table class="table table-translations table-striped datagrid m-b-sm tableMaxCheck">
                <thead>
                <tr>
                    <th><?=THelper::t('login')?></th>
                    <th><?=THelper::t('user_firstname_secondname')?></th>
                    <th>email</th>
                    <th><?=THelper::t('phone')?></th>
                    <th><?=THelper::t('amount')?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($statisticInfo['tradeTurnover']['forUser'])){?>
                <?php foreach ($statisticInfo['tradeTurnover']['forUser'] as $k=>$item) {?>
                        <?php $infoUser = Users::findOne(['_id'=>new \MongoDB\BSON\ObjectID($k)]);?>
                    <tr>
                        <td><?=$infoUser->username?></td>
                        <td>
                            <?=(!empty($infoUser->secondName) ? $infoUser->secondName : ''); ?>
                            <?=(!empty($infoUser->firstName) ? $infoUser->firstName : ''); ?>
                        </td>
                        <td><?=(!empty($infoUser->email) ? $infoUser->email : '')?></td>
                        <td><?=(!empty($infoUser->phoneNumber) ? $infoUser->phoneNumber : '')?></td>
                        <td><?=$item?></td>
                    </tr>
                <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <script type="text/javascript">
    $('.tableMaxCheck').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 4, "desc" ]]
    });
    </script>
    
    <?php } ?>
