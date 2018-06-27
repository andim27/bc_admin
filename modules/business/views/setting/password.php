<?php
    use yii\helpers\Html;
    use app\components\THelper;
    use yii\bootstrap\ActiveForm;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('settings_password_title'); ?></h3>
</div>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-12">
        <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true])->label(THelper::t('settings_password_current_password')) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true])->label(THelper::t('settings_password_new_password')) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['maxlength' => true])->label(THelper::t('settings_password_repeat_new_password')) ?>
    </div>
    <div class="col-sm-12 text-center m-b">
        <?= Html::submitButton(THelper::t('settings_password_save'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>