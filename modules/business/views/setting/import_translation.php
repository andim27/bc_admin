<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('settings_translation_import_title') ?></h4>
        </div>
        <div class="modal-body">
            <?= $form->field($importTranslationForm, 'lang')->hiddenInput()->label(false)->error(false) ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($importTranslationForm, 'file')->fileInput()->label(THelper::t('settings_translation_import_file'))->hint(THelper::t('settings_translation_import_file_hint')) ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-center">
                <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>