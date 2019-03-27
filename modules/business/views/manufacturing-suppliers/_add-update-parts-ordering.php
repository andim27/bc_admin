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
$listPartsUnit = PartsAccessories::getListUnit();
?>

<div class="modal-dialog popupPartsOrdering">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_parts_ordering') ?></h4>
            <h5>(<?=$action ?? '?' ?>)</h5>
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
                    <?=Html::label(THelper::t('goods'))?>
                    <?php if ($action =='edit') {?>
                        <input type="text" class="form-control" name="part_title" value="<?=$part_title ?>" disabled min="1" step="1">
                        <?=(!empty($model->parts_accessories_id) ? Html::hiddenInput('parts_accessories_id', $model->parts_accessories_id,['class'=>'form-control']) : '')?>
                    <?php } else { ?>
                        <?= Select2::widget([
                            'name' => 'parts_accessories_id',
                            'data' => $listGoods,
                            'options' => [
                                'placeholder' => 'Выберите товар',
                                'multiple' => true
                            ]
                        ]);
                        ?>

<!--                        <select class="form-control"  id="part-accessors" name="parts_accessories_ids" multiple="multiple" >-->
<!--                            --><?php //foreach ($listGoods as $key=>$value) { ?>
<!--                                --><?php //if ($key!= '') { ?>
<!--                                        <option  value="--><?//=$key ?><!--">--><?//=$value ?><!--</option>-->
<!--                                --><?php //} ?>
<!--                            --><?php //} ?>
<!---->
<!--                        </select>-->
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?=Html::label(THelper::t('sidebar_suppliers_performers'))?>
                    <?php if ($action =='edit') {?>
                        <input type="text" class="form-control" name="sup_title" value="<?=$sup_title ?>" disabled min="1" step="1">
                        <?=(!empty($model->suppliers_performers_id) ? Html::hiddenInput('suppliers_performers_id', $model->suppliers_performers_id,['class'=>'form-control']) : '')?>
                    <?php } else { ?>
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
                    <?php }?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    Количество
                    <?=Html::input('number','number', (!empty($model->number) ? $model->number: '1'),[
                        'class'=>'form-control',
                        'min'=>'1',
                        'step'=>'1',
                    ])?>
                </div>
                <div class="col-md-2">
                    Ед.изм.
                    <?=Html::dropDownList('unit',(!empty($model->unit) ? $model->unit : ''),$listPartsUnit,[
                        'class'=>'form-control',
                        'id'=>'selectChangeStatus',
                        'required'=>'required',
                        'options' => [
                            '' => ['disabled' => true]
                        ]
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

                <div class="col-md-3">
                    Полная стоимость
                    <?=Html::input('number','price', (!empty($model->price) ? $model->price: '0'),[
                        'class'=>'form-control',
                        'min'=>'0.01',
                        'step'=>'0.01',
                    ])?>
                </div>

                <div class="col-md-3">
                    Дата прибития
                    <?=Html::input('text','dateReceipt',
                        (!empty($model->dateReceipt) ? $model->dateReceipt->toDateTime()->format('Y-m-d') : date('Y-m-d')),
                        [
                            'class' => 'form-control datepicker-input',
                            'data-date-format'=>'yyyy-mm-dd'
                        ])?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
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
</script>

