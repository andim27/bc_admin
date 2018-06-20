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
            <?php if ($videos) { ?>
                <?php foreach ($videos as $video) { ?>
                    <div class="col-md-12 m-b-md">
                        <?= Html::textInput('url[]', $video->url, ['class' => 'form-control', 'placeholder' => THelper::t('wellness_club_video_url_placeholder')]) ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-md-12 m-b-md">
                    <?= Html::textInput('url[]', '', ['class' => 'form-control', 'placeholder' => THelper::t('wellness_club_video_url_placeholder')]) ?>
                </div>
            <?php } ?>
            <div class="col-md-12 text-right m-b-md">
                <?= Html::button('+', ['class' => 'btn btn-success add-video']) ?>
            </div>
            <div class="col-md-12 text-center m-b-md">
                <?= Html::submitButton(THelper::t('wellness_club_video_save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</section>
<script>
    $('#languages-list-video').change(function() {
        window.location.replace('/' + LANG + '/business/wellness-club-members?l=' + $(this).val() + '&t=video');
    });
    $('.add-video').click(function () {
        var thisButton = $(this);

        thisButton.parent().before('<div class="col-md-12 m-b-md"><?= Html::textInput('url[]', '', ['class' => 'form-control', 'placeholder' => THelper::t('wellness_club_video_url_placeholder')]) ?></div>');

        console.log(thisButton);
    });
</script>