<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_parts_ordering') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/manufacturing-suppliers/add-update-parts-ordering?action=add'],['class'=>'btn btn-default btn-block addPartsOrdering','data-toggle'=>'ajaxModal'])?>
    </div>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?=THelper::t('sale_date_create')?>
                    </th>
                    <th>
                        <?=THelper::t('name_product')?>
                    </th>
                    <th>
                        <?=THelper::t('count')?>
                    </th>
                    <th>
                        <?=THelper::t('curency')?>
                    </th>
                    <th>
                        <?=THelper::t('price')?> <?=THelper::t('pcs')?>
                    </th>
                    <th>
                        <?=THelper::t('amount')?>
                    </th>
                    <th>
                        <?=THelper::t('date_receipt')?>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $item) { ?>
                    <tr>
                        <td><?=$item->dateCreate->toDateTime()->format('Y-m-d H:m:s')?></td>
                        <td><?=$item->partsAccessories->title?></td>
                        <td><?=$item->number?></td>
                        <td><?=THelper::t($item->currency)?></td>
                        <td><?=round($item->price/$item->number,2)?></td>
                        <td><?=$item->price?></td>
                        <td><?=$item->dateReceipt->toDateTime()->format('Y-m-d')?></td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/manufacturing-suppliers/add-update-parts-ordering','id'=>$item->_id->__toString(),'action'=>'edit'], ['data-toggle'=>'ajaxModal']) ?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/manufacturing-suppliers/remove-parts-ordering','id'=>$item->_id->__toString()],['data' =>['confirm'=>'Вы действительно хотите удалить?','method'=>'post']]) ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>

<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>

<script type="text/javascript">

    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>
