<?php
    use app\components\THelper;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'action' => '/' . $language . '/business/setting/admin']); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('setting_admin_add_title') ?></h4>
        </div>
        <div class="modal-body">
            <div class="raw">
                <div class="col-md-12">
                    <?= $form->field($addAdminForm, 'user')->textInput(['class' => 'form-control'])->label(THelper::t('setting_admin_user')) ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-center">
                <?= Html::submitButton(THelper::t('setting_admin_add_save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>