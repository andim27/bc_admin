<?php
use app\components\THelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\AlertWidget;
use kartik\widgets\DatePicker;


$negative_payment = false;
$notPaid = false;
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_offsets_with_warehouse') ?></h3>
</div>


<div class="row blQuery">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/offsets-with-warehouses/list-repayment-warehouse',
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
                        <th><?=THelper::t('deduction')?></th>
                        <th><?=THelper::t('total')?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($info)) { ?>
                        <?php foreach($info as $k=>$itemWarehouse) { ?>

                            <?php
                            $repayment = $itemWarehouse['amount_repayment']-$itemWarehouse['deduction'];
                            if($repayment<0){
                                $negative_payment = true;
                            }
                            ?>

                            <tr>
                                <td><?=$itemWarehouse['title']?></td>
                                <td><?=$itemWarehouse['amount_repayment']?></td>
                                <td><?=$itemWarehouse['deduction']?></td>
                                <td><?=$repayment?></td>
                                <td>
                                    <?php if($itemWarehouse['paid'] == true){ ?>
                                        <i class="fa fa-check-square text-success"></i>
                                    <?php } else { ?>
                                        <?php $notPaid = true;?>
                                        <i class="fa fa-minus-square text-danger"></i>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <th colspan="5">
                                <?=(($repayment_paid === true || $negative_payment== true) ?
                                    '' :
                                    Html::a(THelper::t('make_payment'),['offsets-with-warehouses/make-repayment-warehouse','dateRepayment'=>$request['date_repayment'],'representative_id'=>$userId],['class'=>'btn btn-default btnHideAfterClick']))?>

                            <?=(($userType=='mainWarehouse' && $negative_payment==false && $notPaid==true) ?
                                    Html::a(THelper::t('make_payment_all_warehouse'),['offsets-with-warehouses/make-repayment-all-warehouse','dateRepayment'=>$request['date_repayment']],['class'=>'btn btn-default btnHideAfterClick']) :
                                    '')?>

                            </th>
                        </tr>
                    </tfooter>
                </table>
            </div>

        </section>
    </div>
</div>

<div class="m-b-md">
    <?=Html::a(THelper::t('table_penalties_from_warehouses'),['/business/offsets-with-warehouses/recovery-for-repayment','object'=>'warehouse','representative_id'=>$userId],['class'=>'btn btn-default','target'=>'_blank'])?>
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
