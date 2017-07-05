<?php
use app\components\THelper;
use yii\helpers\Html;


$from = strtotime($request['from']);
$to = strtotime($request['to']);

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
            <?=THelper::t('status_sale')?>
        </th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($model)) {?>
    <?php foreach($model as $item) {?>
    <?php if (!empty($item->statusSale) && count($item->statusSale->set)>0 && $item->statusSale->checkSalesForUserChange($listAdmin)!==false) {?>
    <?php if (empty($request['infoCity'])  || $request['infoCity'] == $item->infoUser->city || (empty($item->infoUser->city) && $request['infoCity']=='None('.$item->infoUser->country.')')) {?>

    <?php
        $infoSet = '';
        foreach ($item->statusSale->set as $itemSet) {
            if($request['infoStatus'] == 'all' || $request['infoStatus']==$itemSet->status) {
                $infoSet = '
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
            }
        }
    ?>

        <?php if(!empty($infoSet)) {?>
        <tr>
            <td><?=$item->dateCreate->toDateTime()->format('Y-m-d H:i:s')?></td>
            <td><?=$item->infoUser->secondName?> <?=$item->infoUser->firstName?></td>
            <td><?=$item->username?></td>
            <td><?=$item->infoUser->city.'('.$item->infoUser->country.')'?></td>
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

</table>
