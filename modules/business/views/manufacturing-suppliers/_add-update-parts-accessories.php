<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use kartik\widgets\Select2;
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

            <div class="row">
                <div class="col-md-12">
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
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('Название(en)')?>
                        <?= Select2::widget([
                            'name' => 'PartsAccessories[translations][en]',
                            'value' => (!empty($model->translations['en']) ? $model->translations['en'] : ''),
                            'data' => $existingProducts['en'],
                            'language' => 'ru',
                            'options' => [
                                'placeholder' => '',
                                'multiple' => false
                            ],
                            'pluginOptions' => [
                                'tags' => true
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
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

            <div class="row">
                <div class="col-md-12">
                    <?= $formCom->field($model, 'last_price_eur')->textInput([
                        'disabled' => ((empty($model->last_price_eur)) ? false : true)
                    ])->label(THelper::t('price_for_one_pcs')) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('Доставляется из Китая')?>
                        <?= Html::checkbox('PartsAccessories[delivery_from_chine]',((!empty($model->delivery_from_chine) && $model->delivery_from_chine==1)? true : false),[
                            'class'=>''
                        ]);
                        ?>
                    </div>
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
