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
        var url ="/<?=Yii::$app->language?>/business/default/stat-details";
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

<?php if(Users::checkRule('show_statistic','sidebar_home') === true){?>
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
                    <span class="h3 block m-t-xs"><strong><?=$statisticInfo['removeUsers']?></strong></span>
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

    <section>
        <div class="m-b-md">
            <h3 class="m-b-none"><?= THelper::t('money'); ?></h3>
        </div>
    </section>
    <section class="panel panel-default">
        <div class="row m-l-none m-r-none bg-light lter">
            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#" onclick="moneyAllDetails()">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('general_arrival'); ?>:</small>
                </a>
                <table id="m_all_details" style="display: none">
                    <tr><td  width="25%"><span>General:</span></td><td align="right"><span class="h4 m-t-xs"> <?= isset($statisticInfo['generalReceiptMoneyDetails']['all']) ? (number_format(round($statisticInfo['generalReceiptMoneyDetails']['all']), 0, ',', ' ')) : 0 ?> </span></td></tr>
                    <tr><td  width="25%"><span>VipCoin:</span></td><td align="right"><span class="h4 m-t-xs"><?= isset($statisticInfo['generalReceiptMoneyDetails']['vipcoin']) ? (number_format(round($statisticInfo['generalReceiptMoneyDetails']['vipcoin']), 0, ',', ' ')) : 0 ?></span></td></tr>
                </table>
            </div>

            <div class="col-sm-3 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                    <i class="fa fa-money fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('money_income'); ?></small>
                </a>
            </div>
            <!--  --------  b:Перенос --------->
            <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                    <i class="fa fa-usd fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['refill']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('replenished'); ?></small>
                </a>
            </div>
            <!--  -------   e:Перенос  -------->
            <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                    <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>
                    <i class="fa fa-file-text-o fa-stack-1x text-white"></i>
                </span>
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptVoucher']-$statisticInfo['cancellationVoucher']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('vaucher_income'); ?></small>
                </a>
            </div>
            <div class="col-sm-2 col-md-2 padder-v b-r b-light">
                <a class="btn btn-success center-block" title="Детализиция по проектам" onclick="getDetailsBlock('projects')">
                    <span class="glyphicon glyphicon-stats"></span> <?= THelper::t('projects'); ?>
                </a>
            </div>
        </div>
    </section>
    <section id="block-place-projects" class="panel panel-default" style="display:none;margin-bottom: 2px">

    </section>


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
                <a class="clear" href="#">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['feesCommission']),0,',',' ')?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('accrued_commissions'); ?></small>
                </a>
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
                <a class="clear" href="#" onclick="tOverDetails();">
                    <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['salesTurnoverDetails']['packs']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                    <small class="text-muted text-uc capsLock"><?= THelper::t('goods_turnover'); ?> (packs)</small>
                </a>
                <table id="t_over_details" style="display: none">
                    <tr><td  width="25%"><span>General:</span></td><td align="right"><span class="h4 m-t-xs"> <?=number_format(round($statisticInfo['salesTurnover']), 0, ',', ' ');?> </span><span>   (<?=@number_format(round(($statisticInfo['salesTurnoverDetails']['packs']*100)/$statisticInfo['salesTurnover']),2, ',', ' ');?>)%</span></td></tr>
<!--                    <tr><td  width="25%"><span>Rest:</span></td><td align="right"><span class="h4 m-t-xs">--><?//=//number_format(round($statisticInfo['salesTurnoverDetails']['rest']), 0, ',', ' ');?><!--</span></td></tr>-->
                </table>
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
                <span class="fa-stack fa-2x pull-left m-r-sm">&nbsp;</span>
                <a class="clear" href="#"></a>
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

<!--        <section  class="panel panel-default">-->
<!--            <div class="row m-l-none m-r-none bg-light lter">-->
<!--                <div class="col-sm-6 col-md-6 padder-v">-->
<!--                    <div class="panel panel-default">-->
<!--                        <header class="panel-heading font-bold">-->
<!--                            Отношение товарооборота к живым деньгам-->
<!--                        </header>-->
<!--                        <div class="panel-body">-->
<!--                            <div id="flot-pie" class="height400"></div>-->
<!--                        </div>-->
<!---->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-sm-6 col-md-6 padder-v">-->
<!--                    <div class="panel panel-default">-->
<!--                        <header class="panel-heading font-bold">-->
<!--                            Отношение товарооборота к комиссионым-->
<!--                        </header>-->
<!--                        <div class="panel-body">-->
<!--                            <div id="flot-pie2" class="height400"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </section>-->
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
    <section>
        <div class="m-b-md">
            <h3 class="m-b-none"><?= THelper::t('bonuses'); ?></h3>
        </div>
    </section>
    <div class="row">
        <div class="col-md-4">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['connectingBonus']),0,',',' ')?></span>
                    <span class="label bg-primary">1</span>
                    <?= THelper::t('personal_award') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['teamBonus']),0,',',' ')?></span>
                    <span class="label bg-dark">2</span>
                    <?= THelper::t('team_award') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['mentorBonus']),0,',',' ')?></span>
                    <span class="label bg-77382E">3</span>
                    <?= THelper::t('mentor_bonus') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['careerBonus']),0,',',' ')?></span>
                    <span class="label bg-009A8C">4</span>
                    <?= THelper::t('career_bonus') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['executiveBonus']),0,',',' ')?></span>
                    <span class="label bg-AAA100">5</span>
                    <?= THelper::t('executive_bonus') ?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['worldBonus']),0,',',' ')?></span>
                    <span class="label bg-AA0900">6</span>
                    <?= THelper::t('world_bonus') ?>
                </li>

                <li class="list-group-item">
                    <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['equityBonus']),0,',',' ')?></span>
                    <span class="label bg-664CC1">7</span>
                    <?= THelper::t('bonus_equity'); ?>
                </li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-6 bg-664CC1">
                    <div class="padder-v">
                        <span class="m-b-xs h3 block text-white"><?=number_format(round($statisticInfo['bonus']['autoBonus']),0,',',' ')?></span>
                        <small class="text-white"><?= THelper::t('main_auto_bonus') ?></small>
                    </div>
                </div>
                <div class="col-md-6 bg-593FB5">
                    <div class="padder-v">
                        <span class="m-b-xs h3 block text-white"><?=number_format(round($statisticInfo['bonus']['propertyBonus']),0,',',' ')?></span>
                        <small class="text-white"><?= THelper::t('main_property_bonus') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>



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

<?php } ?>