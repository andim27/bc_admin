<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
?>

<section class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['action' => '/' . $language . '/business/wellness-club-members/add-video']); ?>
        <div class="row">
            <div class="col-md-3 m-b-md">
                <?= Html::dropDownList('language', $selectedLanguage, $translationList, ['id' => 'languages-list-video', 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-12 m-b-md">
                <?= Html::textInput('url', $videoUrl, ['class' => 'form-control', 'placeholder' => THelper::t('wellness_club_video_url_placeholder')]) ?>
            </div>
            <div class="col-md-12 text-center m-b-md">
                <?= Html::submitButton(THelper::t('wellness_club_video_save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <?php if ($videoUrl) { ?>
            <div class="row">
                <div class="col-md-12 text-center">
                    <video src="<?= $videoUrl ?>" width="320" height="240" controls>
                        Sorry, your browser doesn't support embedded videos,
                        but don't worry, you can <a href="<?= $videoUrl ?>">download it</a>
                        and watch it with your favorite video player!
                    </video>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<script>
    $('#languages-list-video').change(function() {
        window.location.replace('/' + LANG + '/business/wellness-club-members?l=' + $(this).val() + '&t=video');
    });
</script>