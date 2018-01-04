<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('history_transaction') ?> «<?= $listGoods[$id] ?>»</h3>
</div>




<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/manufacturing-suppliers/log-suppliers-performers?id='.$id,
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-2 m-b">
        <?= Html::input('text','from',$dateInterval['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd'])?>
    </div>

    <div class="col-md-2 m-b">
        <?= Html::input('text','to',$dateInterval['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd'])?>
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
            {"data": "comment"}
        ],
        "order": [[ 0, "desc" ]]
    });


</script>

<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>


