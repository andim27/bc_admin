<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 15:38
 */
use app\components\THelper;
?>
<div class="row">
    <h3 style="padding-left: 40%"><?= THelper::t('projects'); ?>:<strong> <?=number_format(round($statisticInfo['receiptMoney_Projects']),0,',',' ') ?> <i class="fa fa-eur"></i></strong></strong></h3>
</div>

<!-- приход по проекту Wellness -->
<section  class="panel panel-default pm-2" >
    <div class="row m-l-none m-r-none bg-light lter">
<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>-->
<!--                        <i class="fa fa-usd fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong>--><?//=number_format(round($statisticInfo['generalReceiptMoney_Wellness']), 0, ',', ' ');?><!-- <i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">--><?//= THelper::t('project_common_income'); ?><!-- <strong>Wellness</strong></small>-->
<!--            </a>-->
<!--        </div>-->

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong>
                        <?=number_format(round($statisticInfo['receiptMoney_Wellness']), 0, ',', ' ');?>
                        <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?= THelper::t('sellings'); ?><strong> Wellness</strong></small>
            </a>
        </div>
        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
               <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
            </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney_BalanceTopUp']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?= THelper::t('sellings'); ?>  <strong>BalanceTopUp</strong></small>
            </a>


        </div>
    </div>
</section>

<!-- приход по проекту VipVip -->
<section class="panel panel-default pm-2">
    <div class="row m-l-none m-r-none bg-light lter">
<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>-->
<!--                        <i class="fa fa-usd fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong>--><?//=number_format(round($statisticInfo['generalReceiptMoney_VipVip']), 0, ',', ' ');?><!-- <i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">--><?//= THelper::t('project_common_income'); ?><!--  <strong>VipVip</strong></small>-->
<!--            </a>-->
<!--        </div>-->

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
            <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
            </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney_VipCoin']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?= THelper::t('sellings'); ?>  <strong>VipCoin</strong></small>
            </a>
        </div>
        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
            <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
            </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney_VipVip']), 0, ',', ' ');?>  <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?= THelper::t('sellings'); ?> <strong>VipVip</strong></small>
            </a>
        </div>
    </div>
</section>

<!-- приход по проекту VipCoin -->
<section class="panel panel-default pm-2">
    <div class="row m-l-none m-r-none bg-light lter">
<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>-->
<!--                        <i class="fa fa-usd fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong>--><?//=number_format(round($statisticInfo['generalReceiptMoney_VipCoin']), 0, ',', ' ');?><!-- <i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">--><?//= THelper::t('project_common_income'); ?><!--  <strong>VipCoin</strong></small>-->
<!--            </a>-->
<!--        </div>-->

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
            <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
            </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney_BusinessSupport']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock"><?= THelper::t('sellings'); ?>  <strong>BusinessSupport</strong></small>
            </a>
        </div>
        <div class="col-sm-6 col-md-6 padder-v b-r b-light"></div>

    </div>
</section>


<!-- приход по проекту BusinessSupport -->
<section class="panel panel-default pm-2">
    <div class="row m-l-none m-r-none bg-light lter">
<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>-->
<!--                        <i class="fa fa-usd fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong>--><?//=number_format(round($statisticInfo['generalReceiptMoney_BusinessSupport']), 0, ',', ' ');?><!-- <i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">--><?//= THelper::t('project_common_income'); ?><!--  <strong>BusinessSupport</strong></small>-->
<!--            </a>-->
<!--        </div>-->

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">

        </div>
        <div class="col-sm-6 col-md-6 padder-v b-r b-light"></div>

    </div>
</section>


