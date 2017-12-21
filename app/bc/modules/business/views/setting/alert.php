<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    $this->title = THelper::t('alert');
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row m-b-md">
        <div class="col-sm-2 m-b-sm min-width-205">
            <img src="/images/robot.png" />
        </div>
        <div class="col-sm-9 text-justify">
            <?= THelper::t('busprobot_description') ?>
        </div>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div style="max-width: 500px;">
        <div class="row m-b-sm">
            <div class="col-sm-2 col-xs-4 m-b-sm"><img src="/images/whatsapp.png" /></div>
            <div class="col-sm-2 col-xs-8 text-left m-b-sm p-t-25"><?= THelper::t('whatsapp') ?></div>
            <div class="col-sm-8 col-xs-12 m-b-sm p-t-20">
                <?= $form->field($model, 'phoneWhatsApp')->textInput(['maxlength' => 16])->label(false) ?>
            </div>
        </div>
        <div class="row m-b-sm">
            <div class="col-sm-2 col-xs-4 m-b-sm"><img src="/images/viber.png" /></div>
            <div class="col-sm-2 col-xs-8 text-left m-b-sm p-t-25"><?= THelper::t('viber') ?></div>
            <div class="col-sm-8 col-xs-12 m-b-sm p-t-20">
                <?= $form->field($model, 'phoneViber')->textInput(['maxlength' => 16])->label(false) ?>
            </div>
        </div>
        <div class="row m-b-sm">
            <div class="col-sm-2 col-xs-4 m-b-sm"><img src="/images/telegram.png" /></div>
            <div class="col-sm-2 col-xs-8 text-left m-b-sm p-t-25"><?= THelper::t('telegram') ?></div>
            <div class="col-sm-8 col-xs-12 p-t-20">
                <?= $form->field($model, 'phoneTelegram')->textInput(['maxlength' => 16])->label(false) ?>
            </div>
        </div>
        <div class="row m-b-md">
            <div class="col-sm-2 col-xs-4 m-b-sm"><img src="/images/facebook.png" /></div>
            <div class="col-sm-2 col-xs-8 text-left m-b-sm p-t-25"><?= THelper::t('facebook') ?></div>
            <div class="col-sm-8 col-xs-12 p-t-20">
                <?= $form->field($model, 'phoneFB')->textInput(['maxlength' => 16])->label(false) ?>
            </div>
        </div>
        <div class="row m-b-md">
            <div class="col-sm-7 m-b-sm"><?= THelper::t('what_is_your_language') ?></div>
            <div class="col-sm-5 m-b-sm">
                <?= $form->field($model, 'selectedLang')->dropDownList($languages)->label(false); ?>
            </div>
        </div>
        <div class="row m-b-md">
            <div class="col-sm-7 m-b-sm"><?= THelper::t('timezone') ?></div>
            <div class="col-sm-5 m-b-sm">
                <select id="select-timezone" class="form-control block" name="timezone">
                    <?php foreach ($timezones as $timezone) { ?>
                        <option value="<?= str_replace('"', '|', json_encode($timezone, JSON_UNESCAPED_SLASHES)) ?>" <?= isset($user->settings->timeZone) ? (str_replace('"', '|', json_encode($user->settings->timeZone, JSON_UNESCAPED_SLASHES)) == str_replace('"', '|', json_encode($timezone, JSON_UNESCAPED_SLASHES)) ? 'selected="selected"' : '') : '' ?>><?= $timezone->text ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default padder-v col-sm-12">
            <?= $form->field($model, 'notifyAboutJoinPartner')->checkbox(['label' => THelper::t('notify_about_join_partner')]); ?>
            <?= $form->field($model, 'notifyAboutReceiptsMoney')->checkbox(['label' => THelper::t('notify_about_receipts_money')]); ?>
            <?= $form->field($model, 'notifyAboutReceiptsPoints')->checkbox(['label' => THelper::t('notify_about_receipts_points')]); ?>
            <?= $form->field($model, 'notifyAboutEndActivity')->checkbox(['label' => THelper::t('notify_about_end_activity')]); ?>
            <?= $form->field($model, 'notifyAboutOtherNews')->checkbox(['label' => THelper::t('notify_about_other_news')]); ?>
            <?= Html::submitButton(THelper::t('save'), array('class'=>'btn btn-s-md btn-success business_alert')); ?>
        </div>
    </div>
    <?php $form = ActiveForm::end(); ?>
</div>
<script>
    $('input[type="text"]').keyup(function() {
        if ($(this).val()[0] != '+' && $(this).val().length > 0) {
            $(this).val('+' + $(this).val());
        }
    });
</script>