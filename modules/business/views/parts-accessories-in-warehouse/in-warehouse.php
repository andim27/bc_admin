<?php
use app\components\THelper;
use app\components\AlertWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use app\models\PartsAccessories;
use app\models\Users;
use kartik\date\DatePicker;


$layoutDate = <<< HTML
    {input1}
    {separator}
    {input2}
HTML;

//$idMyWarehouse = Warehouse::getIdMyWarehouse();

$listGoods = PartsAccessories::getListPartsAccessories();

$listWarehouse = [];
if(Warehouse::checkWarehouseKharkov($idWarehouse)){
    $listWarehouse = Warehouse::getArrayWarehouse();
} else {
    if(Users::checkHeadAdmin()){
        $listWarehouse = Warehouse::getListHeadAdminWarehouse();
    }
}


?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('in_warehouse') ?></h3>
</div>


<?php if(!empty($idWarehouse)){?>

    <div class="row">
        <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
    </div>

    <div class="row">

        <?php $formStatus = ActiveForm::begin([
            'action' => '/' . $language . '/business/parts-accessories-in-warehouse/in-warehouse',
            'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
        ]); ?>

        <div class="col-md-4 m-b">
            <?= DatePicker::widget([
                'name' => 'dateInterval[from]',
                'value' => $request['dateInterval']['from'],
                'type' => DatePicker::TYPE_RANGE,
                'name2' => 'dateInterval[to]',
                'value2' => $request['dateInterval']['to'],
                'separator' => '-',
                'layout' => $layoutDate,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]); ?>
        </div>

        <?php if(!empty($listWarehouse)) { ?>
        <div class="col-md-2 m-b">
            <?=Html::dropDownList('listWarehouse',$request['listWarehouse'],$listWarehouse,[
                'class'=>'form-control listWarehouse',
                'id'=>'listWarehouse',
                'promt'=>'Мои склады',
                'options' => []
            ])?>
        </div>
        <?php } ?>

        <div class="col-md-6 m-b">
            <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php if(!empty($model)) { ?>
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th><?=THelper::t('product')?></th>
                        <th><?=THelper::t('number_in_stock')?></th>
<!--                        <th>--><?php//=THelper::t('sold_during_period')?><!--</th>-->
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($model as $k=>$item) { ?>
                        <tr>
                            <td><?=$listGoods[(string)$item->parts_accessories_id]?></td>
                            <td><?=$item->number?></td>
<!--                            <td>--><?php //=(!empty($implementation[$listGoods[(string)$item->parts_accessories_id]]) ? $implementation[$listGoods[(string)$item->parts_accessories_id]] : '0'); ?><!--</td>-->
                            <td>
                                <?php if(!empty($arrayProcurementPlanning[(string)$item->parts_accessories_id]) && $arrayProcurementPlanning[(string)$item->parts_accessories_id]=='wait'){ ?>
                                    <i class="fa fa-exclamation-triangle procurementPlanningWait" data-container="body" data-toggle="popover" data-placement="left" data-content="Данный товар доставляется"></i>
                                <?php } else if(!empty($arrayProcurementPlanning[(string)$item->parts_accessories_id]) && $arrayProcurementPlanning[(string)$item->parts_accessories_id]=='attention'){ ?>
                                    <i class="fa fa-exclamation-triangle procurementPlanningAttention" data-container="body" data-toggle="popover" data-placement="left" data-content="Данный товар заканчивается"></i>
                                <?php } else if(!empty($arrayProcurementPlanning[(string)$item->parts_accessories_id]) && $arrayProcurementPlanning[(string)$item->parts_accessories_id]=='alert'){ ?>
                                    <i class="fa fa-exclamation-triangle procurementPlanningAlert" data-container="body" data-toggle="popover" data-placement="left" data-content="Данного товара не достатотчно">
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($item->number>0){ ?>
                                <?=Html::a(THelper::t('write_off'),['/business/parts-accessories-in-warehouse/cancellation','goodsID'=>(string)$item->parts_accessories_id],['data-toggle'=>'ajaxModal','class'=>'btn btn-danger'])?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php } ?>

<?php } ?>


<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>


