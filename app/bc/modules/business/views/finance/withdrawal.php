<?php
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use app\components\THelper;

    echo count($availableCards);
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= THelper::t('withdrawal_data') ?></h4>
        </div>
        <div class="modal-body">

            <?php if(count($availableCards) == 1){ ?>
            <div class="alert alert-danger fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?=THelper::t('no_cards_linked_to_your_profile')?>
            </div>
            <?php } ?>

            <div class="row m-b">
                <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
                <div class="col-sm-12">
                    <label><?=THelper::t('card_type')?></label>
                    <select id="cardform-type" class="form-control cardType" name="CardForm[type]" required="required">
                        <?php foreach($availableCards as $item) { ?>
                            <option value="<?=$item['key']?>" data-card="<?=$item['card']?>">
                                <?=$item['value']?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <?= $form->field($model, 'amount')->hiddenInput()->label(false)->error(false) ?>
                <div style="display: none" id="card-personal-data">
                    <div class="col-sm-8">
                        <?= $form->field($model, 'number')->textInput()->label(THelper::t('card_number')); ?>
                    </div>
                    <div class="col-sm-4">
                        <?= $form->field($model, 'system')->dropDownList($systems, ['prompt' => THelper::t('select_system')])->label(THelper::t('card_system')); ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'expirationMonth')->dropDownList($months, ['prompt' => THelper::t('select_expiration_month')])->label(THelper::t('card_expiration_month')); ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'expirationYear')->dropDownList($years, ['prompt' => THelper::t('select_expiration_year')])->label(THelper::t('card_expiration_year')); ?>
                    </div>
                    <div class="col-sm-12">
                        <?= $form->field($model, 'holder')->textInput()->label(THelper::t('card_holder')); ?>
                    </div>
                    <div class="col-sm-12">
                        <?= $form->field($model, 'financePassword')->passwordInput(['readonly' => 'readonly', 'onfocus' => '$(this).removeAttr(\'readonly\');', 'style' => 'cursor:text;background-color: #fff;'])->label(THelper::t('password_on_financial_transactions')) ?>
                    </div>
                </div>
                <div style="display: none" id="card-submit-button">
                    <div class="col-sm-12 text-center">
                        <?= $form->field($model, 'moneys')->hiddenInput()->label(false); ?>
                        <?= Html::submitButton(THelper::t('withdrawal_order'), ['class' => 'btn btn-info']); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $('#cardform-type').change(function() {
        var cardPersonalData = $('#card-personal-data');
        var cardSubmitButton = $('#card-submit-button');
        var cardType = $(this).val();

        if(cardType == 2){
            $('#cardform-amount').attr('id', '_cardform-amount');
            $('#_cardform-number').attr('id', 'cardform-number');
            $('#_cardform-system').attr('id', 'cardform-system');
            $('#_cardform-expirationmonth').attr('id', 'cardform-expirationmonth');
            $('#_cardform-expirationyear').attr('id', 'cardform-expirationyear');
            $('#_cardform-holder').attr('id', 'cardform-holder');
            $('#_cardform-financepassword').attr('id', 'cardform-financepassword');
            cardPersonalData.show();
            cardSubmitButton.show();
        } else {
            $('#cardform-number').val($(this).find(":selected").data('card'));

            $('#cardform-amount').attr('id', '_cardform-amount');
            $('#cardform-number').attr('id', '_cardform-number');
            $('#cardform-system').attr('id', '_cardform-system');
            $('#cardform-expirationmonth').attr('id', '_cardform-expirationmonth');
            $('#cardform-expirationyear').attr('id', '_cardform-expirationyear');
            $('#cardform-holder').attr('id', '_cardform-holder');
            $('#cardform-financepassword').attr('id', '_cardform-financepassword');
            cardPersonalData.hide();
            cardSubmitButton.show();
        }


    });

    $('#cardform-amount').val(withdrawalAmount);
</script>