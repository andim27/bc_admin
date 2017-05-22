<?php
use app\components\THelper;
use yii\helpers\Html;
use app\models\Users;
use app\models\Warehouse;
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();
$listWarehouse = Warehouse::getArrayWarehouse();
$listAdmin = Users::getListAdmin();
?>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>№</th>
                <th>Дата добавления</th>
                <th>Кто отправил</th>
                <th>Откуда отправка</th>
                <th>Состав посылки</th>
                <th>Кол</th>
                <th>Куда отправленно</th>
                <th>Кто получает</th>
                <th>Чем отправленно</th>
                <th></th>
                <th>Состояние</th>
                <th>Замечания</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($modelWaiting)) { ?>
                <?php foreach($modelWaiting as $item) { ?>
                    <?php if(!empty($item->part_parcel)) { ?>
                        <?php foreach($item->part_parcel as $itemParcel) { ?>
                            <tr>
                                <td><?=$item->id?></td>
                                <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                                <td><?=(!empty($listAdmin[(string)$item->who_sent]) ? $listAdmin[(string)$item->who_sent] : 'None');?></td>
                                <td><?=(!empty($listWarehouse[$item->from_where_send]) ? $listWarehouse[$item->from_where_send] : 'None');?></td>
                                <td><?=$listGoods[$itemParcel['goods_id']]?></td>
                                <td><?=$itemParcel['goods_count']?></td>
                                <td><?=$listWarehouse[$item->where_sent]?></td>
                                <td><?=(!empty($item->who_gets) ? $item->who_gets : '')?></td>
                                <td><?=(!empty($item->delivery) ? $item->delivery : '')?></td>
                                <td><i class="fa fa-file-text"></i></td>
                                <td><?=($item->is_posting == 0  ? 'Отправлено' : 'Оприходовано')?></td>
                                <td><?=(!empty($itemParcel['comment']) ? $itemParcel['comment'] : 'нет')?></td>
                                <td>
                                    <?=($item->is_posting == 0  ? 
                                        Html::a('<i class="fa fa-shopping-cart"></i>',['/business/sending-waiting-parcel/posting-parcel','id'=>(string)$item->_id],
                                        [
                                            'class'=>'btn btn-default btn-block',
                                            'data-toggle'=>'ajaxModal',
                                            'title'=> 'Оприходовать'
                                        ]) : 
                                        '')?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
