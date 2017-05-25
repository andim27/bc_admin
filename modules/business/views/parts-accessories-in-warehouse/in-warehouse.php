<?php
use app\components\THelper;
use app\components\AlertWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use app\models\PartsAccessories;

$idMyWarehouse = Warehouse::getIdMyWarehouse();

$listGoods = PartsAccessories::getListPartsAccessories();
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('in_warehouse') ?></h3>
</div>


<?php if(!empty($idMyWarehouse)){?>

    <div class="row">
        <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
    </div>

    <div class="row">

        <?php $formStatus = ActiveForm::begin([
            'action' => '/' . $language . '/business/parts-accessories-in-warehouse/in-warehouse',
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

    <?php if(!empty($model)) { ?>
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Наличие</th>
                        <th>Реализовано за период</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($model as $k=>$item) { ?>
                        <tr>
                            <td><?=$listGoods[(string)$item->parts_accessories_id]?></td>
                            <td><?=$item->number?></td>
                            <td><?=(!empty($implementation[$listGoods[(string)$item->parts_accessories_id]]) ? $implementation[$listGoods[(string)$item->parts_accessories_id]] : '0')?></td>
                            <td>
                                <?php if($item->number>0){ ?>
                                <?=Html::a('Списать',['/business/parts-accessories-in-warehouse/cancellation','goodsID'=>(string)$item->parts_accessories_id],['data-toggle'=>'ajaxModal','class'=>'btn btn-danger'])?>
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


