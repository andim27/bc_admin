<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('backoffice_regbutton_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3 m-b-md">
        <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
    </div>
</div>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">
        <?= $form->field($regbuttonForm, 'lang')->hiddenInput()->label(false)->error(false) ?>
        <?= $form->field($regbuttonForm, 'shop')->textInput()->label(THelper::t('backoffice_regbutton_shop')) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($regbuttonForm, 'site')->textInput()->label(THelper::t('backoffice_regbutton_site')) ?>
    </div>
    <div class="col-md-12">
        <?= Html::submitButton(THelper::t('backoffice_regbutton_save'), array('class' => 'btn btn-s-md btn-success')); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $('#languages-list').change(function() {
        window.location.replace('/' + LANG + '/business/backoffice/regbutton?l=' + $(this).val());
    });
</script>