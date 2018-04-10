<?php
use app\components\THelper;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use app\models\Warehouse;

$listRepresentative = Warehouse::getListHeadAdmin();
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_report_charges_warehouse') ?></h3>
</div>
<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/sale-report/report-charges-warehouse',
        'options' => ['name' => 'selectFilters'],
    ]); ?>

    <div class="col-md-5 m-b">
        <?= DatePicker::widget([
            'name' => 'date',
            'value' => $request['date'],
            'removeButton' => false,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm',
                'startView'=>'year',
                'minViewMode'=>'months',
            ]
        ]); ?>
    </div>
    <div class="col-md-5 m-b">
        <?= Select2::widget([
            'name' => 'representative',
            'data' => $listRepresentative,
            'value'=> (!empty($request['representative']) ? $request['representative'] : ''),
            'options' => [
                'placeholder' => THelper::t('representative'),
                'id' => 'admin-list',
                'class' => 'form-control',
            ]
        ]);
        ?>
    </div>

    <div class="col-md-2 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success btn-block']) ?>
    </div>

<!--    <div class="col-md-1 m-b">-->
<!--        --><?php //=Html::a('<i class="fa fa-file-o"></i>','#',['class'=>'btn btn-default btn-block exportExcel','title'=>'Выгрузка в excel'])?>
<!--    </div>-->

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right">

    </div>
</div>


<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th><?=THelper::t('date')?></th>
                <th><?=THelper::t('warehouse')?></th>
                <th><?=THelper::t('representative')?></th>
                <th><?=THelper::t('percent')?></th>
                <th><?=THelper::t('amount_repayment')?></th>
                <th><?=THelper::t('deduction')?></th>
                <th><?=THelper::t('total')?></th>
                <th><?=THelper::t('goods_turnover')?></th>
                <th><?=THelper::t('issued_for_amount')?></th>
                <th></th>
            </tr>
            </thead>

            <?php if(!empty($report)) { ?>
                <tbody>
                <?php foreach($report as $item) { ?>
                    <tr>
                        <td><?=$request['date'];?></td>
                        <td><?=$item['title']?></td>
                        <td><?=(!empty($listRepresentative[$item['representative_id']]) ? $listRepresentative[$item['representative_id']] : '')?></td>
                        <td><?=$item['percent']?></td>
                        <td><?=$item['accrued']?></td>
                        <td><?=$item['deduction']?></td>
                        <td><?=$item['repayment']?></td>
                        <td><?=$item['goods_turnover']?></td>
                        <td><?=$item['issued_for_amount']?></td>
                        <td>
                            <?php foreach ($item['goods'] as $itemGoods) { ?>
                                <?=$itemGoods['title']?> - <?=$itemGoods['count']?> шт (на <?=$itemGoods['price']?>)<br/>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } ?>

        </table>
    </div>

</section>



<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 6, "desc" ]]
    });

    $('.exportExcel').on('click',function (e) {
        e.preventDefault();

        formFilter = $('form[name="selectFilters"]');
        formFilter.attr('action','<?=\yii\helpers\Url::to(['sale-report/report-project-vipcoin-excel'])?>').submit();
        setTimeout(function() { formFilter.attr('action','<?=\yii\helpers\Url::to(['sale-report/report-project-vipcoin'])?>') }, 5000);
    })

</script>