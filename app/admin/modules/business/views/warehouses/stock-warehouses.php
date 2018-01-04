<?php
use app\components\THelper;
use yii\bootstrap\Html;
use app\components\AlertWidget;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use app\models\PartsAccessories;
use kartik\widgets\Select2;


$listHeadAdmin = Warehouse::getListHeadAdmin();
$listWarehouse = Warehouse::getArrayWarehouse();
$listRepresentative = Warehouse::getListHeadAdmin();
$listProduct = PartsAccessories::getListPartsAccessoriesForSaLe();

?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_stock_warehouses') ?></h3>
</div>

<?php if($hideFilter != 1){ ?>
<div class="row blQuery">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/warehouses/stock-warehouses',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

        <div class="col-md-2 m-b blChangeWarehouse">
            <?= Select2::widget([
                'name' => 'listRepresentative',
                'value' => (!empty($request['listRepresentative']) ? $request['listRepresentative'] : ''),
                'data' => $listRepresentative,
                'options' => [
                    'placeholder'   => 'Выберите представителя',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ]
            ]);
            ?>
        </div>



    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php } ?>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?=THelper::t('representative')?>
                    </th>
                    <th>
                        <?=THelper::t('warehouse')?>
                    </th>
                    <th>
                        <?=THelper::t('goods')?>
                    </th>
                    <th>
                        <?=THelper::t('stock')?>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $item) { ?>
                    <?php foreach ($listProduct as $k=>$itemProduct) { ?>
                        <tr>
                            <td><?= ((!empty($item->headUser) && !empty($listHeadAdmin[(string)$item->headUser])) ? $listHeadAdmin[(string)$item->headUser] : '')?></td>
                            <td><?= $item->title; ?></td>
                            <td><?= $itemProduct; ?></td>
                            <td><?= (!empty($item->stock[$k]['count']) ? $item->stock[$k]['count'] : 0); ?></td>
                            <td>
                                <?= Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/warehouses/update-stock-warehouses','id'=>(string)$item->_id], ['data-toggle'=>'ajaxModal']) ?>
                            </td>
                        </tr>
                    <?php } ?>
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


