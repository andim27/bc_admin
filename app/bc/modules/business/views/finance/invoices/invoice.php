<?php
    use app\components\THelper;
?>

<!DOCTYPE html>
<!-- saved from url=(0074)http://vipsite.biz/index.php?route=account/order/fullinvoice&order_id=1857 -->
<html class="gr__vipsite_biz"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Invoice №1857</title>
    <!--<base href="http://vipsite.biz/catalog/">--><base href=".">
    <link rel="stylesheet" href="/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="chrome-extension://onhiacboedfinnofagfgoaanfedhmfab/dist/contentStyles.css"></head>
<body data-gr-c-s-loaded="true">
<div class="container">

    <div class="row top30">
        <div class="col-sm-1"></div>
        <div class="col-md-5 paddingtop30"><img src="/images/invoices/logo_long.png" style="width:400px;"></div>
        <div class="col-md-4 text-right">
            <b>BUSINESS PROCESS TECHNOLOGIES LTD</b><br>
            registered address at 165985<br>
            1st floor, Victoria, Mah6, Seychelles    </div>
    </div>
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-md-6 text-left">

        </div>

    </div>
    <div class="row">
        <div class="col-sm-1"></div>
        <div id="content" class="col-sm-9">
            <table class="table table-hover">
                <thead>
                <tr>
                    <td class="text-left">BUSINESS PROCESS TECHNOLOGIES LTD
                        1st floor, Victoria, Mah6, Seychelles<br>
                        <b><br>
                        </b>
                    </td>

                </tr>
                <tr>
                    <td class="text-left" style="border: none;"><b><?=THelper::t('invoice')?></b> <?=$receipt?></td>
                    <td class="text-left" style="border: none;"><b><?=THelper::t('date')?>:</b> <?=$date?></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-left" style="width: 50%; border:none;">
                        <b><?=THelper::t('your_customer_no')?>:</b> <?=$customerNo?><br>
                    </td></tr>
                </tbody>
            </table>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <td class="text-left"><?=THelper::t('goods_name')?></td>

                        <td class="text-right"><?=THelper::t('quantity')?></td>
                        <td class="text-right"><?=THelper::t('unit_price')?></td>
                        <td class="text-right"><?=THelper::t('amount')?></td>

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-left"><?=$productName?></td>
                        <td class="text-right"><?=$quantity?></td>
                        <td class="text-right"><?=$price?><?=$currency?></td>
                        <td class="text-right"><?=$subTotal?><?=$currency?></td>

                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><b><?=THelper::t('sub_total_amount')?></b></td>
                        <td class="text-right"><?=$subTotal?><?=$currency?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><b><?=THelper::t('payment_gateway')?></b></td>
                        <td class="text-right"><?=$paymentGateway?><?=$currency?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right"><b><?=THelper::t('total_amount')?></b></td>
                        <td class="text-right"><?=$total?><?=$currency?></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row">
                <br><br><br>
                <div class="col-md-12 text-left">
                    <b>BUSINESS PROCESS TECHNOLOGIES LTD</b>
                </div>
                <br><br>
                <div class="col-md-12 text-center">
                    Bank of Cyprus Public Company LTD, BRANCH IBU 3 0388,<br>
                    IBAN CY10 0020 0195 0000 3570 2229 9285, BIC Code BCYPCY2N
                </div>

            </div>
        </div>

    </div></div></body></html>