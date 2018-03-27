<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['action' => '/' . $language . '/business/backoffice/promotion-edit']); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('backoffice_promotion_add_title') ?></h4>
        </div>
        <div class="modal-body">
            <?= $form->field($editPromotionForm, 'id')->hiddenInput()->label(false)->error(false) ?>
            <?= $form->field($editPromotionForm, 'author')->hiddenInput()->label(false)->error(false) ?>
            <?= $form->field($editPromotionForm, 'lang')->hiddenInput()->label(false)->error(false) ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($editPromotionForm, 'dateStart')->textInput()->label(THelper::t('backoffice_promotion_add_date_start')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($editPromotionForm, 'dateFinish')->textInput()->label(THelper::t('backoffice_promotion_add_date_finish')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($editPromotionForm, 'title')->textInput()->label(THelper::t('backoffice_promotion_add_title')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($editPromotionForm, 'body')->textarea()->label(THelper::t('backoffice_promotion_add_body')) ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-center">
                <?= Html::submitButton(THelper::t('backoffice_promotion_add_save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    $('#editpromotionform-lang').val(language);
    $('#editpromotionform-author').val(author);
    tinymce.remove();
    tinymce.init({
        selector:'textarea',
        paste_data_images: true,
        plugins : 'advlist autolink link image lists charmap print preview fullscreen'
    });
    $('.modal').on('hidden.bs.modal', function () {

    })
</script>