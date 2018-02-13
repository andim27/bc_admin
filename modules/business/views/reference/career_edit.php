<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(
            ['action' => '/' . $language . '/business/reference/career-edit'],
            ['options' => ['enctype' => 'multipart/form-data']]
        );
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('career_add_title') ?></h4>
        </div>
        <div class="modal-body">
            <?= $form->field($careerAddForm, 'lang')->hiddenInput()->label(false)->error(false) ?>
            <?= $form->field($careerAddForm, 'id')->hiddenInput()->label(false)->error(false) ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'serialNumber'/*, ['enableAjaxValidation' => true]*/)->textInput()->label(THelper::t('career_add_serial_number')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'statusName')->textInput()->label(THelper::t('career_add_status_name')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'shortName')->textInput()->label(THelper::t('career_add_short_name')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'steps')->textInput()->label(THelper::t('career_add_steps')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'timeForAward')->textInput()->label(THelper::t('career_add_time_for_award')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'bonus')->textInput()->label(THelper::t('career_add_bonus')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'selfInvitedStatusInOneBranch')->radioList([
                        1 => THelper::t('yes'),
                        0 => THelper::t('no')
                    ])->label('self_invited_status_in_one_branch'); ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'selfInvitedStatusInAnotherBranch')->radioList([
                        1 => THelper::t('yes'),
                        0 => THelper::t('no')
                    ])->label('self_invited_status_in_another_branch'); ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'selfInvitedStatusInSpillover')->textInput()->label(THelper::t('career_add_self_invited_status_in_spillover')) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($careerAddForm, 'selfInvitedNumberInSpillover')->textInput()->label(THelper::t('career_add_self_invited_number_in_spillover')) ?>
                </div>
                <div class="col-md-12">
                    <label class="control-label"><?=THelper::t('add_status_avatar')?></label>
                    <?php
                    // With model & without ActiveForm
                    echo FileInput::widget([
                        'model' => $careerAddForm,
                        'attribute' => 'statusAvatar',
                        'options' => ['multiple' => true],
                        'pluginOptions' => [
                            'previewFileType' => 'image',
                            'showUpload' => false,
                            'initialPreview' => [
                                '<img src="' . $careerAddForm->statusAvatar . '" class="file-preview-image">',
                            ],
                            'initialCaption' => "avatar",
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-12">
                    <label class="control-label"><?=THelper::t('add_status_certificate')?></label>
                    <?php
                    // With model & without ActiveForm
                    echo FileInput::widget([
                        'model' => $careerAddForm,
                        'attribute' => 'statusCertificate',
                        'options' => ['multiple' => true],
                        'pluginOptions' => [
                            'previewFileType' => 'image',
                            'showUpload' => false,
                            'initialPreview' => [
                                '<img src="' . $careerAddForm->statusCertificate . '" class="file-preview-image">',
                            ],
                            'initialCaption' => "certificate",
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="text-center">
                <?= Html::submitButton(THelper::t('career_add_save'), ['class' => 'btn btn-success']) ;?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    $('#careeraddform-lang').val('<?=$language?>');

    $('.modal').on('hidden.bs.modal', function () {

    });

    $('#w0')
        .submit( function( e ) {
            var $this = $(this);

            $.ajax( {
                url: $this.attr('action'),
                type: 'POST',
                data: new FormData( this ),
                processData: false,
                contentType: false
            } );
            e.preventDefault();
        } );
</script>