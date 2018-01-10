<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['id' => $translationForm->formName(), 'action' => '/' . $language . '/business/setting/delete-translation']); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('settings_translation_delete_title') ?></h4>
        </div>
        <div class="modal-body">
            <?= $form->field($translationForm, 'id')->hiddenInput()->label(false) ?>
            <?= $form->field($translationForm, 'countryId')->hiddenInput()->label(false) ?>
            <?= $form->field($translationForm, 'stringId')->hiddenInput()->label(false) ?>

            <div class="text-center">
                <?= Html::radio('delete_items', true, ['value' => 'one', 'label' => THelper::t('settings_translation_delete_one')]) ?>
                &nbsp;
                <?= Html::radio('delete_items', false, ['value' => 'all', 'label' => THelper::t('settings_translation_delete_all')]) ?>
                &nbsp;
                <?= Html::radio('delete_items', false, ['value' => 'all_except_this', 'label' => THelper::t('settings_translation_delete_all_except_this')]) ?>
                <br>

                <a href="javascript:void(0);" class="btn btn-danger delete-translation" data-dismiss="modal">
                    <?= THelper::t('settings_translation_delete') ?>
                </a>

                <a href="javascript:void(0);" class="btn btn-warning" data-dismiss="modal">
                    <?= THelper::t('settings_translation_cancel') ?>
                </a>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

