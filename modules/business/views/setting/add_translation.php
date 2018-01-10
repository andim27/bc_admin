<?php
    use yii\widgets\ActiveForm;
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['id' => $translationForm->formName(), 'action' => '/business/setting/add-translation']); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('settings_translation_edit_title') ?></h4>
        </div>
        <div class="modal-body">
            <?= $form->field($translationForm, 'countryId')->hiddenInput()->label(false) ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($translationForm, 'stringId')->textInput()->label(THelper::t('settings_translation_edit_key')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($translationForm, 'stringValue')->textarea()->label(THelper::t('settings_translation_edit_value')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($translationForm, 'comment')->textarea()->label(THelper::t('settings_translation_edit_comment')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($translationForm, 'originalStringValue')->textInput()->label(THelper::t('settings_translation_edit_original_value')) ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-center">
                <input type="submit" class="btn btn-success" value="<?= THelper::t('settings_translation_add') ?>">
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

