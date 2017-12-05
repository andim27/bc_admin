<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\Settings;
use kartik\widgets\Select2;
use app\components\ArrayInfoHelper;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('warehouse') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/setting/save-warehouse',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <?=(!empty($model->_id) ? $formCom->field($model, '_id')->hiddenInput()->label(false) : '')?>

            <div class="row">
                <div class="col-md-12">
                    <?= $formCom->field($model, 'title')->textInput(['required'=>'required'])->label(THelper::t('name_product')) ?>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-12">
                    <label><?=THelper::t('country')?></label>
                    <?=Html::dropDownList('Warehouse[country]',(!empty($model->country) ? $model->country : ''),Settings::getListCountry(),[
                        'class'=>'form-control',
                        'prompt' => '---------- Выберите страну ----------',
                        'id'=>'countryReport',
                        'options' => [
                        ]
                    ])?>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-12">
                    <label for="">Города</label>
                    <?=Select2::widget([
                        'name' => 'Warehouse[cities][]',
                        'value' => (!empty($model->cities) ? $model->cities : ''),
                        'data' => (!empty($model->cities) ? ArrayInfoHelper::getArrayEqualKeyValue($model->cities) : []),
                        'maintainOrder' => true,
                        'toggleAllSettings' => [
                            'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> Выбрать все',
                            'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> Удалить все',
                            'selectOptions' => ['class' => 'text-success'],
                            'unselectOptions' => ['class' => 'text-danger'],
                        ],
                        'options' => ['placeholder' => 'Добавьте город ...', 'multiple' => true],
                        'pluginOptions' => [
                            'tags' => true,
                            'maximumInputLength' => 10
                        ],
                    ]);?>
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
