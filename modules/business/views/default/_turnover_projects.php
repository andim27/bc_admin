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
    <tr><td  width="25%"><span>Wellness:</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_Wellness']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span>VIPVIP:</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_VipVip']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span>VIPCoin:</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_VipCoin']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span>BUSINESSSUPPORT:</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_BusinessSupport']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%"><span title="Факт выполнения">BALANCETOPUP(f):</span></td><td align="right"><span class="h4 m-t-xs"><?=number_format(round($statisticInfo['receiptMoney_BalanceTopUp']), 0, ',', ' ');?></span></td></tr>
    <tr><td  width="25%" align="right"><span class="text-primary">TOTAL:</span></td><td align="right"><span class="h4 m-t-xs text-dark"><?=number_format(round($statisticInfo['receiptMoney_BalanceTopUp']+$statisticInfo['receiptMoney_BusinessSupport']+$statisticInfo['receiptMoney_VipCoin']+$statisticInfo['receiptMoney_VipVip']+$statisticInfo['receiptMoney_Wellness']), 0, ',', ' ');?></span></td></tr>
</table>
