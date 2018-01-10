<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
    $this->title = THelper::t('recovery_password') ;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="modal-body">
            <div class="row" style="margin-bottom:25px">
                <div class="col-xs-12">
                    <?php $form = ActiveForm::begin(['id' => 'recovery-form']); ?>
                    <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('email')])->label(false) ?>
                    <div class="row">
                        <div class="text-center">
                            <?= Html::submitButton(THelper::t('recover'), ['class' => 'btn btn-default btn-sm']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <div id="recovery-success" class="alert alert-success" style="display: none;">
                        <?= THelper::t('password_has_been_recovered') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJsFile('/js/main/recovery_password.js', ['depends' => ['yii\web\JqueryAsset']]); ?>