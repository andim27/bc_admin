<?php
use app\components\THelper;
use app\components\AlertWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use app\models\PartsAccessories;
use kartik\widgets\DatePicker;

$layoutDate = <<< HTML
    {input1}
    {separator}
    {input2}
HTML;

$idMyWarehouse = Warehouse::getIdMyWarehouse();
$listWarehouse = Warehouse::getArrayWarehouse();
$listGoods = PartsAccessories::getListPartsAccessories();
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('cancellation_warehouse') ?></h3>
</div>


<?php if(!empty($idMyWarehouse)){?>

    <div class="row">
        <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
    </div>

    <div class="row">

        <?php $formStatus = ActiveForm::begin([
            'action' => '/' . $language . '/business/parts-accessories-in-warehouse/cancellation-warehouse',
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

        <div class="col-md-1 m-b">
            <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
        </div>
        <div class="col-md-1 m-b">
            <?=Html::a('<i class="fa fa-file-o"></i>','#',['class'=>'btn btn-default btn-block exportExcel','title'=>'Выгрузка в excel'])?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

    <?php if(!empty($model)) { ?>
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th><?=THelper::t('date_cancellation')?></th>
                        <th><?=THelper::t('product')?></th>
                        <th><?=THelper::t('count')?></th>
                        <th><?=THelper::t('what_warehouse_cancellation')?></th>
                        <th><?=THelper::t('who_cancellation')?></th>
                        <th><?=THelper::t('reason_cancellation')?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($model as $k=>$item) { ?>
                        <tr>
                            <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                            <td><?=$listGoods[(string)$item->parts_accessories_id]?></td>
                            <td><?=$item->number?></td>
                            <td><?=$listWarehouse[(string)$item->admin_warehouse_id]?></td>
                            <td><?=(!empty($item->adminInfo) ? $item->adminInfo->secondName . ' ' .$item->adminInfo->firstName : '')?></td>
                            <td><?=$item->comment?></td>
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
    $('.exportExcel').on('click',function (e) {
        e.preventDefault();

        formFilter = $('form[name="saveStatus"]');
        formFilter.attr('action','<?=\yii\helpers\Url::to(['parts-accessories-in-warehouse/cancellation-warehouse-exel'])?>').submit();
        setTimeout(function() { formFilter.attr('action','<?=\yii\helpers\Url::to(['parts-accessories-in-warehouse/cancellation-warehouse'])?>') }, 5000);
    })
</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>


