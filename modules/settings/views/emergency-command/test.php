<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\EmergencyCommand */
/* @var $form ActiveForm */
?>
<div class="test">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'accrued_commission') ?>
    <?= $form->field($model, 'user_authorization') ?>
    <?= $form->field($model, 'user_authorization_txt') ?>
    <?= $form->field($model, 'user_registration') ?>
    <?= $form->field($model, 'user_registration_txt') ?>
    <?= $form->field($model, 'money_transaction') ?>

    <div class="form-group">
        <?= Html::submitButton(\app\components\THelper::t('save'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- test -->
