<?php
use app\components\THelper;
use yii\helpers\Html;

$countGoods = [];
?>

<table class="table table-translations table-striped datagrid m-b-sm">
    <thead>
    <tr>
        <th>
            <?=THelper::t('date')?>
        </th>
        <th>
            <?=THelper::t('full_name')?>
        </th>
        <th>
            <?=THelper::t('login')?>
        </th>
        <th>
            <?=THelper::t('city')?>
        </th>
        <th>
            <?=THelper::t('goods')?>
        </th>
        <th>
            <?=THelper::t('price')?>
        </th>
        <th>
            <?=THelper::t('type_payment')?>
        </th>
        <th>
            <?=THelper::t('status_sale')?>
        </th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php $totalSum = 0;?>
    <?php if(!empty($model)) {?>
    <?php foreach($model as $item) {?>
    <?php if (!empty($item->statusSale) && count($item->statusSale->set)>0 && $item->statusSale->checkSalesForUserChange($listAdmin)!==false) {?>
    <?php if (empty($request['infoCity'])  || $request['infoCity'] == $item->infoUser->city || (empty($item->infoUser->city) && $request['infoCity']=='None('.$item->infoUser->country.')')) {?>

    <?php
    $infoSet = '';

    foreach ($item->statusSale->set as $itemSet) {
        if(($request['infoStatus'] == 'all' || $request['infoStatus']==$itemSet->status)
            && ($request['infoProducts'] == 'all' || $request['infoProducts']==$itemSet->title)) {
            $infoSet .= '
                    <tr data-set="'.$itemSet->title.'">
                        <td>
                            '. $itemSet->title .'
                        </td>
                        <td>
                            <span class="label label-default statusOrder">
                                '. THelper::t($itemSet->status) .'
                            </span>
                        </td>
                        <td>
                            '.$itemSet->dateChange->toDateTime()->format('Y-m-d H:i:s') .'
                        </td>
                    </tr>';

            if(empty($countGoods[$itemSet->title])){
                $countGoods[$itemSet->title] = 0;
            }

            $countGoods[$itemSet->title]++;
        }
    }
    ?>

    <?php if(!empty($infoSet)) {?>
        <?php $totalSum += $item->price;?>
        <tr>
            <td><?=$item->dateCreate->toDateTime()->format('Y-m-d H:i:s')?></td>
            <td><?=$item->infoUser->secondName?> <?=$item->infoUser->firstName?></td>
            <td><?=$item->username?></td>
            <td><?=$item->infoUser->city.'('.$item->infoUser->country.')'?></td>
            <td><?=$item->productName?></td>
            <td><?=$item->price?></td>
            <td><?=(!empty($item->statusSale->buy_for_money) ? THelper::t('paid_in_cash') : THelper::t('paid_in_company'))?></td>
            <td>
                <table>
                    <?=$infoSet?>
                </table>
            </td>
            <td>
                <?= Html::a('<i class="fa fa-comment"></i>', ['/business/status-sales/look-comment','idSale'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) ?>
            </td>
        </tr>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
    </tbody>
    <tfooter>
        <tr>
            <th>
                <?=THelper::t('total')?>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><?=$totalSum?></th>
            <th></th>
            <th>
                <?php if(!empty($countGoods)){ ?>
                    <?php foreach($countGoods as $k=>$item){ ?>
                        <?=$k?> - <?=$item?> <br/>
                    <?php } ?>
                <?php } ?>
            </th>
            <th></th>
        </tr>
    </tfooter>
</table>
