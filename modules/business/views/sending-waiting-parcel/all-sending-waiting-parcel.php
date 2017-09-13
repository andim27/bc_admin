<?php
use app\components\THelper;
use yii\helpers\Html;
use app\models\Users;
use app\models\Warehouse;
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories($language);
$listWarehouse = Warehouse::getArrayWarehouse();
$listAdmin = Users::getListAdmin();
?>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th><?=THelper::t('date_added');?></th>
                <th><?=THelper::t('who_sent');?></th>
                <th><?=THelper::t('from_sent')?></th>

                <th><?=THelper::t('to_sent')?></th>
                <th><?=THelper::t('who_gets')?></th>
                <th><?=THelper::t('than_sent')?></th>

                <th><?=THelper::t('part_parcel')?></th>
                <th><?=THelper::t('num')?></th>

                <th><?=THelper::t('condition')?></th>
                <th><?=THelper::t('comments')?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($model)) { ?>
                <?php foreach($model as $item) { ?>
                    <?php if(!empty($item->part_parcel)) { ?>
                        <?php foreach($item->part_parcel as $itemParcel) { ?>
                            <tr>
                                <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                                <td><?=(!empty($listAdmin[(string)$item->who_sent]) ? $listAdmin[(string)$item->who_sent] : 'None');?></td>
                                <td><?=(!empty($listWarehouse[$item->from_where_send]) ? $listWarehouse[$item->from_where_send] : 'None');?></td>

                                <td><?=$listWarehouse[$item->where_sent]?></td>
                                <td><?=(!empty($item->who_gets) ? $item->who_gets : '')?></td>
                                <td><?=(!empty($item->delivery) ? $item->delivery : '')?></td>

                                <td><?=$listGoods[$itemParcel['goods_id']]?></td>
                                <td><?=$itemParcel['goods_count']?></td>

                                <td><?=($item->is_posting == 0  ? THelper::t('sent') : THelper::t('capitalized'))?></td>
                                <td><?=(!empty($itemParcel['comment']) ? $itemParcel['comment'] : THelper::t('no'))?></td>

                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
</script>