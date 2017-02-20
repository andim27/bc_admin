<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\THelper;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\PasswordForm */

$this->title = THelper::t('change_my_password');
?>

<div class="user-profile-change-password" style="width: 50%">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'currentPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPassword')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'newPasswordRepeat')->passwordInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(THelper::t('change_password'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>