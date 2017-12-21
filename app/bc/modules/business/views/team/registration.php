<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= THelper::t('new_partner_registartion') ?></h4>
        </div>
        <div class="modal-body">
            <div class="row" style="margin-bottom:25px">
                <div class="col-xs-12">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'ref')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'name', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('name')])->label(false)->hint(THelper::t('writing_in_cyrillic_or_latin_alphabet')) ?>
                    <?= $form->field($model, 'second_name', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('surname')])->label(false)->hint(THelper::t('writing_in_cyrillic_or_latin_alphabet')) ?>
                    <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('email')])->label(false) ?>
                    <?= $form->field($model, 'login', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('login')])->label(false)->hint(THelper::t('latin_letters_numbers_and_dashes_allowed')) ?>
                    <?= $form->field($model, 'mobile', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('mobile_phone')])->label(false)->hint(THelper::t('only_numbers')) ?>
                    <?= $form->field($model, 'skype', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('skype')])->label(false)->hint(THelper::t('this_is_not_a_required_field')) ?>
                    <?= $form->field($model, 'messenger', ['enableAjaxValidation' => false])->dropDownList([
                        'whatsapp' => THelper::t('whatsapp'),
                        'viber'    => THelper::t('viber'),
                        'telegram' => THelper::t('telegram'),
                        'facebook' => THelper::t('facebook'),
                    ], ['id' => 'messenger', 'prompt' => THelper::t('select_messenger')])->label(false)->hint(THelper::t('messenger_info')) ?>
                    <div id="messenger-number-block" style="display:none;">
                        <?= $form->field($model, 'messengerNumber', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('messenger_number')])->label(false)->hint(THelper::t('messenger_numbe_info')) ?>
                    </div>
                    <?= $form->field($model, 'country_id', ['enableAjaxValidation' => false])->dropDownList($countries, ['prompt' => THelper::t('select_country')])->label(false); ?>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <?= $form->field($model, 'pass', ['enableAjaxValidation' => false])->passwordInput(['placeholder' =>  THelper::t('security_password')])->label(false)->hint(THelper::t('not_less_than_6_characters')) ?>
                        </div>
                        <div class="form-group col-xs-6">
                            <?= $form->field($model, 'password_repeat', ['enableAjaxValidation' => false])->passwordInput(['placeholder' => THelper::t('repeat_the_password_entry')])->label(false) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-xs-6">
                            <?= $form->field($model, 'finance_pass', ['enableAjaxValidation' => false])->passwordInput(['placeholder' => THelper::t('password_on_financial_transactions')])->label(false)->hint(THelper::t('not_less_than_6_characters')) ?>
                        </div>
                        <div class="form-group col-xs-6">
                            <?= $form->field($model, 'password_repeat_finance', ['enableAjaxValidation' => false])->passwordInput(['placeholder' => THelper::t('repeat_password_on_financial_transactions')])->label(false) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center">
                            <?= Html::submitButton(THelper::t('register'), ['class' => 'btn btn-default btn-sm btn-next register']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#messenger').change(function() {
        var value = $(this).val();
        var messengerNumberBlock = $('#messenger-number-block');

        if (value) {
            messengerNumberBlock.show();
        } else {
            messengerNumberBlock.hide();
        }
    });
</script>