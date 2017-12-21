<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
    $this->title = THelper::t('recovery_password') ;
?>
<style>
    #passresetformemail-type label{
        padding: 5px;
    }
</style>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="modal-body">
            <div class="row text-center" style="margin-bottom:25px">
                <div class="col-xs-12">
                    <?php if (! $resetPassTime) { ?>

                        <?php $form = ActiveForm::begin(['id' => 'recovery-form-type']); ?>
                            <?php
                                $modelEmail->type = 'messenger';
                            ?>
                            <?= $form->field($modelEmail, 'type')->radioList([
                                'messenger' => THelper::t('messenger'),
                                'email' => THelper::t('email')
                            ])->label(false); ?>
                        <?php ActiveForm::end(); ?>


                        <?php $form = ActiveForm::begin(['id' => 'recovery-form-email', 'action' => ['login/reset-email']]); ?>

                            <?= $form->field($modelEmail, 'email', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('email')])->label(false) ?>

                            <div class="row">
                                <div class="text-center">
                                    <?= Html::submitButton(THelper::t('recover'), ['id' => 'recovery-submit', 'class' => 'btn btn-success btn-sm']) ?>
                                </div>
                            </div>

                        <?php ActiveForm::end(); ?>


                        <?php $form = ActiveForm::begin(['id' => 'recovery-form-messenger', 'action' => ['login/reset-messenger']]); ?>

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
                                <?= $form->field($modelMessenger, 'messengerNumber', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('messenger_number')])->label(false)->hint(THelper::t('messenger_numbe_info')) ?>
                            </div>

                            <div class="row">
                                <div class="text-center">
                                    <?= Html::submitButton(THelper::t('recover'), ['id' => 'recovery-submit', 'class' => 'btn btn-success btn-sm']) ?>
                                </div>
                            </div>

                        <?php ActiveForm::end(); ?>

                        <div id="recovery-email-success" class="alert alert-success" style="display: none;">
                            <?= THelper::t('password_has_been_recovered') ?>
                        </div>

                        <div id="recovery-messenger-success" class="alert alert-success" style="display: none;">
                            <?= THelper::t('password_has_been_recovered_on_messenger') ?>
                        </div>

                    <?php } else { ?>
                        <div class="alert alert-danger">
                            <i class="fa fa-ban-circle"></i>
                            <span><?= THelper::t('login_recovery_pass_time_msg') ?></span>
                            <span><?= $resetPassTime ?></span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJsFile('/js/main/recovery_password.js?v=' . time(), ['depends' => ['yii\web\JqueryAsset']]); ?>
