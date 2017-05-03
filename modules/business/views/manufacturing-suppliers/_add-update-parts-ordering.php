<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\models\CurrencyRate;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_composite_products') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-parts-ordering',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <?=(!empty($model->_id) ? Html::hiddenInput('id', $model->_id,['class'=>'form-control']) : '')?>

            <div class="row">
                <div class="col-md-12">
                    <?=Html::label(THelper::t('goods'))?>
                    <?=Html::dropDownList('parts_accessories_id',
                        (!empty((string)$model->parts_accessories_id) ? (string)$model->parts_accessories_id: ''),
                        PartsAccessories::getListPartsAccessories(),[
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
                <div class="col-md-12">
                    <?=Html::label(THelper::t('sidebar_suppliers_performers'))?>
                    <?=Html::dropDownList('suppliers_performers_id',
                        (!empty((string)$model->suppliers_performers_id) ? (string)$model->suppliers_performers_id: ''),
                        SuppliersPerformers::getListSuppliersPerformers(),[
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
                    <?=Html::label('count_goods')?>
                    <?=Html::input('number','number', (!empty($model->number) ? $model->number: '0'),[
                        'class'=>'form-control',
                        'min'=>'1',
                        'step'=>'1',
                    ])?>
                </div>
                <div class="col-md-3">
                    <?=Html::label('currency')?>
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
                    <?=Html::label('price')?>
                    <?=Html::input('number','price', (!empty($model->price) ? $model->price: '0'),[
                        'class'=>'form-control',
                        'min'=>'0.01',
                        'step'=>'0.01',
                    ])?>
                </div>

                <div class="col-md-3">
                    <?=Html::label('date_receipt')?>
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

<script>
    $(".datepicker-input").datepicker();
</script>

