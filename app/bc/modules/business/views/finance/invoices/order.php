<?php
    use app\components\THelper;
?>

<!DOCTYPE html>
<!-- saved from url=(0070)http://vipsite.biz/index.php?route=account/order/receipt&order_id=1857 -->
<html class="gr__vipsite_biz"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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
      <b>BUSINESS PROCESS TECHNOLOGIES LP</b><br>
      Registration No SL26269<br>
      Suite 2 5 Vincent Str., Edinburgh, Scotland    </div>
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
            <td class="text-left"><b><?=THelper::t('client_information')?>:</b><br>
                <?=$clientFullName?></td>
            
          </tr>
           <tr>
            <td class="text-left" style="border: none;"><b><?=THelper::t('receipt')?></b> <?=$receipt?></td>
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
              <td class="text-left"><?=THelper::t('product_name')?></td>
              	
              <td class="text-right"><?=THelper::t('quantity')?></td>
              <td class="text-right"><?=THelper::t('price')?></td>
              <td class="text-right"><?=THelper::t('total')?></td>
            
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
              <td class="text-right"><b><?=THelper::t('sub_total')?></b></td>
              <td class="text-right"><?=$subTotal?><?=$currency?></td>
            </tr>
                        <tr>
              <td colspan="2"></td>
              <td class="text-right"><b><?=THelper::t('payment_gateway')?></b></td>
              <td class="text-right"><?=$paymentGateway?><?=$currency?></td>
            </tr>
                        <tr>
              <td colspan="2"></td>
              <td class="text-right"><b><?=THelper::t('total')?></b></td>
              <td class="text-right"><?=$total?><?=$currency?></td>
            </tr>
                      </tfoot>
        </table>
      </div>
      <div class="row">
          
          <div class="col-md-3 col-md-offset-6 text-right">
            <img src="/images/invoices/stamp_LP.png" alt="" style="position:relative; width:250px; margin-top:-40px;">
          </div>
          
        </div>
</div>

</div></div></body></html>