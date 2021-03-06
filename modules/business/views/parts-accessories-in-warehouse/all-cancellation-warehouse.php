<?php
use app\components\THelper;
use app\components\AlertWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use app\models\PartsAccessories;
use kartik\date\DatePicker;

$layoutDate = <<< HTML
    {input1}
    {separator}
    {input2}
HTML;

$listWarehouse = Warehouse::getArrayWarehouse();
$listShowroom = \app\models\api\Showrooms::getListForFilter();
$listGoods = PartsAccessories::getListPartsAccessories();
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('all_cancellation_warehouse') ?></h3>
</div>



<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
</div>

<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/parts-accessories-in-warehouse/all-cancellation-warehouse',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-4 m-b">
        <?= DatePicker::widget([
            'name' => 'from',
            'value' => $dateInterval['from'],
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'to',
            'value2' => $dateInterval['to'],
            'separator' => '-',
            'layout' => $layoutDate,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]
        ]); ?>
    </div>

    <div class="col-md-8 m-b">
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
                    <th></th>
                    <th>Дата списания</th>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Какой склад списал</th>
                    <th>Кто списал</th>
                    <th>Причины списания</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $k=>$item) { ?>
                    <tr date-transaction-id="<?=(string)$item->_id?>">
                        <td>
                            <?php if(empty($item->confirmation_action)){ ?>
                                <?=  Html::a('<i class="fa fa-flash text-warning"></i>', ['/business/parts-accessories-in-warehouse/confirmation-cancellation','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']); ?>
                            <?php } else if($item->confirmation_action == -1){ ?>
                                <i class="fa fa-times text-danger"></i>
                            <?php } else if($item->confirmation_action == 1){ ?>
                                <i class="fa fa-check text-success"></i>
                            <?php } ?>
                        </td>
                        <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=$listGoods[(string)$item->parts_accessories_id]?></td>
                        <td><?=$item->number?></td>
                        <td><?=(!empty($listWarehouse[(string)$item->admin_warehouse_id]) ? $listWarehouse[(string)$item->admin_warehouse_id] : (!empty($listShowroom[(string)$item->admin_warehouse_id]) ? $listShowroom[(string)$item->admin_warehouse_id] : '???')) ?></td>
                        <td><?=(!empty($item->adminInfo) ? $item->adminInfo->secondName . ' ' .$item->adminInfo->firstName : 'None')?></td>
                        <td><?=$item->comment?></td>
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


