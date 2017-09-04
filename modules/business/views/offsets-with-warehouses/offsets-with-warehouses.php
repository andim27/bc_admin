<?php
use yii\bootstrap\Html;
use app\components\THelper;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use app\models\Products;
use app\models\Warehouse;

$listPack = Products::getListPack();
$myWarehouseId = Warehouse::getIdMyWarehouse();

$listWarehouse = Warehouse::getArrayWarehouse();

?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_offsets_with_warehouses') ?></h3>
</div>


<div class="row blQuery">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/offsets-with-warehouses/offsets-with-warehouses',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-3">
        <div class="input-group">
            <?= Html::input('text','from',$request['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd', 'data-date-weekStart'=>1])?>
            <span class="input-group-addon"> - </span>
            <?= Html::input('text','to',$request['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd', 'data-date-weekStart'=>1])?>
        </div>
    </div>


    <?php if(empty($representativeId)){ ?>
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
    <?php } ?>

    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right">
        <?=Html::a(THelper::t('sidebar_offsets_with_representative'),'offsets-with-representative')?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?=THelper::t('country')?></th>
                        <th><?=THelper::t('warehouse')?></th>
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
                                <tr>
                                    <td>
                                        <?=  Html::a('<i class="fa fa-bars text-info"></i>', 'javascript:void(0);', ['class'=>'btn btn-default decompositionByProducts', 'data-warehouse'=>$kWarehouse]); ?>
                                    </td>
                                    </td>
                                    <td><?=$listCountry[$kCountry]?></td>
                                    <td><?=$listWarehouse[$kWarehouse]?></td>
                                    <td><?=$itemWarehouse['number_buy_prepayment']?></td>
                                    <td><?=$itemWarehouse['number_buy_cash']?></td>
                                    <td><?=$itemWarehouse['amount_for_the_device']?></td>
                                    <td><?=$itemWarehouse['amount_repayment_for_company']?></td>
                                    <td><?=$itemWarehouse['amount_repayment_for_warehouse']?></td>
                                    <td>
                                        <?php
                                            $difference = $itemWarehouse['amount_repayment_for_company']-$itemWarehouse['amount_repayment_for_warehouse'];
                                        ?>
                                        <span class="<?=($difference>0 ? 'text-danger' : 'text-success')?>">
                                            <?=abs($difference)?>
                                        </span>
                                    </td>
                                    <td>
                                        <span>
                                            <?=$itemWarehouse['repayment']?>
                                        </span>
                                    </td>
                                    <td>
                                        <?=  Html::a('<i class="fa fa-eye text-info"></i>', ['/business/offsets-with-warehouses/repayment','object'=>'warehouse','id'=>$kWarehouse], ['class'=>'btn btn-default']); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </section>
    </div>
</div>



<div class="modal fade" id="decompositionPopup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=THelper::t('decomposition_for_goods')?></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 1, "desc" ]]
    });

    $('.btnflWarehouse').on('change',function () {
        if($(this).is(':checked')) {
            $(this).closest('.row').find('.blChangeWarehouse select[name="listWarehouse"]').prop( "disabled", false ).show();
            $(this).closest('.row').find('.blChangeWarehouse select[name="listCountry"]').prop( "disabled", true ).hide();
        } else{
            $(this).closest('.row').find('.blChangeWarehouse select[name="listWarehouse"]').prop( "disabled", true ).hide();
            $(this).closest('.row').find('.blChangeWarehouse select[name="listCountry"]').prop( "disabled", false ).show();
        }
    });

    $('.decompositionByProducts').on('click',function () {
        warehouseId = $(this).data('warehouse');

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['offsets-with-warehouses/offsets-with-goods'])?>',
            type: 'POST',
            data: {
                id        : warehouseId,
                object    : 'warehouse',
                from      : $('.blQuery .dateFrom').val(),
                to        : $('.blQuery .dateTo').val()
            },
            success: function (data) {
                $('#decompositionPopup').modal().find('.modal-body').html(data);
            }
        });

    });

    $('#decompositionPopup').on('click','.decompositionItem',function(){
        warehouseId = $(this).data('id');
        if ($(this).find('.fa').hasClass('fa-toggle-down') ) {
            $(this).find('i').removeClass('fa-toggle-down').addClass('fa-toggle-right');
            $('#decompositionPopup .table tr[data-warehouse="'+warehouseId+'"]').each(function(indx){
                $(this).hide();
            });
        } else {
            $(this).find('i').removeClass('fa-toggle-right').addClass('fa-toggle-down');
            $('#decompositionPopup .table tr[data-warehouse="'+warehouseId+'"]').each(function(indx){
                $(this).show();
            });
        }
    });

</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>
