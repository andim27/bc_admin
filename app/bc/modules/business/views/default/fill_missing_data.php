<?php
    use app\components\UserHelper;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\components\THelper;
?>
<div class="main">
    <section id="content" class="m-t-lg">
        <?php $form = ActiveForm::begin(); ?>

        <div>
            <?php if (in_array('firstName', $empty_fields)) { ?>
                <?= $form->field($model, 'name', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('name'), 'value' => $user->firstName])->label(false)->hint(THelper::t('writing_in_cyrillic_or_latin_alphabet')) ?>
            <?php } ?>

            <?php if (in_array('secondName', $empty_fields)) { ?>
                <?= $form->field($model, 'second_name', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('surname'), 'value' => $user->secondName])->label(false)->hint(THelper::t('writing_in_cyrillic_or_latin_alphabet')) ?>
            <?php } ?>

            <?php if (in_array('email', $empty_fields)) { ?>
                <?= $form->field($model, 'email', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('email'), 'value' => $user->email])->label(false) ?>
            <?php } ?>

            <?php if (in_array('username', $empty_fields)) { ?>
                <?= $form->field($model, 'login', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('login')])->label(false)->hint(THelper::t('latin_letters_numbers_and_dashes_allowed')) ?>
            <?php } ?>

            <?php if (!empty($user) && !($user->phoneNumber || $user->phoneNumber2)) { ?>
                <?= $form->field($model, 'mobile', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('mobile_phone')])->label(false)->hint(THelper::t('only_numbers')) ?>
            <?php } ?>

            <?php if (!empty($user) && !$user->skype) { ?>
                <?= $form->field($model, 'skype', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('skype')])->label(false)->hint(THelper::t('latin_letters_numbers_and_dashes_allowed')) ?>
            <?php } ?>

            <?php if (UserHelper::hasNoMessengerPhones($empty_fields)) { ?>
                <?= $form->field($model, 'messenger', ['enableAjaxValidation' => false])->dropDownList([
                    'whatsapp' => THelper::t('whatsapp'),
                    'viber'    => THelper::t('viber'),
                    'telegram' => THelper::t('telegram'),
                    'facebook' => THelper::t('facebook'),
                ], ['id' => 'messenger', 'prompt' => THelper::t('select_messenger')])->label(false)->hint(THelper::t('messenger_info')) ?>

                <div id="messenger-number-block" style="display:none;">
                    <?= $form->field($model, 'messengerNumber', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('messenger_number')])->label(false)->hint(THelper::t('messenger_numbe_info')) ?>
                </div>
            <?php } ?>

            <?php if (in_array('username', $empty_fields)) { ?>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <?= $form->field($model, 'finance_pass', ['enableAjaxValidation' => false])->passwordInput(['placeholder' => THelper::t('password_on_financial_transactions')])->label(false)->hint(THelper::t('not_less_than_6_characters')) ?>
                    </div>
                    <div class="form-group col-xs-6">
                        <?= $form->field($model, 'password_repeat_finance', ['enableAjaxValidation' => false])->passwordInput(['placeholder' => THelper::t('repeat_password_on_financial_transactions')])->label(false) ?>
                    </div>
                </div>
            <?php } ?>

            <div class="actions m-t">
                <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-default btn-sm']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </section>
</div>


<?php $this->registerJsFile('/js/fuelux/fuelux.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('/js/main/registration.js?a=' . md5(time())); ?>
<?php $this->registerCssFile('/js/fuelux/fuelux.css',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('/css/main.css',['depends'=>['app\assets\AppAsset']]); ?>


