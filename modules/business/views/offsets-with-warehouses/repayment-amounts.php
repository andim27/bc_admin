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

    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/offsets-with-warehouses/add-update-repayment-amounts'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
    </div>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>№</th>
                    <th>
                        <?=THelper::t('warehouse')?>
                    </th>
                    <th>
                        <?=THelper::t('goods')?>
                    </th>
                    <th>
                        <?=THelper::t('amount_warehouse')?>
                    </th>
                    <th>
                        <?=THelper::t('amount_representative')?>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $k=>$item) { ?>
                    <tr>
                        <td><?=($k+1)?></td>
                        <td><?=$listWarehouse[(string)$item->warehouse_id]?></td>
                        <td><?=$listProduct[(string)$item->product_id]?></td>
                        <td>
                            <?=$item->price?>
                        </td>
                        <td>
                            <?=$item->price_representative?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/offsets-with-warehouses/add-update-repayment-amounts','id'=>(string)$item->warehouse_id], ['data-toggle'=>'ajaxModal']) ?>
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


