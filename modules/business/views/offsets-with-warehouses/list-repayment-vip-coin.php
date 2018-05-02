<?php
use app\components\THelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\AlertWidget;
use kartik\widgets\DatePicker;


$notPaid = true;
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_offsets_with_warehouse_vipcoin') ?></h3>
</div>


<div class="row blQuery">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/offsets-with-warehouses/list-repayment-vip-coin',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-10">
        <div class="input-group">
            <?= DatePicker::widget([
                'name' => 'date_repayment',
                'value'=>$request['date_repayment'],
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm',
                    'startView'=>'year',
                    'minViewMode'=>'months',
                ]
            ]); ?>
        </div>
    </div>

    <div class="col-md-2 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-block btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th><?=THelper::t('warehouse')?></th>
                        <th><?=THelper::t('amount_repayment')?></th>
                        <th><?=THelper::t('repayment')?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($info)) { ?>
                        <?php foreach($info as $k=>$itemWarehouse) { ?>
                            <tr>
                                <td><?=$itemWarehouse['title']?></td>
                                <td><?=$itemWarehouse['amount']?></td>
                                <td><?=$itemWarehouse['issued_for_amount']?></td>
                                <td>
                                    <?php if(($itemWarehouse['amount']-$itemWarehouse['issued_for_amount']) == 0){ ?>
                                        <i class="fa fa-check-square text-success"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-minus-square text-danger"></i>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <th colspan="4">
                                <?=(($notPaid==true) ?
                                    Html::a(THelper::t('make_payment_for_vipcoin'),['offsets-with-warehouses/make-repayment-vip-coin','dateRepayment'=>$request['date_repayment']],['class'=>'btn btn-default btnHideAfterClick']) :
                                    '')?>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="4">
                                <?php if(!empty($notUseOrder)){ ?>
                                <div class="alert alert-danger">
                                    <h4>Не распределенные заказы:</h4>
                                    <ul>
                                    <?php foreach ($notUseOrder as $item) { ?>
                                        <li>Заказ <?=$item['country']?> <?=$item['city']?></li>
                                    <?php } ?>
                                    </ul>
                                </div>
                                <?php } ?>
                            </th>
                        </tr>
                    </tfooter>
                </table>
            </div>

        </section>
    </div>
</div>


<script type="text/javascript">
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 1, "desc" ]]
    });

    $('.btnHideAfterClick').on('click',function () {
        $(this).hide();
    })
</script>
