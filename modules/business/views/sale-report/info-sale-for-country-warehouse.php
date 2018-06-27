<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Settings;

use app\models\Products;
use yii\helpers\ArrayHelper;


$listCountry = Settings::getListCountry();


$listPack = Products::getListPack();
$listGoods = Products::getListGoods();

$amount = [
    'ordering'  => 0,
    'issued'    => 0,
    'in_stock'  => 0,
    'send'      => 0,
    'repair'    => 0,
    'margin'    => 0,
];


?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('report_sale_for_country_warehouse') ?></h3>
</div>
<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/sale-report/info-sale-for-country-warehouse',
        'options' => ['name' => 'selectFilters'],
    ]); ?>


    <div class="col-md-2 m-b">
        <?=Html::dropDownList('listCountry',(!empty($request['listCountry']) ? $request['listCountry'] : 'all'),
            ArrayHelper::merge(['all' => 'Все страны'],$listCountryWarehouse),[
                'class'=>'form-control'
            ])?>
    </div>

    <div class="col-md-1">
        <label class="control-label switch-center"></label>
        <label class="switch">
            <input value="1" class="btnflGoods" type="checkbox" name="flGoods" <?= ((!empty($request['flGoods']) && $request['flGoods']==1) ? 'checked="checked"' : '')?>/>
            <span></span>
        </label>
    </div>

    <div class="col-md-2 m-b blChangeGoods">
        <?=Html::dropDownList('listPack',(!empty($request['listPack']) ? $request['listPack'] : 'all'),
            ArrayHelper::merge(['all' => 'Все паки'],$listPack),[
            'class'=>'form-control listPack',
            'id'=>'listPack',
            'disabled' => ((!empty($request['flGoods']) && $request['flGoods']==1) ? false : true),
            'style' =>  ((!empty($request['flGoods']) && $request['flGoods']==1) ? '' : 'display:none'),
            'options' => []
        ])?>

        <?=Html::dropDownList('listGoods',(!empty($request['listGoods']) ? $request['listGoods'] : 'all'),$listGoods,[
            'class'=>'form-control listGoods',
            'id'=>'listGoods',
            'disabled' => ((!empty($request['flGoods']) && $request['flGoods']==1) ? true : false),
            'style' =>  ((!empty($request['flGoods']) && $request['flGoods']==1) ? 'display:none' : ''),
            'options' => []
        ])?>
    </div>

    <div class="col-md-1 m-b">
        <?=Html::label(THelper::t('number_send') .  ' из Харькова','send_kh')?>
    </div>
    <div class="col-md-1 m-b">
        <?=Html::checkbox('send_kh',($request['send_kh']==0 ? false : true),['id'=>'send_kh'])?>
    </div>

    <div class="col-md-1 m-b">
        <?=Html::a('<i class="fa fa-file-o"></i>','#',['class'=>'btn btn-default btn-block exportExcel','title'=>'Выгрузка в excel'])?>
    </div>

    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-block btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right">

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th>
                            <?=THelper::t('country')?>
                        </th>
                        <th>
                            <?=THelper::t('warehouse')?>
                        </th>
                        <th>
                            <?=((!empty($request['flGoods']) && $request['flGoods']==1) ? THelper::t('business_product') : THelper::t('goods'))?>
                        </th>
                        <th>
                            <?=THelper::t('number_all_ordering')?>
                        </th>
                        <th>
                            <?=THelper::t('number_issue')?>
                        </th>                        
                        <?php if(!empty($request['listGoods'])) { ?>
                        <th>
                            <?=THelper::t('number_in_stock')?>
                        </th>
                        <th>
                            <?=THelper::t('number_send')?>
                        </th>
                        <?php } ?>
                        <th>
                            <?=THelper::t('number_difference')?>
                        </th>
                        <th>
                            <?=THelper::t('number_repair')?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($infoSale)) { ?>
                            <?php foreach($infoSale as $k=>$itemWarehouse) { ?>
                                <?php foreach($itemWarehouse as $kWarehouse=>$itemGoods) { ?>
                                    <?php foreach($itemGoods as $kGoods=>$item) { ?>
                                        <?php
                                            $margin = $item['issued'] + $item['send'] + $item['in_stock'] - $item['all'];

                                            $amount['ordering'] += $item['all'];
                                            $amount['issued'] += $item['issued'];
                                            $amount['in_stock'] += $item['in_stock'];
                                            $amount['send'] += $item['send'];
                                            $amount['repair'] += $item['repair'];
                                            $amount['margin'] += $margin;
                                        ?>
                                        <tr>
                                            <td><?=(!empty($listCountry[$k]) ? $listCountry[$k] : 'none') ?></td>
                                            <td><?=$kWarehouse?></td>
                                            <td><?=$kGoods?></td>
                                            <td><?=$item['all']?></td>
                                            <td><?=$item['issued']?></td>
                                            <?php if(!empty($request['listGoods'])) { ?>
                                            <td><?=$item['in_stock']?></td>
                                            <td><?=$item['send']?></td>
                                            <?php } ?>
                                            <td>
                                                <span class="<?=($margin>=0 ? 'text-success' : 'text-danger')?>"><?=($margin)?></span>
                                            </td>
                                            <td><?=$item['repair']?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <th>Итого:</th>
                            <th></th>
                            <th></th>
                            <th><?=$amount['ordering']?></th>
                            <th><?=$amount['issued']?></th>
                            <?php if(!empty($request['listGoods'])) { ?>
                            <th><?=$amount['in_stock']?></th>
                            <th><?=$amount['send']?></th>
                            <?php } ?>
                            <th><?=$amount['margin']?></th>
                            <th><?=$amount['repair']?></th>
                        </tr>
                    </tfooter>
                </table>
            </div>

        </section>
    </div>
</div>


<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });


    $('.btnflGoods').on('change',function () {
        if($(this).is(':checked')) {
            $(this).closest('.row').find('.blChangeGoods select[name="listPack"]').prop( "disabled", false ).show();
            $(this).closest('.row').find('.blChangeGoods select[name="listGoods"]').prop( "disabled", true ).hide();
        } else{
            $(this).closest('.row').find('.blChangeGoods select[name="listPack"]').prop( "disabled", true ).hide();
            $(this).closest('.row').find('.blChangeGoods select[name="listGoods"]').prop( "disabled", false ).show();
        }
    });

    $('.exportExcel').on('click',function (e) {
        e.preventDefault();

        formFilter = $('form[name="selectFilters"]');
        formFilter.attr('action','<?=\yii\helpers\Url::to(['sale-report/info-sale-for-country-warehouse-excel'])?>').submit();
        setTimeout(function() { formFilter.attr('action','<?=\yii\helpers\Url::to(['sale-report/info-sale-for-country-warehouse'])?>') }, 5000);
    })


</script>
