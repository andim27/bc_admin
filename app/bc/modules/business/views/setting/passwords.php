<?php
    use yii\helpers\Html;
    use app\components\THelper;
    use yii\bootstrap\ActiveForm;
    $this->title = THelper::t('passwords');
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-12">
        <h4><?= THelper::t('main_password') ?></h4>
    </div>
    <div class="col-sm-12">
        <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true])->label(THelper::t('current_password'), ['class' => 'pull', 'style' => 'margin: 7px 10px 0px 0px']) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true])->label(THelper::t('new_password'), ['class' => 'pull', 'style' => 'margin: 7px 10px 0px 0px']) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['maxlength' => true])->label(THelper::t('repeat_new_password'), ['class' => 'pull', 'style' => 'margin: 7px 10px 0px 0px']) ?>
    </div>
    <div class="col-sm-12 text-center m-b">
        <?php $model->type = 0; ?>
        <?= $form->field($model, 'type')->hiddenInput()->label(false); ?>
        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-primary main']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-sm-12">
        <h4><?= THelper::t('finance_password') ?></h4>
    </div>
    <div class="col-sm-12">
        <?= $form->field($model, 'currentfinPassword')->passwordInput(['maxlength' => true])->label(THelper::t('current_finance_password'), ['class' => 'pull', 'style' => 'margin: 7px 10px 0px 0px']) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'newfinPassword')->passwordInput(['maxlength' => true])->label(THelper::t('new_finance_password'), ['class' => 'pull', 'style' => 'margin: 7px 10px 0px 0px']) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'newfinPasswordRepeat')->passwordInput(['maxlength' => true])->label(THelper::t('repeat_new_finance_password'), ['class' => 'pull', 'style' => 'margin: 7px 10px 0px 0px']) ?>
    </div>
    <div class="col-sm-12 text-center m-b">
        <?php $model->type = 1; ?>
        <?= $form->field($model, 'type')->hiddenInput()->label(false); ?>
        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-primary finance']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="row">
    <div class="col-sm-12 text-center m-b">
        <?= Html::a(THelper::t('reset_finance_password'), ['reset-finance-password'], array('class' => 'btn btn-s-md btn-info', 'data-toggle' => 'ajaxModal')); ?>
    </div>
</div>

<br>