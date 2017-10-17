<?php
use app\components\THelper;
use yii\helpers\Html;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;

$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

?>


<div class="row">
    <div class="col-md-offset-6 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-wrench"></i>',[
            '/business/submit-execution-posting/add-edit-sending-repair'
        ],[
            'class'=>'btn btn-default btn-block',
            'data-toggle'=>'ajaxModal',
            'title'=>'Отправить на ремонт'
        ]);?>
    </div>
    <div class="col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',[
            '/business/submit-execution-posting/add-edit-sending-execution'
        ],[
            'class'=>'btn btn-default btn-block',
            'data-toggle'=>'ajaxModal',
            'title'=>'Отправить на исполнение'
        ])?>
    </div>
</div>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>Дата добавдения</th>
                <th>
                    <?=THelper::t('name_product')?>
                </th>
                <th>
                    <?=THelper::t('count')?>
                </th>
                <th>
                    Что собираем
                </th>
                <th>Дата прихода</th>
                <th>Кто собирает</th>
                <th>Кому переданно</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $item) { ?>
                <?php if(!empty($item->list_component)){?>
                <?php foreach ($item->list_component as $k=>$itemList) { ?>
                    <?php if($item->received == 0 && $item->posting != 1){?>
                    <tr>
                        <td><?= $item->date_create->toDateTime()->format('Y-m-d H:i:s') ?></td>
                        <td><?= $listGoods[(string)$itemList['parts_accessories_id']]?></td>
                        <td><?= ($itemList['number'] * $item->number) + $itemList['reserve'] ?></td>
                        <td><?= ($item->one_component == 1 ? THelper::t('component_replacement') : $listGoods[(string)$item->parts_accessories_id]) ?></td>
                        <td><?= (!empty($item->date_execution) ? $item->date_execution->toDateTime()->format('Y-m-d H:i:s') : '') ?></td>
                        <td><?= $listSuppliers[(string)$item->suppliers_performers_id] ?></td>
                        <td><?= ((!empty($item->repair) && $item->repair==1) ? 'Ремонт' : $item->fullname_whom_transferred) ?></td>
                        <td>
                            <?=  ((empty($item->repair) && $item->repair==0)
                                ? Html::a('<i class="fa fa-edit"></i>', ['/business/submit-execution-posting/add-edit-sending-execution','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal'])
                                : '');
                            ?>
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



