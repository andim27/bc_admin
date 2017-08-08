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
    <div class="row">
        <div class="col-md-offset-9 col-md-3 form-group">
            <?=Html::a('<i class="fa fa-plus"></i>',['/business/sending-waiting-parcel/add-edit-parcel'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>№</th>
                <th><?=THelper::t('date_added');?></th>
                <th><?=THelper::t('who_sent');?></th>
                <th><?=THelper::t('from_sent')?></th>
                <th><?=THelper::t('part_parcel')?></th>
                <th><?=THelper::t('num')?></th>
                <th><?=THelper::t('to_sent')?></th>
                <th><?=THelper::t('who_gets')?></th>
                <th><?=THelper::t('than_sent')?></th>
                <th></th>
                <th><?=THelper::t('condition')?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($modelSending)) { ?>
                <?php foreach($modelSending as $item) { ?>
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
                                <td>
                                    <?=(!empty($item->documents) ?
                                        Html::a('<i class="fa fa-file-text text-success"></i></td>',Yii::getAlias('@parcelDocumentsUrl') . '/' . $item->id . '/' .$item->documents,['target'=>'_blank','title'=>$item->documents]) :
                                        '<i class="fa fa-file-text text-danger"></i></td>')?>
                                </td>
                                <td><?=($item->is_posting == 0  ? 'Отправлено' : 'Оприходовано')?></td>
                                <td>
                                    <?=($item->is_posting == 0  ?
                                        Html::a('<i class="fa fa-edit"></i>',['/business/sending-waiting-parcel/add-edit-parcel','id'=>(string)$item->_id],
                                        [
                                            'class'=>'btn btn-default btn-block',
                                            'data-toggle'=>'ajaxModal',
                                            'title'=> 'Редактировать'
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
