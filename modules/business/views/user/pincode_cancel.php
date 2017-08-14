<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('pincode_cancel_title') ?></h3>
</div>
<div class="row">
    <section class="panel panel-default" style="overflow: hidden; padding-bottom: 10px;">
        <?php $form = ActiveForm::begin(['id' => $model->formName(), 'action' => $action]); ?>
            <div class="col-md-6">
                <?= $form->field($model, 'pin')->label(THelper::t('pin')) ?>
            </div>

            <div class="col-md-12">
                <?= Html::submitButton(THelper::t('ok'), array('class' => 'btn btn-s-md btn-success')); ?>
            </div>
        <?php ActiveForm::end(); ?>

        <?php if (!empty($status)) { ?>
            <h3 class="col-md-12"><?= $status; ?></h3>
        <?php } ?>

    </section>
</div>

