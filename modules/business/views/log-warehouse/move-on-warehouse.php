<?php
use yii\helpers\ArrayHelper;

use app\components\THelper;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use app\models\Warehouse;
use app\models\LogWarehouse;
use kartik\widgets\DatePicker;

$listWarehouse = Warehouse::getArrayWarehouse();
$listAction = LogWarehouse::getAllAction();

$layoutDate = <<< HTML
    {input1}
    {separator}
    {input2}
HTML;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Логирование</h3>
</div>


<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/log-warehouse/move-on-warehouse',
        'options' => ['name' => 'formLog', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-3">
        <div class="input-group">
            <?= DatePicker::widget([
                'name' => 'from',
                'value' => $request['from'],
                'type' => DatePicker::TYPE_RANGE,
                'name2' => 'to',
                'value2' => $request['to'],
                'separator' => '-',
                'layout' => $layoutDate,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]); ?>
          </div>
    </div>

    <div class="col-md-2 m-b">
        <?=Html::dropDownList('infoWarehouse', $request['infoWarehouse'],
            ArrayHelper::merge([''=>THelper::t('all_warehouse')],$listWarehouse),[
                'class'=>'form-control infoWarehouse'
            ])?>
    </div>

    <div class="col-md-3 m-b">
        <?= Select2::widget([
            'name' => 'list_action',
            'data' => $listAction,
            'value' => (!empty($request['list_action']) ? $request['list_action'] : ''),
            'options' => [
                'placeholder' => 'Выберите действия',
                'multiple' => true
            ]
        ]);
        ?>
    </div>

    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success btn-block']) ?>
    </div>

    <div class="col-md-1 m-b">
        <?=Html::a('<i class="fa fa-file-o"></i>',['javascript:void(0);'],['class'=>'btn btn-default btn-block exportExcel','title'=>'Export Excel'])?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right"></div>
</div>


<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Действие</th>
                    <th>Кто проводил</th>
                    <th>Склад --></th>
                    <th>Склад <--</th>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Коментарий</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $k=>$item) { ?>
                    <tr data-id="<?=(string)$item->_id?>">
                        <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=THelper::t($item->action)?></td>
                        <td>
                            <?=(!empty($item->adminInfo->secondName) ? $item->adminInfo->secondName : '') ?>
                            <?=(!empty($item->adminInfo->firstName) ? $item->adminInfo->firstName : '') ?>
                        </td>
                        <td><?=(!empty($item->admin_warehouse_id) ? $item->adminWarehouseInfo->title : '')?></td>
                        <td><?=(!empty($item->on_warehouse_id) ? $item->onWarehouseInfo->title : '')?></td>
                        <td><?=$item->infoPartsAccessories->title?></td>
                        <td><?=$item->number?></td>
                        <td><?=(!empty($item->money) ? $item->money . ' EUR' : '')?></td>
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

    $('.exportExcel').on('click',function (e) {
        e.preventDefault();

        formFilter = $('form[name="formLog"]');

        if(formFilter.find('.infoWarehouse').val()==''){
            alert('Выберите склад');
            return false;
        }

        formFilter.attr('action','<?=\yii\helpers\Url::to(['log-warehouse/move-on-warehouse-excel'])?>').submit();
    })
</script>