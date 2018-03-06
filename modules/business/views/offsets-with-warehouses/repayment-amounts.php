<?php
use app\components\THelper;
use yii\bootstrap\Html;
use app\components\AlertWidget;

use app\models\Warehouse;
use app\models\PartsAccessories;


$listWarehouse = Warehouse::getArrayWarehouse();

$listProduct = PartsAccessories::getListPartsAccessoriesForSaLe();
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_repayment_amounts') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <div class="col-md-12 form-group ">
        <?php //=Html::a('<i class="fa fa-plus"></i>',['/business/offsets-with-warehouses/add-update-repayment-amounts'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
        <?=Html::a('Таблица взысканий представителям',['/business/offsets-with-warehouses/recovery-for-repayment','object'=>'representative'],['class'=>'btn btn-default'])?>
        <?=Html::a('Таблица процентов представителям',['/business/offsets-with-warehouses/percent-for-repayment','object'=>'representative'],['class'=>'btn btn-default'])?>
        <?=Html::a('Таблица процентов складам',['/business/offsets-with-warehouses/percent-for-repayment','object'=>'warehouse'],['class'=>'btn btn-default'])?>
        <?=Html::a('Просчитать за прошлый месяц',['/business/offsets-with-warehouses/calculation-repayment'],['class'=>'btn btn-default'])?>
    </div>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>№</th>
                    <th><?=THelper::t('warehouse')?></th>
                    <th><?=THelper::t('goods')?></th>
                    <th><?=THelper::t('date')?></th>
                    <th><?=THelper::t('amount_warehouse')?></th>
                    <th><?=THelper::t('amount_representative')?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $k=>$item) { ?>
                    <?php
                    $priceWarehouse = $dateWarehouse = '-';
                    if(!empty($item->prices_warehouse)){
                        $infoPriceWarehouse = (array)$item->prices_warehouse;
                        end($infoPriceWarehouse);
                        $dateWarehouse = key($infoPriceWarehouse);
                        $priceWarehouse = $infoPriceWarehouse[$dateWarehouse]['price'];
                    }

                    $priceRepresentative = $dateRepresentative = '-';
                    if(!empty($item->prices_representative)) {
                        $infoPriceRepresentative = (array)$item->prices_representative;
                        end($infoPriceRepresentative);
                        $dateRepresentative = key($infoPriceRepresentative);
                        $priceRepresentative = $infoPriceRepresentative[$dateRepresentative]['price'];
                    }
                    ?>
                    <tr>
                        <td><?=($k+1)?></td>
                        <td><?=$listWarehouse[(string)$item->warehouse_id]?></td>
                        <td><?=$listProduct[(string)$item->product_id]?></td>
                        <td><?=$dateRepresentative?></td>
                        <td><?=$priceWarehouse;?></td>
                        <td><?=$priceRepresentative;?></td>
                        <td>
                            <?php //= Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/offsets-with-warehouses/add-update-repayment-amounts','id'=>(string)$item->warehouse_id], ['data-toggle'=>'ajaxModal']) ?>
                            <?php //= Html::a('<i class="fa fa-trash-o" title="удалить"></i>', ['/business/offsets-with-warehouses/remove-repayment-amounts','id'=>$item->warehouse_id->__toString()],['data' =>['confirm'=>'Вы действительно хотите удалить?','method'=>'post']]); ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>

<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>