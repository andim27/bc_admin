<?php

use yii\widgets\ActiveForm;
use app\assets\AppAsset;
use yii\helpers\Html;
use app\components\THelper;
use app\components\LangswitchWidget;

AppAsset::register($this);
?>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp sect">
    <?= Html::a('<img alt ="logo registration" src= "/images/logo_reg.png" width = "" height = "" />', ['/login/login'], ['class' => 'block logo_logo', 'alt' => 'logotip registratsii']) ?>
    <section class="padder">
        <div class="m-b-md">
            <h3 class="m-b-none color-alice-blue"><?=THelper::t('register_company')?> <?= THelper::t('company_name') ?></h3>
        </div>
        <div class="panel panel-default">
            <div class="wizard clearfix" id="form-wizard">
                <ul class="steps">
                    <li data-target="#step1" class="active step s1"><span class="badge badge-info sp1">1</span><?=THelper::t('step')?> 1</li>
                    <li data-target="#step2" class="step s2"><span class="badge sp2">2</span><?=THelper::t('step')?> 2</li>
                </ul>
                <div class="pull-right">
                    <?= LangswitchWidget::widget() ?>
                </div>
            </div>
                <div class="step-content">
                    <?php $form = ActiveForm::begin(); ?>
                    <input type="hidden" value="<?=$ref?>" name="RegistrationForm[ref]">
                    <div class="step-pane active" id="step1">
                        <?= $form->field($model, 'name')->textInput(['placeholder' => THelper::t('name')])->label(false)->hint(THelper::t('writing_in_cyrillic_or_latin_alphabet')) ?>
                        <?= $form->field($model, 'second_name')->textInput(['placeholder' => THelper::t('surname')])->label(false)->hint(THelper::t('writing_in_cyrillic_or_latin_alphabet')) ?>
                        <?= $form->field($model, 'email')->textInput(['placeholder' => THelper::t('email')])->label(false) ?>
                        <div class="errmail"><?=THelper::t('this_email_is_already_in_use')?></div>
                        <?= $form->field($model, 'login')->textInput(['placeholder' => THelper::t('login')])->label(false)->hint(THelper::t('latin_letters_numbers_and_dashes_allowed')) ?>
                        <div class="errname"><?=THelper::t('this_login_is_already_taken')?></div>
                        <?= $form->field($model, 'mobile')->textInput(['placeholder' => THelper::t('mobile_phone')])->label(false)->hint(THelper::t('only_numbers')) ?>
                        <?= $form->field($model, 'skype')->textInput(['placeholder' => THelper::t('skype')])->label(false)->hint(THelper::t('this_is_not_a_required_field')) ?>
                        <?= $form->field($model, 'country_id')->dropDownList($countries, ['prompt' => THelper::t('select_country')])->label(false); ?>
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <?= $form->field($model, 'pass')->passwordInput(['placeholder' =>  THelper::t('security_password')])->label(false)->hint(THelper::t('not_less_than_6_characters')) ?>
                            </div>
                            <div class="form-group col-xs-6">
                                <?= $form->field($model, 'password_repeat')->passwordInput(['placeholder' => THelper::t('repeat_the_password_entry')])->label(false) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <?= $form->field($model, 'finance_pass')->passwordInput(['placeholder' => THelper::t('password_on_financial_transactions')])->label(false)->hint(THelper::t('not_less_than_6_characters')) ?>
                            </div>
                            <div class="form-group col-xs-6">
                                <?= $form->field($model, 'password_repeat_finance')->passwordInput(['placeholder' => THelper::t('repeat_password_on_financial_transactions')])->label(false) ?>
                            </div>
                        </div>
                        <div class="checkbox cl6">
                            <?= $form->field($model, 'rememberMe')->checkbox()->label(THelper::t('i_agree_with').' '.Html::a(THelper::t('conditions'), ['reg/participant'], ['data-toggle'=>'ajaxModal', 'class' => 'more_usl']), ['class' => 'check_me'])?>
                        </div>
                        <div class="actions m-t t-a-r">
                            <?= Html::Button(THelper::t('next'), ['class' => 'btn btn-default btn-sm btn-next next1', 'disabled' => 'disabled']) ?>
                        </div>
                    </div>
                    <div class="step-pane" id="step2">
                        <p class="p1"><?=THelper::t('congratulations_registration_is_completed')?>
                            <br>
                        <p><?=THelper::t('to_enter_the_back_office_using_the_following_data')?>:
                            <br><br>
                        <p><?=THelper::t('login')?>: <span class="lo"></span></p>
                        <p><?=THelper::t('password')?>: <i class="i1"><?=THelper::t('you specified at registration')?></i>
                            <br><br>
                        <p><?=THelper::t('now_you_can_buy_the_products_text')?>
                        <p><?=THelper::t('manage_news_subscriptions')?> <a href="<?= $url ?>" class="more_usl"><?= $url ?></a>
                        <br/>
                        <br/>
                        <div class="btn-group btn-group-justified m-b">
                            <?= Html::a(THelper::t('private_office'), Yii::$app->getHomeUrl(), ['class' => 'btn btn-primary', 'target' => '_blank']); ?>
                            <?php if ($links->market) { echo Html::a(THelper::t('shop'), $links->market, ['class' => 'btn btn-info', 'target' => '_blank']); } ?>
                            <?php if ($links->site) { echo Html::a(THelper::t('company_site'), $links->site, ['class' => 'btn btn-primary', 'target' => '_blank']); } ?>
                        </div>
                        <br/>
                        <br/>
                        <?php if ($links->video) { ?>
                            <div class="text-center">
                                <?= $links->video; ?>
                            </div>
                            <br/>
                            <br/>
                        <?php } ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
</section>
<br>
<br>

<?php $this->registerJsFile('js/fuelux/fuelux.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/bg.js', ['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/parsley/parsley.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/regref.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('js/fuelux/fuelux.css',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
