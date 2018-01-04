<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;

$this->title = THelper::t('emergency_command');
$this->params['breadcrumbs'][] = $this->title;
?>
    <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=THelper::t('accrual_commissions')?><!--Начисление комиссионных--></label>
             <label class="switch">
                 <input type="checkbox"
                     <?=(isset($model->accrued_commission) && $model->accrued_commission == 1)?'checked':'';?>
                        name="EmergencyCommand[accrued_commission]">
                 <span></span>
             </label>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=THelper::t('user_authorization')?><!--Авторизация пользователей--></label>
            <label class="switch">
                <input type="checkbox"
                    <?=(isset($model->user_authorization) && $model->user_authorization == 1)?'checked':'';?>
                       name="EmergencyCommand[user_authorization]">
                <span></span>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=THelper::t('the_inscription_at_the_closing')?><!--Надпись при закрытии--></label>
            <input type="text" name="EmergencyCommand[user_authorization_txt]"
                <?php
                if(!empty($model->user_authorization_txt)){?>
                    placeholder="<?=$model->user_authorization_txt?>"
                    <?php
                }
                ?>>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=THelper::t('user_registration')?><!--Регистрация пользователей--></label>
            <label class="switch"> <input type="checkbox" name="EmergencyCommand[user_registration]"
                    <?=(isset($model->user_registration) && $model->user_registration == 1)?'checked':'';?>
                    >
                <span></span>
            </label>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=THelper::t('the_inscription_at_the_closing')?><!--Надпись при закрытии--></label>
            <input type="text" name="EmergencyCommand[user_registration_txt]"
                <?php
                if(!empty($model->user_registration_txt)){
                    ?>
                    placeholder="<?=$model->user_registration_txt?>"
                    <?php
                }
                ?>>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><?=THelper::t('money_transaction')?><!--Перевод средств--></label>
            <label class="switch"> <input type="checkbox" name="EmergencyCommand[money_transaction]"
                    <?=(isset($model->money_transaction) && $model->money_transaction == 1)?'checked':'';?>>
                <span></span>
            </label>
        </div>
        <div class="form-group">
            <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end();?>