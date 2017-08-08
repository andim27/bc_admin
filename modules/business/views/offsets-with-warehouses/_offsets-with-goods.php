<?php
use yii\bootstrap\Html;
use app\components\THelper;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use app\models\Products;
use app\models\Warehouse;

$listPack = Products::getListPack();
$listWarehouse = Warehouse::getArrayWarehouse();

?>

<table class="table table-translations table-striped datagrid m-b-sm">
    <thead>
    <tr>
        <th><?=THelper::t('product')?></th>
        <th><?=THelper::t('number_buy_prepayment')?></th>
        <th><?=THelper::t('number_buy_cash')?></th>
        <th><?=THelper::t('amount_for_the_device')?></th>
        <th><?=THelper::t('amount_repayment_for_company')?></th>
        <th><?=THelper::t('amount_repayment_for_warehouse')?></th>
        <th><?=THelper::t('difference')?></th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($info)) { ?>
        <?php foreach($info as $kSet=>$itemSet) { ?>
            <tr>
                <td><?=$listPack[$kSet]?></td>
                <td><?=$itemSet['number_buy_prepayment']?></td>
                <td><?=$itemSet['number_buy_cash']?></td>
                <td><?=$itemSet['amount_for_the_device']?></td>
                <td><?=$itemSet['amount_repayment_for_company']?></td>
                <td><?=$itemSet['amount_repayment_for_warehouse']?></td>
                <td>
                    <?php
                    $difference = $itemSet['amount_repayment_for_company']-$itemSet['amount_repayment_for_warehouse'];
                    ?>
                    <span class="<?=($difference>0 ? 'text-danger' : 'text-success')?>">
                        <?=abs($difference)?>
                    </span>
                </td>
            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>