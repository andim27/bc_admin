<?php

use app\components\THelper;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

<div class="modal-dialog popupPincode">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('users_pincode_title') ?></h4>
        </div>

        <div class="modal-body">
            <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'action' => '/' . $language . '/business/user/pincode']); ?>

            <?= $form->field($model, 'user')->hiddenInput()->label(false) ?>

            <div class="raw">
                <div class="col-md-12">
                    <?= $form->field($model, 'warehouse')->dropDownList($warehouses, ['prompt' => THelper::t('choose_a_warehouse')])->label(false); ?>
                </div>
            </div>

            <div class="raw">
                <div class="col-md-12">
                    <?= $form->field($model, 'pin')->textInput(['class' => 'form-control'])->label(THelper::t('user_pin')) ?>
                </div>
            </div>

            <div class="text-center">
                <?= Html::submitButton(THelper::t('buy_product_with_pincode'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="modal-footer">
        </div>

    </div>
</div>