<?php

use app\components\THelper;
use app\components\UrlHelper;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use app\models\Users;

$hideStatistic = 0;
$apply=THelper::t('apply');
$layoutDate = <<< HTML
    <span class="input-group-addon">c</span>
    {input1}
    {separator}
    {input2}
    <a class="input-group-addon" href="javascript:$('.formStatistic').submit();">
        {$apply}
    </a>
HTML;
function totalDetailsSum($statisticInfo) {
    $sum =$statisticInfo['receiptMoneyDetails']['softpay']
        +$statisticInfo['receiptMoneyDetails']['paysera']
        +$statisticInfo['receiptMoneyDetails']['paysera_a']
        +$statisticInfo['receiptMoneyDetails']['advcash']
        +$statisticInfo['receiptMoneyDetails']['advcash_a']
        +$statisticInfo['receiptMoneyDetails']['pb']
        +$statisticInfo['receiptMoneyDetails']['bank_a']
        +$statisticInfo['receiptMoneyDetails']['cash_a']
        +$statisticInfo['receiptMoneyDetails']['perevod_a']
        +$statisticInfo['receiptMoneyDetails']['advaction_a']
        +$statisticInfo['receiptMoneyDetails']['other_a']
    ;
    return number_format(round($sum));
}

?>
    <style>
        .pm-2 {
            margin-bottom:2px !important;
        }
    </style>

    <script>
        function moneyAllDetails() {
            $('#m_all_details').toggle();
        }
        function moneyIncomeDetails() {
            $('#m_income_details').toggle();
        }
        function tOverDetails() {
            $('#t_over_details').toggle();
        }
        function getDetailsBlock(block_name) {
            if ($("#block-place-"+block_name).css('display') == 'none') {
                $("#block-place-"+block_name).css('display','block');
            } else {
                $("#block-place-"+block_name).css('display','none');
                return;
            }
            var url ="/<?=Yii::$app->language?>/business/sale-report/stat-details";
            $.post(url,{'d_from':$('#d_from').val(),'d_to':$('#d_to').val(),'block_name':block_name}).done(function (data) {
                if (data.success == true) {
                    $('#block-place-'+block_name).html(data.details_html).show();
                } else {
                    console.log('Error:get block_name  '+block_name);
                    error_html ='<div class="alert alert-danger"><strong>'+data.details_html+'</strong></div>';
                    $('#block-place-'+block_name).html(error_html).show();
                }

            })
        }

    </script>
    <div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('date'); ?></h3>
    </div>

<?php if(Users::checkRule('show_statistic','sidebar_main_stat') === true){?>
    <section class="panel panel-default">
        <div class="row">
            <div class="col-md-12">
                <form action="" method="POST" class="formStatistic">
                    <?= DatePicker::widget([
                        'name' => 'from',
                        'value' => $statisticInfo['request']['from'],
                        'type' => DatePicker::TYPE_RANGE,
                        'options' =>['id'=>'d_from'],
                        'options2' =>['id'=>'d_to'],
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

    <!--b: GoodsTurnover-->
    <section>
        <div class="m-b-md">
            <h3 class="m-b-none"><?= THelper::t('goods_turnover'); ?></h3>
        </div>
    </section>
    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">


            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#" onclick="getDetailsBlock('turnover-details')">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['salesTurnover']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('goods_turnover'); ?> (general)</small>
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['salesTurnoverDetails']['packs']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('goods_turnover'); ?> (packs)</small>
                </a>
                <table id="t_over_details" style="display: none">
                </table>
                <section id="block-place-turnover-details" class="panel panel-default" style="display:none">
                </section>
            </div>

            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
               <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['onPersonalAccounts']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('on_personal_accounts'); ?></small>
                </a>
            </div>
            <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['salesPoints']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('points_turnover'); ?></small>
                </a>
            </div>
            <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <a class="btn btn-success center-block" title="Товарооборот- графики" onclick="getDetailsBlock('turnover-graph')">
                    <span class="glyphicon glyphicon-stats"></span> <?= THelper::t('graph'); ?>
                </a>
            </div>
            <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <a class="btn btn-success center-block" title="Товарооборот- графики" onclick="getDetailsBlock('turnover')">
                    <span class="glyphicon glyphicon-stats"></span> <?= THelper::t('table'); ?>
                </a>
            </div>
        </div>
    </section>
    <section id="block-place-turnover-graph" class="panel panel-default" style="display:none">
    </section>
    <section id="block-place-turnover" class="panel panel-default" style="display:none">

        <script type="text/javascript">

            var da = [
                {
                    label: 'Живых денег',
                    data: <?=($statisticInfo['generalReceiptMoney']-$statisticInfo['receiptVoucher']+$statisticInfo['cancellationVoucher'])?>
                },
                {
                    label: '',
                    data: <?=($statisticInfo['generalReceiptMoney']-($statisticInfo['generalReceiptMoney']-$statisticInfo['receiptVoucher']+$statisticInfo['cancellationVoucher']))?>
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
                    label: '',
                    data: <?=($statisticInfo['generalReceiptMoney']-$statisticInfo['feesCommission'])?>
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

    </section>

    <!--e:  GoodsTurnOver-->

    <!--b: Money-->
    <section>
        <div class="m-b-md">
            <h3 class="m-b-none"><?= THelper::t('money'); ?></h3>
        </div>
    </section>
    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">

            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-money fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#" onclick="moneyIncomeDetails()">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoneyDetails']['income']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('money_income'); ?>(ORDERS)</small>
                </a>
                <table id="m_income_details" style="display: none">
                    <tr><td  width="25%"><span>softpay:</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['softpay']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['softpay']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>paysera:</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['paysera']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['paysera']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>paysera(a):</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['paysera_a']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['paysera_a']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>advcash:</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['advcash']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['advcash']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>advcash(a):</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['advcash_a']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['advcash_a']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>pb:</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['pb']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['pb']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>bank(a):</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['bank_a']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['bank_a']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>cash(a):</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['cash_a']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['cash_a']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>perevod(a):</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['perevod_a']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['perevod_a']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr><td  width="25%"><span>advaction(a):</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['advaction_a']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['advaction_a']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <tr style="border-bottom: dotted darkgreen"><td  width="25%"><span>other(a):</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['receiptMoneyDetails']['other_a']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['other_a']), 0, ',', ' ')) : 0 ?></span></td></tr>
                    <!--                    <tr><td  width="25%"><span>invoice:</span></td><td align="right"><span class="h4 m-t-xs">--><?//= isset($statisticInfo['receiptMoneyDetails']['invoice']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['invoice']), 0, ',', ' ')) : 0 ?><!--</span></td></tr>-->
                    <!--                    <tr style="border-bottom: dotted darkgreen"></tr>-->
                    <tfoot >
                    <tr style="border-bottom: dotted darkgreen"><td  width="25%"><span>Total:</span></td><td align="right"><span class="h4 m-t-xs"> <?= totalDetailsSum($statisticInfo) ?> </span></td></tr>
                    <!--                    <tr><td  width="25%"><span>Income:</span></td><td align="right"><span class="h4 m-t-xs"> --><?//= isset($statisticInfo['receiptMoneyDetails']['income']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['income']), 0, ',', ' ')) : 0 ?><!-- </span></td></tr>-->
                    <!--                    <tr><td  width="25%"><span>Reloan:</span></td><td align="right"><span class="h4 m-t-xs">--><?//= isset($statisticInfo['receiptMoneyDetails']['reloan']) ? (number_format(round($statisticInfo['receiptMoneyDetails']['reloan']), 0, ',', ' ')) : 0 ?><!--</span></td></tr>-->

                    </tfoot>

                </table>
            </div>

            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                  <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoneyDetails']['loan']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('loan'); ?>(ADMIN)</small>
                </a>

                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoneyDetails']['reloan']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('sidebar_repayment'); ?></small>
                </a>
            </div>
            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <!--                  <span class="fa-stack fa-2x pull-left m-r-sm " style="margin-left: 4%">-->
                <!--                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>-->
                <!--                    <i class="fa fa-money fa-stack-1x text-white"></i>-->
                <!--                  </span>-->
                <!--                <a class="clear" href="#">-->
                <!--                    <span class="h3 block m-t-xs"><strong>--><?//=number_format(round($statisticInfo['refill_vipvip']),0,',',' ')?><!-- <i class="fa fa-eur"></i></strong></span>-->
                <!--                    <small class="text-muted text-uc capsLock">--><?//= THelper::t('replenished'); ?><!-- VipVip</small>-->
                <!--                </a>-->
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['refill']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('replenished'); ?></small>
                </a>
            </div>
            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm " style="margin-left: 4%">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-money fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['refill_wellness']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('replenished'); ?> Wellness</small>
                </a>
                <div class="row b-l">

                    <!--                        <span class="fa-stack fa-2x pull-left m-r-sm " >-->
                    <!--                            <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>-->
                    <!--                            <i class="fa fa-money fa-stack-1x text-white"></i>-->
                    <!--                        </span>-->

                    <a class="clear" href="#">
                        <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['refill_vipvip']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                        <small class="text-muted text-uc capsLock"><?= THelper::t('replenished'); ?> VipVip</small>
                    </a>

                </div>


            </div>
        </div>
    </section>
    <section id="block-place-projects" class="panel panel-default" style="display:none;margin-bottom: 2px;margin-left:50%;">

    </section>

    <!--e: Money-->
    <!--b: Comission-->
    <section>
        <div class="m-b-md">
            <h3 class="m-b-none"><?= THelper::t('user_commission_title'); ?></h3>
        </div>
    </section>
    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">


            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['orderedForWithdrawal']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('ordered_for_withdrawal'); ?></small>
                </a>
            </div>

            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-4c6cc1"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#" onclick="getDetailsBlock('commission-details')">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['feesCommission']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('accrued_commissions'); ?></small>
                </a>
                <section id="block-place-commission-details" class="panel panel-default" style="display:none;margin-bottom: 2px;">

                </section>
            </div>
            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['issuedCommission']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('issued_commissions'); ?></small>
                </a>
            </div>
            <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <a class="btn btn-success center-block" title="Товарооборот- графики" onclick="getDetailsBlock('commission-graph')">
                    <span class="glyphicon glyphicon-stats"></span> <?= THelper::t('graph'); ?>
                </a>
            </div>

        </div>
    </section>
    <section id="block-place-commission-graph" class="panel panel-default" style="display:none">
    </section>

    <!--e: Comission-->

    <!--b:  Bonuses-->

    <!--e:  Bonuses-->
    <!--b:  Cheki-->
    <section>
        <div class="m-b-md">
            <h3 class="m-b-none"><?="Чеки" ?></h3>
        </div>
    </section>

    <div class="row m-l-none m-r-none bg-light lter">
        <!-- Максимальный чек - 1 за период -->
        <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=@number_format(round($statisticInfo['tradeTurnover']['bestChecksUser'][0]['sum']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?=@$statisticInfo['tradeTurnover']['bestChecksUser'][0]['fio'] ?></small>
            </a>
        </div>
        <!-- Максимальный чек - 2 за период -->
        <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=@number_format(round($statisticInfo['tradeTurnover']['bestChecksUser'][1]['sum']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?=@$statisticInfo['tradeTurnover']['bestChecksUser'][1]['fio'] ?></small>
            </a>
        </div>
        <!-- Максимальный чек - 3 за период -->
        <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=@number_format(round($statisticInfo['tradeTurnover']['bestChecksUser'][2]['sum']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?=@$statisticInfo['tradeTurnover']['bestChecksUser'][2]['fio'] ?> </small>
            </a>
        </div>
        <!-- Максимальный чек - 4 за период -->
        <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=@number_format(round($statisticInfo['tradeTurnover']['bestChecksUser'][3]['sum']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?=@$statisticInfo['tradeTurnover']['bestChecksUser'][3]['fio'] ?></small>
            </a>
        </div>
        <div class="col-sm-2 col-md-2 padder-v b-r b-light">
            <a class="btn btn-success center-block" title="Детализиция по проектам" onclick="getDetailsBlock('checks')">
                <span class="glyphicon glyphicon-stats"></span><?= THelper::t('table'); ?>
            </a>
        </div>
    </div>
    <br> <br>
    <section id="block-place-checks" class="panel panel-default" style="display:none">


    </section>
    <!--   b: Partners-->
    <section>
        <div class="m-b-md">
            <h3 class="m-b-none"><?= THelper::t('tree_partners'); ?></h3>
        </div>
    </section>
    <section class="panel panel-default">

        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-4c6cc1"></i>
                    <i class="fa fa-users fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=$statisticInfo['newRegistration'];?></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('new_partners_for_period'); ?></small>
                </a>
            </div>

            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-4cc0c1"></i>
                    <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=$statisticInfo['ofThemPaid'];?></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('which_paid'); ?></small>
                </a>
            </div>

            <div class="col-sm-4 col-md-4 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-c14cba"></i>
                    <i class="fa fa-sign-out fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=(!isset($statisticInfo['removeUsers'])? Thelper::t('no'):$statisticInfo['removeUsers']);  ?></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('main_partners_excluded'); ?></small>
                </a>
            </div>
            <div class="col-sm-2 col-md-2 padder-v b-r b-light ">
                <a class="btn btn-success center-block" title="График подключения" onclick="getDetailsBlock('partners')">
                    <span class="glyphicon glyphicon-stats"></span> <?= THelper::t('graph'); ?>
                </a>
            </div>

        </div>
    </section>

    <section id="block-place-partners" class="panel panel-default" style="display:none">
        <header class="panel-heading font-bold">
            <?= THelper::t('partners_graph_by_time'); ?>
        </header>
        <div class="panel-body">
            <div id="flot-connect-partners" class="height250"></div>
        </div>
    </section>
    <!--e: Partners-->
    <!--e:  Cheki-->
<?php } ?>