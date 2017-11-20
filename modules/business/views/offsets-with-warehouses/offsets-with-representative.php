<?php
use yii\bootstrap\Html;
use app\components\THelper;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use app\models\Products;
use app\models\Warehouse;

$listRepresentative = Warehouse::getListHeadAdmin();

$listPack = Products::getListPack();
$myWarehouseId = Warehouse::getIdMyWarehouse();
if($myWarehouseId != '5a056671dca7873e022be781'){
    $listWarehouse = Warehouse::getMyWarehouse();
} else{
    $listWarehouse = Warehouse::getArrayWarehouse();
}
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_offsets_with_representative') ?></h3>
</div>


<div class="row blQuery">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/offsets-with-warehouses/offsets-with-representative',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-3">
        <div class="input-group">
            <?= Html::input('text','from',$request['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd', 'data-date-weekStart'=>1])?>
            <span class="input-group-addon"> - </span>
            <?= Html::input('text','to',$request['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd', 'data-date-weekStart'=>1])?>
        </div>
    </div>

    <?php if($hideFilter != 1){ ?>
    <div class="col-md-2 m-b blChangeWarehouse">
        <?= Select2::widget([
            'name' => 'listRepresentative',
            'value' => $request['listRepresentative'],
            'data' => $listRepresentative,
            'options' => [
                'placeholder'   => 'Выберите представителя',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ]
        ]);
        ?>
    </div>

    <?php } ?>

    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right">
        <?=Html::a(THelper::t('sidebar_offsets_with_warehouses'),'offsets-with-warehouses')?>
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
                        <th><?=THelper::t('representative')?></th>
                        <th><?=THelper::t('number_buy_prepayment')?></th>
                        <th><?=THelper::t('number_buy_cash')?></th>
                        <th><?=THelper::t('amount_for_the_device')?></th>
                        <th><?=THelper::t('amount_repayment_for_company')?></th>
                        <th><?=THelper::t('amount_repayment_for_representative')?></th>
                        <th><?=THelper::t('repayment_company')?></th>
                        <th><?=THelper::t('repayment_representative')?></th>
<!--                        <th>--><?php //=THelper::t('difference'); ?><!--</th>-->
<!--                        <th>--><?//=THelper::t('repaid')?><!--</th>-->
                        <th><?=THelper::t('look_repaid')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($info)) { ?>
                        <?php foreach($info as $kRepresentative=>$itemRepresentative) { ?>
                            <tr>
                                <td>
                                    <?=  Html::a('<i class="fa fa-bars text-info"></i>', 'javascript:void(0);', ['class'=>'btn btn-default decompositionByProducts', 'data-representative'=>$kRepresentative]); ?>
                                </td>
                                </td>
                                <td><?=$listRepresentative[$kRepresentative]?></td>
                                <td><?=$itemRepresentative['number_buy_prepayment']?></td>
                                <td><?=$itemRepresentative['number_buy_cash']?></td>
                                <td><?=$itemRepresentative['amount_for_the_device']?></td>
                                <td><?=$itemRepresentative['amount_repayment_for_company']?></td>
                                <td><?=$itemRepresentative['amount_repayment_for_warehouse']?></td>
                                <td><?=$itemRepresentative['repayment_company']?></td>
                                <td><?=$itemRepresentative['repayment_warehouse']?></td>
<!--                                <td>-->
<!--                                    --><?php
//                                        //$difference = $itemRepresentative['amount_repayment_for_company']-$itemRepresentative['amount_repayment_for_warehouse'];
//                                    ?>
<!--                                    <span class="--><?php //=($difference>0 ? 'text-danger' : 'text-success'); ?><!--">-->
<!--                                        --><?php //=abs($difference); ?>
<!--                                    </span>-->
<!--                                </td>-->
<!--                                <td>-->
<!--                                    <span>-->
<!--                                        --><?php //=$itemRepresentative['repayment'];?>
<!--                                    </span>-->
<!--                                </td>-->
                                <td>
                                    <?=  Html::a('<i class="fa fa-eye text-info"></i>', ['/business/offsets-with-warehouses/repayment','object'=>'representative','id'=>$kRepresentative], ['class'=>'btn btn-default']); ?>
                                    <?=  Html::a('<i class="fa fa-building-o text-info"></i>', ['/business/offsets-with-warehouses/offsets-with-warehouses','representativeId'=>$kRepresentative], ['class'=>'btn btn-default']); ?>
                                </td>
                            </tr>
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


    $('.decompositionByProducts').on('click',function () {
        representativeId = $(this).data('representative');

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['offsets-with-warehouses/offsets-with-goods'])?>',
            type: 'POST',
            data: {
                id        : representativeId,
                object    : 'representative',
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
