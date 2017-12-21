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

            <div class="form-group field-stockstatus-carrier_id">
                <label class="control-label" for="stockstatus-carrier_id"><?=THelper::t('status_code')?><!--Код статуса--></label>
                <select id="stockstatus-carrier_id" class="form-control" name="StockStatus[carrier_id]">
                    <?php foreach($list as $option){?>
                        <option value="<?=$option['sku']?>"><?=$option['sku']?></option>
                    <?php }?>
                </select>
                <div class="help-block"></div>
            </div>
            <?= $form->field($model, 'carrier_title')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'NP_status')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'NO_status')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="modal-footer">
            <?= Html::submitButton(($title==1)?THelper::t('editing'):THelper::t('create'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


