<?php
    use app\components\THelper;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>

<div class="modal-dialog">
    <div class="modal-content">

        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'action' => '/' . $language . '/business/user/write-off']); ?>

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('write-off_add_title') ?></h4>
        </div>

        <div class="modal-body">
            <div class="raw">
                <div class="col-md-12">
                    <?= $form->field($model, 'amount')->textInput(['class' => 'form-control'])->label(THelper::t('amount')) ?>
                </div>

                <div class="col-md-12">
                    <?= $form->field($model, 'comment')->textarea(['class' => 'form-control'])->label(THelper::t('comment')) ?>
                </div>

                <?= $form->field($model, 'userId')->hiddenInput()->label(false)->error(false) ?>

                <div class="text-center">
                    <?= Html::submitButton(THelper::t('write-off_add_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>

        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>