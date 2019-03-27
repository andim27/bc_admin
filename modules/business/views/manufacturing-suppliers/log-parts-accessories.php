<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\PartsAccessories;
use kartik\widgets\DatePicker;

$layoutDate = <<< HTML
    {input1}
    {separator}
    {input2}
HTML;

$listGoods = PartsAccessories::getListPartsAccessories();
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('history_transaction') ?> «<?= $listGoods[$id] ?>»</h3>
</div>




<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/manufacturing-suppliers/log-parts-accessories?id='.$id,
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


<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>Дата</th>
                <th>Действие</th>
                <th>Кто проводил</th>
                <th>Количество</th>
                <th>Цена</th>
                <th> <?=THelper::t('sidebar_suppliers_performers')?></th>
                <th>Коментарий</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</section>

<script>
    var table = $('.table-translations');

    table = table.dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/' + LANG + '/business/manufacturing-suppliers/log-parts-accessories',
            "data": function ( d ) {
                d.id = "<?=$id?>",
                d.from = "<?= $dateInterval['from'] ?>";
                d.to = "<?= $dateInterval['to'] ?>"
            }
        },
        "columns": [
            {"data": "date_create"},
            {"data": "action"},
            {"data": "who_performed_action"},
            {"data": "number"},
            {"data": "money"},
            {"data": "manuf"},
            {"data": "comment"}
        ],
        "order": [[ 0, "desc" ]]
    });


</script>

<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>


