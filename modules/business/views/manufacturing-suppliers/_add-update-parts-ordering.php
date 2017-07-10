<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\models\CurrencyRate;


$listGoods = PartsAccessories::getListPartsAccessories();
$listGoods = ArrayHelper::merge([''=>'Выберите товар'],$listGoods);

$listSuppliersPerformers=SuppliersPerformers::getListSuppliersPerformers();
$listSuppliersPerformers = ArrayHelper::merge([''=>'Выберите поставщика-испонителя'],$listSuppliersPerformers);

$listGoodsWithComposite = PartsAccessories::getListPartsAccessoriesWithComposite();
?>

<div class="modal-dialog popupPartsOrdering">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_parts_ordering') ?></h4>
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
                    <?=Html::dropDownList('parts_accessories_id',
                        (!empty((string)$model->parts_accessories_id) ? (string)$model->parts_accessories_id: ''),
                        $listGoods,[
                            'class'=>'form-control',
                            'id'=>'partsAccessoriesId',
                            'required'=>'required',
                            'options' => [
                                '' => ['disabled' => true]
                            ]
                        ])?>
                </div>
            </div>

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
                <div class="col-md-3">
                    Количество
                    <?=Html::input('number','number', (!empty($model->number) ? $model->number: '1'),[
                        'class'=>'form-control',
                        'min'=>'1',
                        'step'=>'1',
                    ])?>
                </div>
                <div class="col-md-3">
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

