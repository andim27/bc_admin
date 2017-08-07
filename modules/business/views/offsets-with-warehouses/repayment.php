<?php
use app\components\THelper;
use yii\bootstrap\Html;
use app\components\AlertWidget;
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_repayment') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/offsets-with-warehouses/add-repayment','warehouse_id'=>$id],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        Сумма для выплаты на данный момент -
        <span class ="<?=($differenceRepaymentNow>=0 ? 'text-danger' : 'text-success')?>">
            <?=abs($differenceRepaymentNow)?>
        </span>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th><?=THelper::t('date')?></th>
                        <th><?=THelper::t('difference_before_repayment')?></th>
                        <th><?=THelper::t('amount_repayment')?></th>
                        <th><?=THelper::t('direction_repayment')?></th>
                        <th><?=THelper::t('difference_after_repayment')?></th>
                        <th><?=THelper::t('method_repayment')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($model)) { ?>
                        <?php foreach($model as $item) { ?>
                            <tr>
                                <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                                <td>
                                    <span class ="<?=($item->difference_repayment>=0 ? 'text-danger' : 'text-success')?>">
                                        <?=abs($item->difference_repayment)?>
                                    </span>
                                </td>
                                <td><?=$item->repayment;?></td>
                                <td><?=THelper::t($item->type_repayment);?></td>
                                <td>
                                    <?php
                                        $difference_after_repayment = ($item->type_repayment == 'company_warehouse'
                                            ? ($item->difference_repayment+$item->repayment)
                                            : ($item->difference_repayment-$item->repayment)
                                        )
                                    ?>
                                    <span class ="<?=($difference_after_repayment>=0 ? 'text-danger' : 'text-success')?>">
                                        <?=abs($difference_after_repayment)?>
                                    </span>
                                </td>
                                <td><?=$item->method_repayment;?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </section>
    </div>
</div>

<script type="text/javascript">
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

    $('.btnflWarehouse').on('change',function () {
        if($(this).is(':checked')) {
            $(this).closest('.row').find('.blChangeWarehouse select[name="listWarehouse"]').prop( "disabled", false ).show();
            $(this).closest('.row').find('.blChangeWarehouse select[name="listCountry"]').prop( "disabled", true ).hide();
        } else{
            $(this).closest('.row').find('.blChangeWarehouse select[name="listWarehouse"]').prop( "disabled", true ).hide();
            $(this).closest('.row').find('.blChangeWarehouse select[name="listCountry"]').prop( "disabled", false ).show();
        }
    })
</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>
