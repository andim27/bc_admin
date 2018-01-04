<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['action' => '/' . $language . '/business/backoffice/news-edit']); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('backoffice_news_edit_title_title') ?></h4>
        </div>
        <div class="modal-body">
            <?= $form->field($addNewsForm, 'id')->hiddenInput()->label(false)->error(false) ?>
            <?= $form->field($addNewsForm, 'lang')->hiddenInput()->label(false)->error(false) ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($addNewsForm, 'title')->textInput()->label(THelper::t('backoffice_news_edit_title')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($addNewsForm, 'body')->textarea()->label(THelper::t('backoffice_news_edit_body')) ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-center">
                <?= Html::submitButton(THelper::t('backoffice_news_edit_save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    $('#addnewsform-lang').val(language);
    $('#addnewsform-author').val(author);
    tinymce.init({
        selector:'textarea',
        paste_data_images: true,
        plugins : 'advlist autolink link image lists charmap print preview fullscreen'
    });
    $('.modal').on('hidden.bs.modal', function () {

    })
</script>