<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
?>

<section class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['action' => '/' . $language . '/business/wellness-club-members/add-info']); ?>
        <div class="row">
            <div class="col-md-3 m-b-md">
                <?= Html::dropDownList('language', $selectedLanguage, $translationList, ['id' => 'languages-list-info', 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-12 m-b-md">
                <?= Html::textarea('body', $body) ?>
            </div>
            <div class="col-md-12 text-center">
                <?= Html::submitButton(THelper::t('wellness_club_info_save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>
<?php $this->registerJsFile('//cdn.tinymce.com/4/tinymce.min.js', ['position' => yii\web\View::POS_HEAD]); ?>
<script>
    $('#languages-list-info').change(function() {
        window.location.replace('/' + LANG + '/business/wellness-club-members?l=' + $(this).val() + '&t=info');
    });
    tinymce.init({
        selector: 'textarea',
        paste_data_images: true,
        plugins : 'advlist autolink link image lists charmap print preview fullscreen'
    });
</script>