<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
    $this->title = THelper::t('authorization_new_cell') ;
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
                    <?php $form = ActiveForm::begin(['action' => $action]); ?>
                    <div class="form-group">
                        <label class="control-label"><?=THelper::t('email')?></label>
                        <?= $form->field($model, 'login')->textInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?=THelper::t('password')?></label>
                        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control'])->label(false) ?>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton(THelper::t('join_login'),  ['class' => 'btn btn-success pull-right']); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>