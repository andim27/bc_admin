<?php
    use app\components\THelper;

/** @var \app\models\Sales $item */
?>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('sale_date_create')?>
                </th>
                <th>
                    <?=THelper::t('sale_product_name')?>
                </th>
                <th>
                    <?=THelper::t('goods')?>
                </th>
                <th>
                    <?=THelper::t('status_sale')?>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($modelSales)){ ?>
                <?php foreach ($modelSales as $item){ ?>
                    <tr>
                        <td><?=$item->dateCreate->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=$item->productName?></td>
                        <td>
                            <table>


                                <?php if(!empty($item->statusSale->set)) {?>
                                    <?php foreach ($item->statusSale->set as $itemSet) {?>
                                        <tr>
                                            <td>
                                                <?= $itemSet->title ?>
                                            </td>
                                            <td>
                                                <span class="label label-default statusOrder">
                                                    <?= THelper::t($itemSet->status) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </table>
                        </td>
                        <td>
                            <?php if($item->type == '-1') { ?>
                                <div  class="label label-danger">Отменен заказ</div>
                            <?php } else { ?>
                                <div  class="label label-success">Активный заказ</div>
                            <?php } ?>
                        </td>
                        <td></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
