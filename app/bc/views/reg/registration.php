<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use app\components\THelper;
    use app\components\LangswitchWidget;
?>
<div class="main">
    <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
        <div class="container aside-xxl">
            <?= Html::a( '<img alt ="logo registration" src= "/images/logo_reg.png" width = "" height = "" />', ['/login/login'], ['class' => 'block logo_logo', 'alt' => 'logotip registratsii']) ?>
            <div class="m-b-md">
                <h3 class="m-b-none color-alice-blue"><?=THelper::t('register_company')?> <?= THelper::t('company_name') ?></h3>
            </div>
            <div class="panel panel-default">
                <div class="wizard clearfix" id="form-wizard">
                    <ul class="steps">
                        <li data-target="#step1" class="<?= $model->step == 1 ? 'active' : ''; ?> step s1"><span class="badge <?= $model->step == 1 ? 'badge-info' : ''; ?> sp1">1</span><?=THelper::t('step')?> 1</li>
                        <li data-target="#step2" class="<?= $model->step == 2 ? 'active' : ''; ?> step s2"><span class="badge <?= $model->step == 2 ? 'badge-info' : ''; ?> sp2">2</span><?=THelper::t('step')?> 2</li>
                        <li data-target="#step3" class="<?= $model->step == 3 ? 'active' : ''; ?> step s3"><span class="badge <?= $model->step == 3 ? 'badge-info' : ''; ?> sp3">3</span><?=THelper::t('step')?> 3</li>
                    </ul>
                    <div class="pull-right">
                        <?= LangswitchWidget::widget() ?>
                    </div>
                </div>
                <div class="step-content">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'step')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'referrer')->hiddenInput()->label(false) ?>
                    <?php if ($model->step == 1) { ?>
                        <div class="step-pane active" id="step1">
                            <p><?=THelper::t('login_recommender_or_membership_number')?> <?= THelper::t('company_name') ?></p>
                            <?= $form->field($model, 'ref')->textInput()->label(false) ?>
                            <p><?=THelper::t('referrer_is')?></p>
                            <p><?=THelper::t('you_do_not_have_a_referee').' '.Html::a(THelper::t('more_information_about_referees'), $recommenderSearchUrl, ['target' => '_blank', 'class' => 'more_usl']); ?></p>
                            <div class="actions m-t t-a-r">
                                <?= Html::a(THelper::t('back'), ['/login/login'],  ['class' => 'btn btn-default btn-sm btn-next']) ?>
                                <?= Html::submitButton(THelper::t('next'), ['class' => 'btn btn-default btn-sm btn-next next1']) ?>
                            </div>
                        </div>
                    <?php } else if ($model->step == 2) { ?>
                        <div class="step-pane active" id="step2">
                            <?= $form->field($model, 'name', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('name')])->label(false)->hint(THelper::t('writing_in_cyrillic_or_latin_alphabet')) ?>
                            <?= $form->field($model, 'second_name', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('surname')])->label(false)->hint(THelper::t('writing_in_cyrillic_or_latin_alphabet')) ?>
                            <?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('email')])->label(false) ?>
                            <?= $form->field($model, 'login', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('login')])->label(false)->hint(THelper::t('latin_letters_numbers_and_dashes_allowed')) ?>
                            <?= $form->field($model, 'mobile', ['enableAjaxValidation' => true])->textInput(['placeholder' => THelper::t('mobile_phone')])->label(false)->hint(THelper::t('only_numbers')) ?>
                            <?= $form->field($model, 'skype', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('skype')])->label(false)->hint(THelper::t('this_is_not_a_required_field')) ?>
                            <?= $form->field($model, 'messenger', ['enableAjaxValidation' => false])->dropDownList([
                                'whatsapp' => THelper::t('whatsapp'),
                                'viber'    => THelper::t('viber'),
                                'telegram' => THelper::t('telegram'),
                                'facebook' => THelper::t('facebook'),
                            ], ['id' => 'messenger', 'prompt' => THelper::t('select_messenger')])->label(false)->hint(THelper::t('messenger_info')) ?>
                            <div id="messenger-number-block" style="display:none;">
                                <?= $form->field($model, 'messengerNumber', ['enableAjaxValidation' => false])->textInput(['placeholder' => THelper::t('messenger_number')])->label(false)->hint(THelper::t('messenger_numbe_info')) ?>
                            </div>
                            <?= $form->field($model, 'country_id', ['enableAjaxValidation' => false])->dropDownList($countries, ['prompt' => THelper::t('select_country')])->label(false) ?>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <?= $form->field($model, 'pass', ['enableAjaxValidation' => false])->passwordInput(['placeholder' =>  THelper::t('security_password')])->label(false)->hint(THelper::t('not_less_than_6_characters')) ?>
                                </div>
                                <div class="form-group col-xs-6">
                                    <?= $form->field($model, 'password_repeat', ['enableAjaxValidation' => false])->passwordInput(['placeholder' => THelper::t('repeat_the_password_entry')])->label(false) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <?= $form->field($model, 'finance_pass', ['enableAjaxValidation' => false])->passwordInput(['placeholder' => THelper::t('password_on_financial_transactions')])->label(false)->hint(THelper::t('not_less_than_6_characters')) ?>
                                </div>
                                <div class="form-group col-xs-6">
                                    <?= $form->field($model, 'password_repeat_finance', ['enableAjaxValidation' => false])->passwordInput(['placeholder' => THelper::t('repeat_password_on_financial_transactions')])->label(false) ?>
                                </div>
                            </div>
                            <div class="checkbox cl6">
                                <?= $form->field($model, 'rememberMe', ['enableAjaxValidation' => false])->checkbox()->label(THelper::t('i_agree_with').' '.Html::a(THelper::t('conditions'), ['reg/participant'], ['data-toggle'=>'ajaxModal', 'class' => 'more_usl']), ['class' => 'check_me'])?>
                            </div>
                            <div class="actions m-t">
                                <?= Html::a(THelper::t('back'), ['/reg/registration'],  ['class' => 'btn btn-default btn-sm']) ?>
                                <?= Html::submitButton(THelper::t('next'), ['class' => 'btn btn-default btn-sm btn-next next2', 'disabled' => 'disabled']) ?>
                            </div>
                        </div>
                    <?php } else if ($model->step == 3) { ?>
                        <div class="step-pane active" id="step3">
                            <p class="p1"><?=THelper::t('congratulations_registration_is_completed')?>
                                <br>
                            <p><?=THelper::t('to_enter_the_back_office_using_the_following_data')?>:
                                <br><br>
                            <p><?=THelper::t('email')?>: <span class="lo"><?= $model->email ?></span></p>
                            <p><?=THelper::t('password')?>: <i class="i1"><?= THelper::t('you_specified_at_registration') ?></i>
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
                                <div class="embed-container">
                                    <?= $links->video; ?>
                                </div>
                                <br/>
                                <br/>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->registerJsFile('/js/fuelux/fuelux.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('/js/bg.js', ['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('/js/main/registration.js?a=' . md5(time())); ?>
<?php $this->registerCssFile('/js/fuelux/fuelux.css',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('/css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
