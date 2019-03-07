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
            <h5>ADD</h5>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-parts-ordering',
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
                            'options' => [
                                '' => ['disabled' => true]
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
                                'placeholder' => 'Выберите товар',

                            ]
                        ]);
                        ?>

                </div>
                <div class="col-md-4">
                    <button id="add-part-btn"  type="button" class="btn btn-default m-top-25"  onclick="addPart()" title="Добавить товар">+</button>
                </div>
            </div>


            <div class="row b-bottom-dotted" id="part-items" style="padding-right: 15px">


                <div class="row m-bottom-25" id="part-item-1">
                <div class="col-md-5">
                    <h4 class="text-primary text-right m-top-25 m-left-10">Название товара</h4>
                </div>
                <div class="col-md-2">
                    Количество
                    <?=Html::input('number','number', (!empty($model->number) ? $model->number: '1'),[
                        'class'=>'form-control',
                        'min'=>'1',
                        'step'=>'1',
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
                    ])?>
                </div>
                 <div class="col-md-1">
                     <button  id="del-part-btn"  type="button" class="btn btn-default m-top-20" data-pos=1 onclick="delPart(this)"><span class="glyphicon glyphicon-minus-sign"></span> </button>
                 </div>

            </div>
            </div>
            <br>
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
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>

<script type="text/javascript">

    $(".datepicker-input").datepicker();

    arrayGoodsComposite = <?=json_encode($listGoodsWithComposite)?>;
    $('#part-accessors').multiselect();
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
    function clearError() {
        $(".popupPartsOrdering .blError").html('');
    }
    //----------------------

    p_items_cnt =0;
    function addPart() {
        var p_item = $('#part-item-1').clone();
        $('#part-items').append(p_item);
        var cnt =  $('#part-items').children().length;
        $('#part-items div:last #del-part-btn').attr('data-pos',cnt);
        p_items_cnt+=1;
    }
    function delPart(el) {
        if (p_items_cnt >0) {
            p_items_cnt-=1;
            //alert('del '+$(el).attr('data-pos') );
            $(el).parent().parent().remove();
        }


    }

</script>

