<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['id' => 'push-delete', 'action' => $action]); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= $title ?></h4>
        </div>
        <div class="modal-body">
            <?php $form = ActiveForm::begin(
                ['action' => $notificationUrl . '/variable-add'],
                ['options' => ['enctype' => 'multipart/form-data']]
            );?>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($variableModel, 'name')->textInput()->label(THelper::t('variable_name')) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($variableModel, 'value')->textInput()->label(THelper::t('variable_value')) ?>
                </div>
            </div>

            <div class="text-center">
                <?= Html::submitButton(THelper::t('add'), ['class' => 'btn btn-success']) ;?>

                <a href="javascript:void(0);" class="btn btn-warning" data-dismiss="modal">
                    <?= THelper::t('cancel') ?>
                </a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
</div>

