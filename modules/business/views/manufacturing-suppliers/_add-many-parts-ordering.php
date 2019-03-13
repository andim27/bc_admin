<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\models\CurrencyRate;
use kartik\widgets\Select2;

$listGoods = PartsAccessories::getListPartsAccessories();

$listSuppliersPerformers=SuppliersPerformers::getListSuppliersPerformers();
$listSuppliersPerformers = ArrayHelper::merge([''=>'Выберите поставщика-испонителя'],$listSuppliersPerformers);

$listGoodsWithComposite = PartsAccessories::getListPartsAccessoriesWithComposite();
?>
<style>
    .popupPartsOrdering {
        width: 60%;
    }
    .m-top-20 {
        margin-top: 20px
    }
    .m-top-25 {
        margin-top: 25px
    }
    .m-left-10 {
        margin-left:10px
    }
    .b-bottom-dotted {
        border-bottom: #1e7e34 dotted;
    }
    .m-bottom-25 {
        margin-bottom: 25px;
    }
</style>
<div class="modal-dialog popupPartsOrdering">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_parts_ordering') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-many-parts-ordering',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <?=(!empty($model->_id) ? Html::hiddenInput('id', $model->_id,['class'=>'form-control']) : '')?>

            <div class="blError"></div>

            <div class="row">
                <div class="col-md-12">
                    <?=Html::label(THelper::t('sidebar_suppliers_performers'))?>

                    <?=Html::dropDownList('suppliers_performers_id',
                        (!empty((string)$model->suppliers_performers_id) ? (string)$model->suppliers_performers_id: ''),
                        $listSuppliersPerformers,[
                            'class'=>'form-control',
                            'id'=>'selectChangeStatus',
                            'required'=>'required',
                            'onchange' =>'clearError();',
                            'options' => [
                                ''         => ['disabled' => true],

                            ]
                        ])?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <?=Html::label(THelper::t('goods'))?>

                        <?= Select2::widget([
                            'name' => 'parts_accessories_id',
                            'data' => $listGoods,
                            'options' => [
                                    'id'=>'cur_good',
                                'placeholder' => 'Выберите товар',
                                'onchange'=>'clearError();'

                            ]
                        ]);
                        ?>

                </div>
                <div class="col-md-4">
                    <button id="add-part-btn"  type="button" class="btn btn-default m-top-25"  onclick="addPart()" title="Добавить товар">+</button>
                </div>
            </div>


            <div class="row b-bottom-dotted" id="part-items" style="display:none;padding-right: 15px">


                <div class="row m-bottom-25" id="part-item-0" style="display:none">
                    <div class="col-md-5">
                        <h4 class="text-primary text-right  m-left-10">Название товара</h4>
                    </div>
                    <div class="col-md-2">
                        Количество
                        <?=Html::input('number','number', (!empty($model->number) ? $model->number: '1'),[
                            'class'=>'form-control',
                            'min'=>'1',
                            'step'=>'1',
                            'onchange'=>'calcNumberPrice();'
                        ])?>
                    </div>
                    <div class="col-md-2">
                        Валюта
                        <?=Html::dropDownList('currency',
                            (!empty((string)$model->currency) ? (string)$model->currency: ''),
                            CurrencyRate::getListCurrency(),[
                                'class'=>'form-control',
                                'id'=>'selectChangeStatus',
                                'required'=>'required',
                                'options' => [
                                    '' => ['disabled' => true]
                                ]
                            ])?>
                    </div>

                    <div class="col-md-2">
                       Стоимость
                        <?=Html::input('number','price', (!empty($model->price) ? $model->price: '0'),[
                            'class'=>'form-control',
                            'min'=>'0.01',
                            'step'=>'0.01',
                            'onblur'  =>'clearError();',
                            'onchange'=>'calcNumberPrice();'
                        ])?>
                    </div>
                     <div class="col-md-1">
                         <button  id="del-part-btn"  type="button" class="btn btn-default m-top-20" data-pos=1 onclick="delPart(this)"><span class="glyphicon glyphicon-minus-sign"></span> </button>
                     </div>

                </div>
            </div>
            <br>
            <div class="row b-bottom-dotted" id="part-totals" style="display: none">
                <div class="col-md-5">
                    <h4 class="text-primary text-right">Итого:</h4>
                </div>
                <div class="col-md-2 ">
                    <h4 id="number-total-sum" class="text-primary text-left">00</h4>
                </div>
                <div class="col-md-2 offset-right-1">
                    <h4 class="text-primary text-right">Стоимость:</h4>
                </div>
                <div class="col-md-2 ">
                    <h4 id="price-total-sum" class="m-bottom-20">0.00</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 offset-left-4">
                    Дата прибития
                    <?=Html::input('text','dateReceipt',
                        (!empty($model->dateReceipt) ? $model->dateReceipt->toDateTime()->format('Y-m-d') : date('Y-m-d')),
                        [
                            'class' => 'form-control datepicker-input',
                            'data-date-format'=>'yyyy-mm-dd'
                        ])?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-7 text-right">
                    <button type="button" class="btn btn-success" id="s_btn" onclick="saveParts();"><?= THelper::t('settings_translation_edit_save') ?></button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>

<script type="text/javascript">

    $(".datepicker-input").datepicker();

    arrayGoodsComposite = <?=json_encode($listGoodsWithComposite)?>;
    //$('#part-accessors').multiselect();
    $("#partsAccessoriesId").on("change",function () {

        clearError();

        goodsID = $(this).val();

        if(arrayGoodsComposite[goodsID]){
            alertError('Данный товар составной!');
        }
    });

    function alertError(error) {
        $(".popupPartsOrdering .blError").html(
            '<div class="alert alert-danger fade in">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
            '<strong>'+error+'</strong>' +
            '</div>')
    }
    function alertMessage(mes) {
        $(".popupPartsOrdering .blError").html(
            '<div class="alert alert-success fade in">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
            '<strong>'+mes+'</strong>' +
            '</div>')

    }
    function clearError() {
        $(".popupPartsOrdering .blError").html('');
    }
    //----------------------

    p_items_cnt =0;
    p_items_arr=[];
    function addPart() {
        if ( $('#cur_good').select2('data')[0].id ==''){
            alertError('Выберите товар');
            return;
        }
        $('#part-items').show();
        $('#part-totals').show();
        var cnt =  $('#part-items').children().length;
        p_items_cnt = cnt;
        if (cnt >= 1) {
            $('#part-item-0').clone().appendTo('#part-items');

            $('#part-items div:last #del-part-btn').attr('data-pos',cnt);
            $('#part-items .row').eq(cnt).show().attr('id','part-item-'+(cnt));
            var cur_goods_text  = $('#cur_good').select2('data')[0].text;
            var cur_goods_value = $('#cur_good').select2('data')[0].id;
            $('#part-item-'+cnt+' h4').html(cur_goods_text);
            $('#part-item-'+cnt).attr('data-part_id',cur_goods_value);
            p_items_cnt =p_items_cnt+1;
        }
        calcNumberPrice();
    }
    function delPart(el) {
        var cnt =  $('#part-items').children().length;
        if (cnt > 0) {
            $(el).parent().parent().remove();
        } else {
            $('#part-totals').hide();
        }
        calcNumberPrice();

    }
    function calcNumberPrice() {
        var price_all=0;
        var number_all=0;
        var p_items =$('#part-items');
        var cnt =  p_items.children().length;
        for (var i=1;i<cnt;i++) {
            var price_item    = parseFloat(p_items.children().eq(i).find('input[name="price"]').val());//parseFloat($('#part-item-'+i+' input[name="price"]').val());
            var number_item   = parseFloat(p_items.children().eq(i).find('input[name="number"]').val());
            price_all+=price_item;
            number_all+=number_item;
        }
        $('#number-total-sum').html(''+number_all.toFixed(2));
        $('#price-total-sum').html(''+price_all.toFixed(2));
    }
    function showError(name,i) {
        var name_rus='';
        if (name =='part_number') {
            name_rus='Колличество';
            $('#part-item-'+i+' input[name="number"]').focus();
        }
        if (name =='part_price') {
            name_rus='Стоимость';
            $('#part-item-'+i+' input[name="price"]').focus();
        }
        if (name =='suppliers') {
            name_rus = 'Поставщик';
            $('select[name="suppliers_performers_id"]').focus();
        }
        if (name =='parts_accessories') {
            name_rus = 'Товар';
            $('select[name="parts_accessories_id"]').focus();
        }
        name_rus = '<strong>'+name_rus+'</strong>';
        alertError('Некорректное значение поля '+name_rus);
    }
    function checkPartItems() {
        var res=true;
        if ($('select[name="suppliers_performers_id"]').val() == null) {
            showError('suppliers',0);
            return false;
        }
        if ($('select[name="parts_accessories_id"]').val() == '') {
            showError('parts_accessories',0);
            return false;
        }
        var cnt =  $('#part-items').children().length;
        for (var i=1;i<=cnt;i++) {
            part_number   = $('#part-item-'+i+' input[name="number"]').val();
            if (part_number <=0) {
                showError('part_number',i);
                return false;
            }
            part_price    = $('#part-item-'+i+' input[name="price"]').val();
            if (part_price <=0) {
                showError('part_price',i);
                return false;
            }
        }
        return res;
    }
    function pickItems() {
        var part_items=[
            //{'part_id':123,'part_number':1,'part_currency':'eur','part_price':100},
            //{'part_id':456,'part_number':2,'part_currency':'eur','part_price':200},
        ];
        var cnt =  $('#part-items').children().length;
        for (var i=1;i<=cnt;i++) {
            part_id       = $('#part-item-'+i).attr('data-part_id');
            part_number   = $('#part-item-'+i+' input[name="number"]').val();
            part_currency = $('#part-item-'+i+' select[name="currency"]').val();
            part_price    = $('#part-item-'+i+' input[name="price"]').val();
            part_items.push({'part_id':part_id,'part_number':part_number,'part_currency':part_currency,'part_price':part_price});
        }
        return part_items;
    }
    function saveParts() {
        if (checkPartItems()==false) return;
        var part_items = pickItems();
        if (part_items.length <=1) {alertMessage('Ошибка: не выбраны товары');return;}
        var url="/<?=Yii::$app->language?>/business/manufacturing-suppliers/save-many-parts-ordering";
        $('#s_btn').attr('disabled',true);
        $.post(url,{
            'suppliers_performers_id':$('select[name="suppliers_performers_id"]').val(),
            'dateReceipt':$('input[name="dateReceipt"] ').val(),
            'part_items':part_items}).done(function (data) {
            if (data.success == true) {
                alertMessage(data.mes);
                setTimeout(function(){
                    window.location.href = "/<?=Yii::$app->language?>/business/manufacturing-suppliers/parts-ordering";
                },5);
            } else {
                console.log('Error='+data.mes);
                $('#s_btn').attr('disabled',false);
            }
        });
    }

</script>

