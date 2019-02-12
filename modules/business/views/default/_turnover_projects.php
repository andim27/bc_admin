<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 15:38
 */
use app\components\THelper;
?>

<table style="width:100%">
    <tr><td  width="25%"><span>Wellness(<span><?=$statisticInfo['receiptMoney_Wellness_cnt']?></span>):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_Wellness']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span>VIPVIP(<span><?=$statisticInfo['receiptMoney_VipVip_cnt']?></span>):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_VipVip']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span>VIPCoin(<span><?=$statisticInfo['receiptMoney_VipCoin_cnt']?></span>):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_VipCoin']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span>BUSINESSSUPPORT(<span><?=$statisticInfo['receiptMoney_BusinessSupport_cnt']?></span>):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_BusinessSupport']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span title="Факт выполнения">BALANCETOPUP(<span><?=$statisticInfo['receiptMoney_BalanceTopUp_cnt']?></span>):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_BalanceTopUp']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span title="Остальное">Composite(<span><?=$statisticInfo['receiptMoney_Composite_cnt']?></span>):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_Composite']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%" align="right"><span class="text-primary">TOTAL:</span></td><td align="right"><span class="h4 m-t-xs text-dark"><?=number_format(round($statisticInfo['receiptMoney_BalanceTopUp']+$statisticInfo['receiptMoney_BusinessSupport']+$statisticInfo['receiptMoney_VipCoin']+$statisticInfo['receiptMoney_VipVip']+$statisticInfo['receiptMoney_Wellness']+$statisticInfo['receiptMoney_Composite']), 0, ',', ' ');?></span></td></tr>
</table>
<hr>
<table style="width:100%">
    <tr><td  width="25%"><span title="Факт выполнения">BALANCETOPUP(f):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_BalanceTopUp_f']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="50%"><span title="Продукты без категорий">Products(no cat):(<span><?=$statisticInfo['receiptMoney_cat_empty_cnt']?></span>):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_cat_empty']), 0, ',', ' ');?></span></td></tr>
</table>
