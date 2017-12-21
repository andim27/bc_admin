<?php
    use yii\widgets\ActiveForm;
    use app\assets\AppAsset;
    use yii\helpers\Html;
    use app\components\THelper;
    use app\components\LangswitchWidget;
    AppAsset::register($this);
?>
<div class="main">
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <div class="container aside-xxl">
        <a class="block logo_authorization" href="/"><img alt="" src= "<?= $logo ? $logo : '/images/logo_auth.png' ?>" width="" height="" /></a>

        <section class="panel panel-default">
            <div class="panel-heading clearfix text-center ofv">
                <strong><?=THelper::t('sign_in')?></strong>
                <div class="pull-right">
                    <?= LangswitchWidget::widget() ?>
                </div>
            </div>
            <div>
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'options' => ['class' => 'panel-body wrapper-lg form_auth'],
                    'fieldConfig' => [
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    ],
                ]); ?>
                <div class="form-group">
                    <?= Html::label(THelper::t('email_or_login'), 'email', ['class' => 'control-label']) ?>
                    <?= $form->field($model, 'email')->textInput(['class' => 'form-control input-lg'])->label(false) ?>
                </div>
                <div class="form-group">
                    <?= Html::label(THelper::t('password'), 'password', ['class' => 'control-label paslab']) ?>
                    <div><?= $form->field($model, 'password')->passwordInput(['class' => 'form-control input-lg', 'placeholder' => THelper::t('password')])->label(false) ?></div>
                </div>

                <div class="form-inline">
                    <?= $form->field($model, 'rememberMe')->checkbox()->label(false) ?>
                    <?= Html::submitButton(THelper::t('sign_in'), ['class' => 'btn btn-danger pull-right btn-block', 'name' => 'login-button']) ?>
                </div>

                <div class="line line-dashed line_after"></div>
                <p class="text-muted text-center"><?= Html::a(THelper::t('recovery_password_text'), ['/login/reset'], ['data-toggle'=>'ajaxModal']) ?></p>
                <p class="text-muted text-center"><small><?= THelper::t('do_not_have_an_account') ?></small></p>
                <?= Html::a(THelper::t('create_an_account'), ['/reg/registration'], ['class' => 'btn btn-default btn-block']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
</section>
<!-- footer -->
<footer id="footer">
    <div class="text-center padder">
        <p>
            <small><?= strtoupper(THelper::t('company_name')) ?><br>&copy; <?= $year ?></small>
        </p>
    </div>
</footer>
</div>

<?php $this->registerJsFile('js/bg.js', ['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('js/fuelux/fuelux.css',['depends'=>['app\assets\AppAsset']]); ?>
