<?php
use yii\bootstrap\Html;
use app\components\THelper;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use app\models\Products;
use app\models\Warehouse;

$listPack = Products::getListPack();
$listWarehouse = Warehouse::getArrayWarehouse();

?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_offsets_with_warehouses') ?></h3>
</div>


<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/offsets-with-warehouses/offsets-with-warehouses',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-3">
        <div class="input-group">
            <?= Html::input('text','from',$request['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd'])?>
            <span class="input-group-addon"> - </span>
            <?= Html::input('text','to',$request['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd'])?>
        </div>
    </div>


    <div class="col-md-1">
        <label class="control-label switch-center"></label>
        <label class="switch">
            <input value="1" class="btnflWarehouse" type="checkbox" name="flWarehouse" <?= ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? 'checked="checked"' : '')?>/>
            <span></span>
        </label>
    </div>

    <div class="col-md-2 m-b blChangeWarehouse">
        <?=Html::dropDownList('listWarehouse',(!empty($request['listWarehouse']) ? $request['listWarehouse'] : 'all'),
            ArrayHelper::merge([''=>THelper::t('all_warehouse')],$listWarehouse),
            [
                'class'=>'form-control listWarehouse',
                'id'=>'listWarehouse',
                'disabled' => ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? false : true),
                'style' =>  ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? '' : 'display:none'),
                'options' => []
            ])?>

        <?=Html::dropDownList('listCountry',(!empty($request['listCountry']) ? $request['listCountry'] : 'all'),
            ArrayHelper::merge([''=>THelper::t('all_country')],$listCountry),
            [
                'class'=>'form-control listCountry',
                'id'=>'listCountry',
                'disabled' => ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? true : false),
                'style' =>  ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? 'display:none' : ''),
                'options' => []
            ])?>
    </div>

    <div class="col-md-2 m-b blChangeGoods">
        <?=Html::dropDownList('listPack',(!empty($request['listPack']) ? $request['listPack'] : 'all'),
            ArrayHelper::merge(['all' => 'Все паки'],$listPack),[
                'class'=>'form-control listPack',
                'id'=>'listPack',
                'options' => []
            ])?>
    </div>

    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right"></div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th><?=THelper::t('country')?></th>
                        <th><?=THelper::t('warehouse')?></th>
                        <th><?=THelper::t('product')?></th>
                        <th><?=THelper::t('number_buy_prepayment')?></th>
                        <th><?=THelper::t('number_buy_cash')?></th>
                        <th><?=THelper::t('amount_for_the_device')?></th>
                        <th><?=THelper::t('amount_repayment_for_company')?></th>
                        <th><?=THelper::t('amount_repayment_for_warehouse')?></th>
                        <th><?=THelper::t('difference')?></th>
                        <th><?=THelper::t('repaid')?></th>
                        <th><?=THelper::t('look_repaid')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($info)) { ?>
                        <?php foreach($info as $kCountry=>$itemCountry) { ?>
                            <?php foreach($itemCountry as $kWarehouse=>$itemWarehouse) { ?>
                                <?php foreach($itemWarehouse as $kSet=>$itemSet) { ?>
                                    <tr>
                                        <td><?=$listCountry[$kCountry]?></td>
                                        <td><?=$listWarehouse[$kWarehouse]?></td>
                                        <td><?=$listPack[$kSet]?></td>
                                        <td><?=$itemSet['number_buy_prepayment']?></td>
                                        <td><?=$itemSet['number_buy_cash']?></td>
                                        <td><?=$itemSet['amount_for_the_device']?></td>
                                        <td><?=$itemSet['amount_repayment_for_company']?></td>
                                        <td><?=$itemSet['amount_repayment_for_warehouse']?></td>
                                        <td>
                                            <span class="<?=($itemSet['amount_repayment_for_company']>$itemSet['amount_repayment_for_warehouse'] ? 'text-danger' : 'text-success')?>">
                                                <?=abs($itemSet['amount_repayment_for_company']-$itemSet['amount_repayment_for_warehouse'])?>
                                            </span>
                                        </td>
                                        <td>???</td>
                                        <td>
                                            <?=  Html::a('<i class="fa fa-eye text-info"></i>', ['/business/offsets-with-warehouses/repayment','id'=>$kWarehouse], ['class'=>'btn btn-default']); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
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