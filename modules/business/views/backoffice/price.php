<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('backoffice_price_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3 m-b-md">
        <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
    </div>
</div>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($priceForm, 'lang')->hiddenInput()->label(false)->error(false) ?>
    <?= $form->field($priceForm, 'author')->hiddenInput()->label(false)->error(false) ?>
    <div class="col-md-12">
        <?= $form->field($priceForm, 'title')->textInput()->label(THelper::t('backoffice_price_title')) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($priceForm, 'body')->textarea()->label(THelper::t('backoffice_price_body')) ?>
    </div>
    <div class="col-md-12 text-center">
        <?= Html::submitButton(THelper::t('backoffice_price_save'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->registerJsFile('//cdn.tinymce.com/4/tinymce.min.js', ['position' => yii\web\View::POS_HEAD]); ?>
<script>
    $('#languages-list').change(function() {
        window.location.replace('/' + LANG + '/business/backoffice/price?l=' + $(this).val());
    });
    tinymce.init({
        selector:'textarea',
        paste_data_images: true,
        plugins : 'advlist autolink link image lists charmap print preview fullscreen'
    });
</script>