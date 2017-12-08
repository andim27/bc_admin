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
$listGoodsWithKey = Products::getListGoodsWithKey();

$total=[
    'count_order'   =>  0,
    'count_issue'   =>  0,
    'count_stock'   =>  0,
    'count_send'    =>  0,
    'count_repair'  =>  0
];
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('report_sale_for_country') ?></h3>
</div>
<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/sale-report/info-sale-for-country',
        'options' => ['name' => 'selectGoods',],
    ]); ?>

    <div class="col-md-1">
        <label class="control-label switch-center"></label>
        <label class="switch">
            <input value="1" class="btnflGoods" type="checkbox" name="flGoods" <?= ((!empty($request['flGoods']) && $request['flGoods']==1) ? 'checked="checked"' : '')?>/>
            <span></span>
        </label>
    </div>

    <div class="col-md-2 m-b blChangeGoods">
        <?=Html::dropDownList('listPack',(!empty($request['listPack']) ? $request['listPack'] : 'all'),
            ArrayHelper::merge(['all' => THelper::t('all_pack')],$listPack),[
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
        <?=Html::label(THelper::t('number_send') . THelper::t('from_kharkov'),'send_kh')?>
    </div>
    <div class="col-md-1 m-b">
        <?=Html::checkbox('send_kh',($request['send_kh']==0 ? false : true),['id'=>'send_kh'])?>
    </div>

    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
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
                            <?php foreach($infoSale as $k=>$itemGoods) { ?>
                                <?php foreach($itemGoods as $kGoods=>$item) { ?>
                                    <?php
                                    $total['count_order'] += $item['all'];
                                    $total['count_issue'] += $item['issued'];
                                    if(!empty($request['listGoods'])) {
                                        $total['count_stock'] += $item['in_stock'];
                                        $total['count_send'] += $item['send'];
                                    }
                                    $total['count_repair'] += $item['issued'];
                                    ?>
                                    <tr>
                                        <td><?=(!empty($listCountry[$k]) ? $listCountry[$k] : 'none') ?></td>
                                        <td><?=$kGoods?></td>
                                        <td><?=$item['all']?></td>
                                        <td><?=$item['issued']?></td>
                                        <?php if(!empty($request['listGoods'])) { ?>
                                            <td><?=$item['in_stock']?></td>
                                            <td><?=$item['send']?></td>
                                        <?php } ?>
                                        <td>
                                            <?php
                                                $margin = $item['issued'] + $item['send'] + $item['in_stock'] - $item['all'];
                                            ?>
                                            <span class="<?=($margin>=0 ? 'text-success' : 'text-danger')?>"><?=($margin)?></span>
                                        </td>
                                        <td><?=$item['repair']?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </tbody>

                    <tfooter>
                        <tr>
                            <th></th>
                            <th></th>
                            <th><?=$total['count_order']?></th>
                            <th><?=$total['count_issue']?></th>
                            <?php if(!empty($request['listGoods'])) { ?>
                            <th><?=$total['count_stock']?></th>
                            <th><?=$total['count_send']?></th>
                            <?php } ?>
                            <th></th>
                            <th><?=$total['count_repair']?></th>
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
    })
</script>
