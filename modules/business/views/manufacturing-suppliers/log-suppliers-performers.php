<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use kartik\widgets\DatePicker;

$layoutDate = <<< HTML
    {input1}
    {separator}
    {input2}
HTML;

$listSuppliersPerformers=SuppliersPerformers::getListSuppliersPerformers();
$listGoods = PartsAccessories::getListPartsAccessories();
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('history_transaction') ?> «<?= $listSuppliersPerformers[$id] ?>»</h3>
</div>




<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/manufacturing-suppliers/log-suppliers-performers?id='.$id,
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-2 m-b">
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
                    <th>Дата</th>
                    <th>Действие</th>
                    <th>Кто проводил</th>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Цена</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $k=>$item) { ?>
                    <tr>
                        <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=THelper::t($item->action)?></td>
                        <td><?=$item->adminInfo->secondName . ' ' .$item->adminInfo->firstName?></td>
                        <td><?=$listGoods[(string)$item->parts_accessories_id]?></td>
                        <td><?=$item->number?></td>
                        <td><?=(!empty($item->money) ? $item->money . ' EUR' : '')?></td>
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


