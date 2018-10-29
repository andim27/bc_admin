<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 15:38
 */
?>
<div class="row">
    <h3 style="padding-left: 50%">Projects</h3>
</div>

<!-- приход по проекту Wellness -->
<section  class="panel panel-default pm-2" >
    <div class="row m-l-none m-r-none bg-light lter">
        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                        <i class="fa fa-usd fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney_Wellness']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Общий приход по проекту <strong>Wellness</strong></small>
            </a>
        </div>

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong>
                        <?=number_format(round($statisticInfo['receiptMoney_Wellness']), 0, ',', ' ');?>
                        <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Приход деньгами по проекту Wellness</small>
            </a>
        </div>

<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>-->
<!--                        <i class="fa fa-file-text-o fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong> <i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">Приход ваучерами по проекту Wellness</small>-->
<!--            </a>-->
<!--        </div>-->
    </div>
</section>

<!-- приход по проекту VipVip -->
<section class="panel panel-default pm-2">
    <div class="row m-l-none m-r-none bg-light lter">
        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                        <i class="fa fa-usd fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney_VipVip']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Общий приход по проекту <strong>VipVip</strong></small>
            </a>
        </div>

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney_VipVip']), 0, ',', ' ');?>  <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Приход деньгами по проекту VipVip</small>
            </a>
        </div>

<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>-->
<!--                        <i class="fa fa-file-text-o fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong> <i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">Приход ваучерами по проекту VipVip</small>-->
<!--            </a>-->
<!--        </div>-->
    </div>
</section>

<!-- приход по проекту BPT -->
<section class="panel panel-default pm-2">
    <div class="row m-l-none m-r-none bg-light lter">
        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                        <i class="fa fa-usd fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney_VipCoin']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Общий приход по проекту <strong>VipCoin</strong></small>
            </a>
        </div>

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney_VipCoin']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Приход деньгами по проекту VipCoin</small>
            </a>
        </div>

<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>-->
<!--                        <i class="fa fa-file-text-o fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong> <i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">Приход ваучерами по проекту VipCoin</small>-->
<!--            </a>-->
<!--        </div>-->
    </div>
</section>


<!-- приход по проекту BusinessSupport -->
<section class="panel panel-default pm-2">
    <div class="row m-l-none m-r-none bg-light lter">
        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                        <i class="fa fa-usd fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney_BusinessSupport']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Общий приход по проекту <strong>BusinessSupport</strong></small>
            </a>
        </div>

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney_BusinessSupport']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Приход деньгами по проекту BusinessSupport</small>
            </a>
        </div>

<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>-->
<!--                        <i class="fa fa-file-text-o fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong><i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">Приход ваучерами по проекту BusinessSupport</small>-->
<!--            </a>-->
<!--        </div>-->
    </div>
</section>

<!-- приход по проекту BalanceTopUp -->
<section class="panel panel-default pm-2">
    <div class="row m-l-none m-r-none bg-light lter">
        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-ffe00e"></i>
                        <i class="fa fa-usd fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['generalReceiptMoney_BalanceTopUp']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Общий приход по проекту <strong>BalanceTopUp</strong></small>
            </a>
        </div>

        <div class="col-sm-6 col-md-6 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-color-c14d4c"></i>
                        <i class="fa fa-money fa-stack-1x text-white"></i>
                    </span>
            <a class="clear" href="#">
                <span class="h3 block m-t-xs"><strong><?=number_format(round($statisticInfo['receiptMoney_BalanceTopUp']), 0, ',', ' ');?> <i class="fa fa-eur"></i></strong></span>
                <small class="text-muted text-uc capsLock">Приход деньгами по проекту BalanceTopUp</small>
            </a>
        </div>

<!--        <div class="col-sm-4 col-md-4 padder-v b-r b-light">-->
<!--                    <span class="fa-stack fa-2x pull-left m-r-sm">-->
<!--                        <i class="fa fa-circle fa-stack-2x text-color-61c14c"></i>-->
<!--                        <i class="fa fa-file-text-o fa-stack-1x text-white"></i>-->
<!--                    </span>-->
<!--            <a class="clear" href="#">-->
<!--                <span class="h3 block m-t-xs"><strong>-->
<!--                        <i class="fa fa-eur"></i></strong></span>-->
<!--                <small class="text-muted text-uc capsLock">Приход ваучерами по проекту BalanceTopUp</small>-->
<!--            </a>-->
<!--        </div>-->
    </div>
</section>
