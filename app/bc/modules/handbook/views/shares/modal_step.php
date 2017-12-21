<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
?>
<div class="modal-dialog">
    <?php $form = ActiveForm::begin(); ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?=($title==1)?THelper::t('editing'):THelper::t('create');?></h4>
        </div>
        <div class="modal-body">

            <div class="form-group field-stockstep-product_id">
                <label class="control-label" for="stockstep-product_id"><?=THelper::t('product_code')?><!--Код товара--></label>
                <select id="stockstep-product_id" class="form-control" name="StockStep[product_id]">
                    <?php foreach($list as $option){?>
                        <option value="<?=$option['sku']?>"><?=$option['sku']?></option>
                    <?php }?>
                </select>
                <div class="help-block"></div>
            </div>
            <?= $form->field($model, 'product_title')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="modal-footer">
            <?= Html::submitButton(($title==1)?THelper::t('editing'):THelper::t('create'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


