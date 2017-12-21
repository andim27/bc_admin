<?php use app\components\THelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<div class="modal-dialog" style="width: 90%">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= THelper::t('fin_pass_recovery') ?></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 text-center">
                    <?php $form = ActiveForm::begin(['id' => 'recovery-form-messenger']); ?>

                    <?php
                      //  $modelMessenger->messenger = 'whatsapp';
                    ?>

                    <?= $form->field($modelMessenger, 'messenger')->radioList([
                        'whatsapp' => THelper::t('whatsapp'),
                        'viber'    => THelper::t('viber'),
                        'telegram' => THelper::t('telegram'),
                        'facebook' => THelper::t('facebook'),
                    ],
                        [
                            'item' => function($index, $label, $name, $checked, $value) {

                                $return = '<label class="social-radio" style="margin: 5px; cursor: pointer">';
                                $return .= '<img src="/images/' . $value . '.png" />';
                                $return .= '<p>' . ucwords($label) . '</p>';
                                $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
                                $return .= '</label>';

                                return $return;
                            },
                            'id' => 'messenger'
                        ])->label(false); ?>

                    <div id="messenger-number-block" style="display:none;">
                        <?= $form->field($modelMessenger, 'messengerNumber', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('messenger_number')])->label(false)->hint(THelper::t('messenger_numbe_info')) ?>
                    </div>

                    <div class="row">
                        <div class="text-center">
                            <?= Html::submitButton(THelper::t('recover'), ['id' => 'recovery-submit', 'class' => 'btn btn-success btn-sm']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var $form = $('#recovery-form-messenger');

    $form.on('submit', function(e){
        e.preventDefault();

        var messenger = $('#recovery-form-messenger').find('input[type=radio][name="PassResetFormMessenger[messenger]"]:checked').val();

        if (messenger) {
            $.ajax({
                'url' : $form.attr('action'),
                'type' : $form.attr('method'),
                'data' : $form.serialize(),
                'success' : function (response) {
                    //$('.modal-dialog').find('.close').trigger('click');
                    location.reload();
                }
            });
        }
    });
</script>


