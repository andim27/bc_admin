<?php
use app\components\THelper;
use yii\bootstrap\Html;
use app\components\AlertWidget;

use app\models\Warehouse;
use app\models\PartsAccessories;


/** @var $item \app\models\PercentForRepaymentAmounts */
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_table_percent_for_repayment') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/offsets-with-warehouses/add-update-percent-for-repayment','object'=>$object],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
    </div>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-percent-repayment table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th><?=THelper::t('representative')?></th>
                    <th><?=THelper::t('dop_price')?></th>
                    <th><?=THelper::t('percent')?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $item) { ?>
                    <tr>
                        <td>
                            <?= ($object == 'representative' ? $item->representative->username : $item->warehouse->title)  ?>
                        </td>
                        <td><?=$item->dop_price_per_warehouse;?></td>
                        <td>
                            <?php $arrayPercent = $item->turnover_boundary;?>
                            <?php if(!empty($arrayPercent)){ ?>
                                <?php foreach($arrayPercent as $kPercent=>$itemPercent){ ?>
                                    <?php $nextBorder = (!empty($arrayPercent[($kPercent+1)]) ? $arrayPercent[($kPercent+1)]['turnover_boundary'] : '...')?>
                                    <div class="row">
                                        <div class="col-md-8"><?=$itemPercent['turnover_boundary']?>-<?=$nextBorder?></div>
                                        <div class="col-md-4"><?=$itemPercent['percent']?>%</div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil" title="редактировать"></i>',
                                [
                                    '/business/offsets-with-warehouses/add-update-percent-for-repayment',
                                    'id'=>(string)$item->_id,
                                    'object'=>$object
                                ],
                                ['data-toggle'=>'ajaxModal']) ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>

<script>
    $('.table-percent-repayment').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>