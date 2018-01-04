<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_payment_card') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/transactions/save-payment-card',
                'options' => ['name' => 'savePaymentCard'],
            ]); ?>

            <?=(!empty($model->_id) ? $formCom->field($model, '_id')->hiddenInput()->label(false) : '')?>

            <div class="row">
                <div class="col-md-12">
                    <?= $formCom->field($model, 'title')->textInput()->label(THelper::t('title_name')) ?>
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
