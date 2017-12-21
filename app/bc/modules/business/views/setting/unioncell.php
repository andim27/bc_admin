<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
$this->title = THelper::t('merge_cells');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($successText) { ?>
    <div class="alert alert-success">
        <?= $successText ?>
    </div>
<?php } else if ($errorText) { ?>
    <div class="alert alert-danger">
        <?= $errorText ?>
    </div>
<?php } ?>

<div class="row" style="margin-bottom:25px">
    <div class="col-xs-12">
        <?php $form = ActiveForm::begin(); ?>
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

<?php if ($linkedAccounts) {
    foreach ($linkedAccounts as $linkedAccount) { ?>
        <div class="row">
            <div class="col-xs-12">
                <section class="panel panel-info">
                    <div class="panel-body">
                        <div class="thumb pull-right m-l">
                            <?php if (isset($linkedAccount->userData) && !empty($linkedAccount->userData->avatar)) {
                                echo '<img src="' . $linkedAccount->userData->avatar . '" class="img-circle">';
                            } else {
                                echo '<img src="/images/avatar_default.png" class="img-circle">';
                            }?>
                        </div>
                        <div class="clear">
                            <span class="text-info"><?= THelper::t('login_is_join') ?></span>
                            <span class="block text-muted"><?= THelper::t('login') . ': ' . $linkedAccount->username ?></span>
                            <?php if (isset($linkedAccount->userData->username)) { ?>
                                <?= Html::a(THelper::t('unjoin'), ['disconnect', 'id' => $linkedAccount->userData->username], ['data-confirm' => THelper::t('are_you_sure'), 'class' => 'btn btn-xs btn-success m-t-xs']); ?>
                            <?php } ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    <?php } ?>
<?php } ?>