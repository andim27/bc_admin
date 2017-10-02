<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\CurrencyRate;

$this->registerJsFile('js/jQuery.print.js', ['depends'=>['app\assets\AppAsset']]);

$partAccessoriesInWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();
$partAccessoriesAll = PartsAccessories::getListPartsAccessories();
$partAccessoriesPricePurchase = PartsAccessories::getPricePurchase();

$list = PartsAccessories::getListPartsAccessoriesWithComposite();

$actualCurrency = CurrencyRate::getActualCurrency();

$amount = 0;
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('planning_purchasing') ?></h3>
</div>


<?php $formStatus = ActiveForm::begin([
    'action' => '/' . $language . '/business/planning-purchasing/multiplier-planning-purchasing'
]); ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-7 m-b">
                <?=Html::dropDownList('','',$list,[
                    'class'=>'form-control idGoods',
                    'options' => [],
                    'prompt'=>'Выберите товар'
                ])?>
            </div>
            <div class="col-md-3 m-b">
                <?= Html::input('number','',0,[
                    'class' => 'form-control countGoods',
                    'min'=>'0',
                    'step'=>'0.01'
                ])?>
            </div>
            <div class="col-md-2 m-b">
                <?= Html::button('<i class="fa fa-plus"></i>', ['class' => 'btn btn-success btn-block addGoods']) ?>
            </div>
        </div>
    </div>

    <div class="panel-body wantMake">
        <?php if(!empty($request)){?>
            <?php foreach ($request['wantMake']['id'] as $k=>$item){?>
                <div class="form-group row">
                    <div class="col-md-7">
                        <input type="text" class="form-control wantMakeTitle" value="<?=$partAccessoriesAll[$item]?>" disabled="disabled" >
                        <input type="hidden" name="wantMake[id][]" value="<?=$item?>">
                        </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control wantMakeCount" value="<?=$request['wantMake']['count'][$k]?>" disabled="disabled">
                        <input type="hidden" name="wantMake[count][]" value="<?=$request['wantMake']['count'][$k]?>">
                        </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-default btn-block removeGoods"><i class="fa fa-trash-o"></i></button>
                        </div>
                    </div>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="panel-footer">
        <div class="row">
            <div class="col-md-2 m-b col-md-offset-10">
                <?= Html::submitButton('Просчитать', ['class' => 'btn btn-success btn-block']) ?>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


<?php if(!empty($info)) { ?>
    <div class="row">
        <div class="col-md-1 m-b col-md-offset-11">
            <?= Html::button('<i class="fa fa-print"></i>', ['class' => 'btn btn-success btn-block btnPrint']) ?>
        </div>
    </div>

    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm infoNeed">
                <thead>
                <tr>
                    <th>Товар</th>
                    <th>Необходимо</th>
                    <th>На складе</th>
                    <th>Нужно заказать</th>
                    <th>Цена за шт</th>
                    <th>Сумма</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($info as $k=>$item) { ?>
                        <?php
                            $inWarehouse = (!empty($partAccessoriesInWarehouse[$k]) ? $partAccessoriesInWarehouse[$k] : '0');
                            $needCount = $item-$inWarehouse;
                            $needCount = ($needCount > 0 ? $needCount : '0');
                            $priceAmount = $partAccessoriesPricePurchase[$k]*$needCount;
                            $amount += $priceAmount;
                        ?>
                        <tr>
                            <td class="accessoriesTitle"><?=$partAccessoriesAll[$k];?></td>
                            <td class="accessoriesNeed"><?=$item?></td>
                            <td class="accessoriesInWarehouse"><?=$inWarehouse?></td>
                            <td class="accessoriesNeedBuy"><?= $needCount; ?></td>
                            <td class="priceOne"><?=$partAccessoriesPricePurchase[$k];?></td>
                            <td class="priceAmount"><?=$priceAmount?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td rowspan="4" colspan="5"><b>Итого</b></td>
                        <td class="amount_eur"><?=$amount?> euro</td>
                    </tr>
                    <tr>
                        <td class="amount_usd"><?=($amount*$actualCurrency['usd'])?> usd</td>
                    </tr>
                    <tr>
                        <td class="amount_uah"><?=($amount*$actualCurrency['uah'])?> uah</td>
                    </tr>
                    <tr>
                        <td class="amount_rub"><?=($amount*$actualCurrency['rub'])?> rub</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <div class="row">
        <div class="col-md-1 m-b col-md-offset-11">
            <?= Html::button('<i class="fa fa-print"></i>', ['class' => 'btn btn-success btn-block btnPrint']) ?>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">

        $('.addGoods').on('click',function () {
            var flAddNow = 1;

            goodsID = $('.idGoods :selected').val();
            goodsName = $('.idGoods :selected').text();
            goodsCount = parseInt($('.countGoods').val());

            if(goodsID==''){
                alert('Выберите товар!');
                flAddNow = 0;
            }

            if(goodsCount<=0){
                alert('Заданyое количество должно быть больше нуля!');
                flAddNow = 0;
            }

            $(".wantMake").find(".row").each(function () {
                if($(this).find('input[name="wantMake[id][]"]').val() == goodsID) {
                    alert('Уже добавлен товар в посылку!');
                    flAddNow = 0;
                }
            });

            if(flAddNow != 1){
                return;
            }

            $(".wantMake").append(
                '<div class="form-group row">'+
                    '<div class="col-md-7">'+
                        '<input type="text" class="form-control" value="'+goodsName+'" disabled="disabled" >'+
                        '<input type="hidden" name="wantMake[id][]" value="'+goodsID+'">'+
                    '</div>'+
                    '<div class="col-md-3">'+
                        '<input type="text" class="form-control" value="'+goodsCount+'" disabled="disabled">'+
                        '<input type="hidden" name="wantMake[count][]" value="'+goodsCount+'">'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<button type="button" class="btn btn-default btn-block removeGoods"><i class="fa fa-trash-o"></i></button>'+
                    '</div>'+
                '</div>'
            );
        });

    $('.wantMake').on('click','.removeGoods',function () {
        $(this).closest(".row").remove();
    });

        $(document).on('click','.btnPrint', function() {

            tempBlAccessories = '';
            $(".infoNeed tbody").find('tr').each(function () {
                tempBlAccessories +=
                        '<tr>' +
                            '<td>'+ $(this).find('.accessoriesTitle').text() +
                            '<td>'+ $(this).find('.accessoriesNeed').text() +
                            '<td>'+ $(this).find('.accessoriesInWarehouse').text() +
                            '<td>'+ $(this).find('.accessoriesNeedBuy').text()+
                            '<td>'+ $(this).find('.priceOne').text()+
                            '<td>'+ $(this).find('.priceAmount').text();
            });

            tempBlGoods = '';
            $(".wantMake").find('row').each(function () {
                tempBlGoods +=
                    '<tr>' +
                        '<td colspan="3">'+ $(this).find('.wantMakeTitle').text() +
                        '<td colspan="3">'+ $(this).find('.wantMakeCount').text();
            });


            printFile =
                '<table>' +
                    '<tr>' +
                        '<th colspan="6">Планирование' +
                    '<tr>' +
                        '<td colspan="3"><b>Собираем<b>'+
                        '<td colspan="3"><b>Количество<b>'+

                    tempBlGoods+

                    '<tr>' +
                        '<th colspan="6">Необходимо:' +
                    '<tr>' +
                        '<td> Товар' +
                        '<td> Необходимо' +
                        '<td> На складе' +
                        '<td> Нужно заказать'+
                        '<td> Цена за шт'+
                        '<td> Сумма'+

                    tempBlAccessories +

                    '<tr>' +
                        '<td colspan="2"> Итого:' +
                        '<td> ' + $('.amount_eur').text() +
                        '<td> ' + $('.amount_usd').text() +
                        '<td> ' + $('.amount_uah').text() +
                        '<td> ' + $('.amount_rub').text() +
                '</table>';

            $.print(printFile,{
                stylesheet : window.location.origin + '/css/print.css'
            });
        });

</script>