<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('showrooms_emails'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3 m-b-md">
        <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#client" aria-controls="client" role="tab" data-toggle="tab"><?= THelper::t('showrooms_emails_tab_client') ?></a>
            </li>
            <li>
                <a href="#showroom" aria-controls="showroom" role="tab" data-toggle="tab"><?= THelper::t('showrooms_emails_tab_showroom') ?></a>
            </li>
        </ul>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="client">
                <section class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($emailsForm, 'clientTitle')->textInput()->label(THelper::t('showrooms_emails_title')) ?>
                            </div>
                            <div class="col-md-12">
                                <?= $form->field($emailsForm, 'clientBody')->textarea()->label(THelper::t('showrooms_emails_body')) ?>
                            </div>
                            <div class="col-md-12 text-center">
                                <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div role="tabpanel" class="tab-pane" id="showroom">
                <section class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($emailsForm, 'showroomTitle')->textInput()->label(THelper::t('showrooms_emails_title')) ?>
                            </div>
                            <div class="col-md-12">
                                <?= $form->field($emailsForm, 'showroomBody')->textarea()->label(THelper::t('showrooms_emails_body')) ?>
                            </div>
                            <div class="col-md-12 text-center">
                                <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->registerJsFile('//cdn.tinymce.com/4/tinymce.min.js', ['position' => yii\web\View::POS_HEAD]); ?>
<script>
    $('#languages-list').change(function() {
        window.location.replace('/' + LANG + '/business/showrooms/emails?l=' + $(this).val());
    });
    tinymce.init({
        selector:'textarea',
        paste_data_images: true,
        plugins : 'advlist autolink link image lists charmap print preview fullscreen'
    });
</script>

