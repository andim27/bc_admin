<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use kartik\widgets\Select2;
use app\models\CurrencyRate;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_parts_accessories') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-parts-accessories',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <?=(!empty($model->_id) ? $formCom->field($model, '_id')->hiddenInput()->label(false) : '')?>

            <div class="row form-group">
                <div class="col-md-12">
                    <?= $formCom->field($model, 'article')->textInput()->label(THelper::t('article')) ?>
                </div>
            </div>

            <?= $formCom->field($model, 'title')->widget(Select2::className(),[
                'data' => $existingProducts['ru'],
                'language' => 'ru',
                'options' => [
                    'placeholder' => '',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    'tags' => true
                ]
            ])->label('Название') ?>

            <?= $formCom->field($model, 'translations[en]')->widget(Select2::className(),[
                'data' => $existingProducts['en'],
                'language' => 'ru',
                'options' => [
                    'placeholder' => '',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    'tags' => true
                ]
            ])->label('Название(en)') ?>

            <div class="row form-group">
                <div class="col-md-12">
                    <?=Html::dropDownList('PartsAccessories[unit]',(!empty($model->unit) ? $model->unit : ''),PartsAccessories::getListUnit(),[
                        'class'=>'form-control',
                        'id'=>'selectChangeStatus',
                        'required'=>'required',
                        'options' => [
                            '' => ['disabled' => true]
                        ]
                    ])?>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-9">
                    <?= $formCom->field($model, 'last_price_eur')->textInput([
                        'disabled' => ((empty($model->last_price_eur)) ? false : true)
                    ])->label(THelper::t('price_for_one_pcs')) ?>
                </div>
                <div class="col-md-3">
                    <?=Html::label('Валюта')?>
                    <?=Html::dropDownList('PartsAccessories[currency]',
                        ((empty($model->last_price_eur)) ? 'uah' : 'eur'),
                        CurrencyRate::getListCurrency(),[
                            'class'=>'form-control',
                            'id'=>'selectChangeStatus',
                            'required'=>'required',
                            'disabled' => ((empty($model->last_price_eur)) ? false : true)
                        ])?>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-12">
                    <?= Html::label('Доставляется из Китая')?>
                    <?= Html::checkbox('PartsAccessories[delivery_from_chine]',((!empty($model->delivery_from_chine) && $model->delivery_from_chine==1)? true : false),[
                        'class'=>''
                    ]);
                    ?>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-12">
                    <?= Html::label('Ремонтный фонд')?>
                    <?= Html::checkbox('PartsAccessories[repair_fund]',((!empty($model->repair_fund))? true : false),[
                        'class'=>'flRepairFund'
                    ]);
                    ?>
                    
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-12">
                    <?= Html::label('Обменный фонд')?>
                    <?= Html::checkbox('PartsAccessories[exchange_fund]',((!empty($model->exchange_fund))? true : false),[
                        'class'=>'flExchangeFund'
                    ]);
                    ?>
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
