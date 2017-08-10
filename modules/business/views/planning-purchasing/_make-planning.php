<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\models\PartsAccessoriesInWarehouse;

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

$listSuppliersPerformers=SuppliersPerformers::getListSuppliersPerformers();
$listSuppliersPerformers = ArrayHelper::merge([''=>'Выберите поставщика-испонителя'],$listSuppliersPerformers);

$canMake = 0;
if(!empty($model) && !empty($model->list_component)){
    $listComponents = [];
    foreach($model->list_component as $item){
        $listComponents[] = (string)$item['parts_accessories_id'];
    }
    $canMake = PartsAccessoriesInWarehouse::getHowMuchCanCollect((string)$model->parts_accessories_id,$listComponents);
}

$want_number = 0;
if(!empty($model)){
    $want_number = $model->number;
}

?>

<div class="modal-dialog modal-more-lg popupPlanning">
    <div class="modal-content ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Планирование</h4>
        </div>

        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/planning-purchasing/save-planning',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(!empty($model) ? (string)$model->_id : ''));?>

                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-6"></div>
                    <div class="col-md-4 text-right">Можно собрать:</div>
                    <div class="col-md-2 canCollect">0 шт.</div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <?=Html::dropDownList('parts_accessories_id',
                            (!empty($model) ?  $model->parts_accessories_id : ''),
                            ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessoriesWithComposite()),[
                                'class'=>'form-control',
                                'id'=>'selectGoods',
                                'required'=>'required',
                                'options' => [
                                    '' => ['disabled' => true]
                                ],
                                'disabled' => (!empty($model) ?  true : false)
                            ])?>

                        <?=(!empty($model) ?  Html::hiddenInput('parts_accessories_id',(string)$model->parts_accessories_id) : '')?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::input('number','need','1',[
                            'class'=>'form-control needPlaning',
                            'placeholder'=>'Необходимое количество',
                            'pattern'=>'\d*',
                            'min'=>'1',
                            'step'=>'1',
                        ])?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::a('Печать','javascript:void(0);',['class'=>'btn btn-default btn-block btnPrint']); ?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::a('Очистить','javascript:void(0);',['class'=>'btn btn-default btn-block btnClear'])?>
                    </div>
                </div>

                <div class="form-group blPartsAccessories row">
                    <?php if(!empty($model)){ ?>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">На одну шт.</div>
                                        <div class="col-md-3">Надо отправить</div>
                                        <div class="col-md-3">С запасом</div>
                                    </div>
                                    <?php if(!empty($model->list_component)){ ?>
                                        <?php foreach($model->list_component as $item){ ?>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['parts_accessories_id']))) { ?>
                                                        <?=Html::dropDownList('complect[]','',
                                                            PartsAccessories::getInterchangeableList((string)$item['parts_accessories_id']),[
                                                                'class'=>'form-control',
                                                                'required'=>'required',
                                                                'options' => [
                                                                ]
                                                            ])?>

                                                    <?php } else {?>
                                                        <?=Html::hiddenInput('complect[]',(string)$item['parts_accessories_id'],[]);?>
                                                        <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],['class'=>'form-control','disabled'=>'disabled']);?>

                                                    <?php } ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                                                    <?=Html::input('text','',$item['number'],['class'=>'form-control','disabled'=>'disabled']);?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? ($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']] + ($item['number']*$want_number)) : 0 ),['class'=>'numberWarehouse']);?>
                                                    <?=Html::input('text','',($item['number']*$want_number),['class'=>'form-control needSend','disabled'=>'disabled']);?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?=Html::input('number','reserve[]',(!empty($item['reserve']) ? $item['reserve'] : 0),[
                                                        'class'=>'form-control',
                                                        'pattern'=>'\d*',
                                                        'min' => '0',
                                                        'step'=>'1',
                                                    ]);?>
                                                </div>

                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>


                <div class="row fullSumma form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">Итого:</div>
                    <div class="col-md-2">
                        <span class="eur"></span> eur /
                    </div>
                    <div class="col-md-2">
                        <span class="usd"></span> usd /
                    </div>
                    <div class="col-md-2">
                        <span class="uah"></span> uah /
                    </div>
                    <div class="col-md-2">
                        <span class="rub"></span> rub
                    </div>
                </div>


                <div class="row form-group">
                    <div class="col-md-6 text-left">
                        <?= Html::a('Удалить заказ с таблицы','',['class' => 'btn btn-success']) ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success assemblyBtn']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>



<script>


    $(document).on('change','#selectGoods',function () {
        $.ajax({
            url: '<?=\yii\helpers\Url::to(['planning-purchasing/all-components'])?>',
            type: 'POST',
            data: {
                PartsAccessoriesId : $(this).val(),
            },
            success: function (data) {
                $('.blPartsAccessories').html(data);
            }
        });
    });

    $('.needPlaning').on('change',function () {

        fullSumma = {
            'eur' : 0,
            'usd' : 0,
            'uah' : 0,
            'rub' : 0,
        };

        needGoods = $(this).val();

        $('.blPartsAccessories .warehouseCount').each(function(indx){
            blItem = $(this).closest('.row');

            warehouseCount = parseInt($(this).text());

            needCountForOne = parseInt(blItem.find('.needCountForOne').text());

            needOrdering = (needCountForOne * needGoods) - warehouseCount;

            if(needOrdering <= 0){
                needOrdering = 0;
            }

            blItem.find('.needOrdering').text(needOrdering);
            blItem.find('.needBuy').val(needOrdering);


            blItem.find('.onceSumma div span').each(function(indx){

                blItem = $(this).closest('.row');

                price = parseFloat($(this).text());
                currency = $(this).attr('class');

                allPrice = parseFloat((price*needOrdering).toFixed(2));

                fullSumma[currency] = fullSumma[currency] + allPrice;

                blItem.find('.allSumma div .'+currency).text(allPrice);

            })

        });

        for (key in fullSumma) {
            $('.fullSumma .'+key).text(fullSumma[key].toFixed(3));
        }


    });

    $('.btnClear').on('click',function () {

        fullSumma = {
            'eur' : 0,
            'usd' : 0,
            'uah' : 0,
            'rub' : 0,
        };

        $('.needPlaning').val(0);

        $('.blPartsAccessories .warehouseCount').each(function(indx){
            blItem = $(this).closest('.row');

            blItem.find('.needBuy').val('0');
            blItem.find('.needOrdering').text('0');
        });

        for (key in fullSumma) {
            $('.fullSumma .'+key).text(fullSumma[key].toFixed(3));
        }
    });

    $(".btnPrint").on('click', function() {

        tempBl = '';
        $(".popupPlanning .blPartsAccessories").find('.form-group.row').each(function () {
            title = $(this).find('.partTitle :selected').text();
            if(title == ''){
                title = $(this).find('.partTitle').val();
            }

            if(title){
                tempBl +=
                    '<tr>' +
                    '<td>'+  title +
                    '<td>'+ $(this).find('.needCountForOne').text() +
                    '<td>'+ $(this).find('.warehouseCount').text() +
                    '<td>'+ $(this).find('.needOrdering').text() +
                    '<td>'+ $(this).find('.onceSumma').html() +
                    '<td>'+ $(this).find('.needBuy').val() +
                    '<td>'+ $(this).find('.allSumma').html();
            }

        });

        printFile =
            '<table>' +
            '<tr>' +
                '<th colspan="7">Планирование' +
            '<tr>' +
                '<td><b>Собираем<b>'+
                '<td colspan="6">' + $(".popupPlanning select[name='parts_accessories_id'] :selected").text() +
            '<tr>' +
                '<td><b>Количество<b>'+
                '<td colspan="6">' + $(".popupPlanning input[name='need']").val() + ' шт.' +
            '<tr>' +
                '<th colspan="7">Необходимо:' +
            '<tr>' +
                '<td> Коплектующая' +
                '<td> На одну шт.' +
                '<td> В наличие' +
                '<td> Надо заказть' +
                '<td> Цена за шт.' +
                '<td> Сколько брем' +
                '<td> Стоимость' +

            tempBl +

            '<tr>' +
                '<td colspan="3"> Итого:' +
                '<td> '+$(".popupPlanning .fullSumma .eur").text()+' eur ' +
                '<td> '+$(".popupPlanning .fullSumma .usd").text()+' usd ' +
                '<td> '+$(".popupPlanning .fullSumma .uah").text()+' uah ' +
                '<td> '+$(".popupPlanning .fullSumma .rub").text()+' rub ' +


            '</table>';

        $.print(printFile,{
            stylesheet : window.location.origin + '/css/print.css'
        });
    });

//    $(".WantCollect").on('change',function(){
//        wantC = parseInt($(this).val());
//        canC = parseInt($('.CanCollect').val());
//
//        $('.blPartsAccessories .row').each(function () {
//            needNumber = $(this).find('input[name="number[]"]').val();
//            $(this).find('.needSend').val(needNumber*wantC);
//        });
//
//        if(wantC>canC){
//            $('.assemblyBtn').hide();
//        } else {
//            $('.assemblyBtn').show();
//        }
//    });
//
//    $('.blPartsAccessories').on('change','input[name="reserve[]"]',function(){
//        bl = $(this).closest('.row');
//
//        inWarehouse = parseInt(bl.find('.numberWarehouse').val());
//        need = parseInt(bl.find('.needSend').val());
//        wantReserve = parseInt($(this).val());
//
//        countInfo = inWarehouse - need - wantReserve;
//        if(countInfo >= 0){
//            $('.assemblyBtn').show();
//        } else {
//            $(".infoDanger").html(
//                '<div class="alert alert-danger fade in">' +
//                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
//                'Такого количества нет на складе. Доступно ' + inWarehouse + 'шт.' +
//                '</div>'
//            );
//
//            $('.assemblyBtn').hide();
//        }
//    })


</script>