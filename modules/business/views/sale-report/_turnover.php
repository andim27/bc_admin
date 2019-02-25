<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 15:39
 */
use app\components\THelper;
?>
<h3 style="margin-left: 40%"><?= THelper::t('turnover_details'); ?></h3>
<section class="panel panel-default">
    <header class="panel-heading font-bold">
        <?= THelper::t('turnover_goods_list'); ?>
    </header>
    <div class="table-responsive panel-body">
        <table class="table table-translations table-striped datagrid m-b-sm tableTradeTurnover">
            <thead>
            <tr>
                <th><?= THelper::t('product_code'); ?></th>
                <th><?=THelper::t('name_product')?></th>
                <th><?=THelper::t('Price')?></th>
                <th><?=THelper::t('sold_PCs')?></th>
                <th><?=THelper::t('turnover')?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($statisticInfo['tradeTurnover']['listProduct'])){?>
                <?php foreach ($statisticInfo['tradeTurnover']['listProduct'] as $k=>$item) {?>
                    <tr>
                        <td><?=$k;?></td>
                        <td><?=$item['title'];?></td>
                        <td><?=$item['price'];?></td>
                        <td><?=$item['count'];?></td>
                        <td><?=($item['amount']);?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<script type="text/javascript">
    $('.tableTradeTurnover').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 4, "desc" ]]
    });
</script>
