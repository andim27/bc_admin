<?php
    use app\components\THelper;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'action' => '/' . $language . '/business/user/purchase']); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('users_purchase_add_title') ?></h4>
        </div>
        <div class="modal-body">
            <div class="raw">
                <div class="col-md-6">
                    <?= $form->field($model, 'product')->dropDownList($productsSelect, ['prompt' => THelper::t('user_purchase_add_select_product')])->label(false); ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'user')->textInput(['class' => 'form-control'])->label(THelper::t('user_purchase_add_user')) ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-center">
                <?= Html::submitButton(THelper::t('users_purchase_add_save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>