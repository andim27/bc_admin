<style>
    .modal-backdrop{
        z-index: 1039;
    }
</style>
<?php
    use yii\helpers\Html;
    use app\components\THelper;
    use yii\widgets\ActiveForm;
    $this->title = THelper::t('profile');
    $this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
<div class="row">
    <div class="col-md-7 col-sm-6 col-lg-9 col-xs-12 m-b">
        <div>
            <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png' ; ?>" class="img-circle">
        </div>
        <div class="controls_edit">
            <?= Html::a(THelper::t('change'), ['change-img'], array('class' => 'btn btn-s-md btn-success', 'data-toggle' => 'ajaxModal')); ?>
            <label for=""><?= THelper::t('200_200_pixels_in_the_formats') ?></label>
        </div>
    </div>
    <?php /*
    <div class="col-md-5 col-sm-6 col-lg-3 col-xs-12 m-b">
        <div style="overflow: hidden; height: 125px;" class="hidden-xs"></div>
        <div>
            <div class="form-group pull-right">
                <label class="control-label switch-center"><?= THelper::t('show_my_phone') ?></label>
                <label class="switch">
                    <input value="1" type="checkbox" name="showMobile" <?php if($user->settings->showMobile):?>checked<?php endif; ?>/>
                    <span></span>
                </label>
            </div>
            <div class="form-group pull-right">
                <label class="control-label switch-center"><?= THelper::t('show_my_email') ?></label>
                <label class="switch">
                    <input value="1" type="checkbox" name="showEmail" <?php if($user->settings->showEmail):?>checked<?php endif; ?>/>
                    <span></span>
                </label>
            </div>
            <div class="form-group pull-right">
                <label  class="control-label switch-center"><?= THelper::t('show_my_name_to_the_structure') ?></label>
                <label class="switch">
                    <input value="1" type="checkbox" name="showName" <?php if($user->settings->showName):?>checked<?php endif; ?>/>
                    <span></span>
                </label>
            </div>
        </div>
    </div>
    */ ?>
</div>
<section class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-name"><?= THelper::t('name') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->name = $user->firstName ?>
                    <?php $model->id = $user->id ?>
                    <?= $form->field($model, 'name')->textInput(['class' => 'form-control name_usr_prof',  'name' => 'name'])->label(false) ?>
                    <?= $form->field($model, 'id')->hiddenInput(['name' => 'id'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-surname"><?= THelper::t('surname') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->surname = $user->secondName ?>
                    <?= $form->field($model, 'surname')->textInput(['class' => 'form-control surname_usr_prof', 'name' => 'surname'])->label(false) ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-birthday"><?= THelper::t('birthday') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->birthday = isset($user->birthday) ? gmdate('m/d/Y', $user->birthday) : '';?>
                    <?= $form->field($model, 'birthday')->textInput(['class' => 'form-control datepicker-input birthday_usr_prof', 'name' => 'birthday'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-login"><?= THelper::t('login') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->login = $user->username ?>
                    <?= $form->field($model, 'login')->textInput(['class' => 'form-control log_usr_prof', 'name' => 'login'])->label(false) ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-email"><?= THelper::t('email') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->email = $user->email ?>
                    <?= $form->field($model, 'email')->textInput(['class' => 'form-control email_usr_prof', 'name' => 'email'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-skype"><?= THelper::t('skype') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->skype = $user->skype ?>
                    <?= $form->field($model, 'skype')->textInput(['class' => 'form-control skype_usr_prof', 'name' => 'skype'])->label(false) ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-mobile"><?= THelper::t('mobile') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->mobile = $user->phoneNumber ?>
                    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 16, 'class' => 'form-control mobile_usr_prof phone', 'name' => 'mobile'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-smobile"><?= THelper::t('another_mobile') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->smobile = $user->phoneNumber2 ?>
                    <?= $form->field($model, 'smobile')->textInput(['maxlength' => 16, 'class' => 'form-control smobile_usr_prof phone', 'name' => 'smobile'])->label(false)->hint(THelper::t('setting_profile_phone_vipvip_warning'), ['class' => 'hint-warning']) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-mobile"><?= THelper::t('profile_phone_wellness') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->phoneWellness = $user->phoneWellness ?>
                    <?= $form->field($model, 'phoneWellness')->textInput(['maxlength' => 16, 'class' => 'form-control phone', 'name' => 'phone_wellness'])->label(false)->hint(THelper::t('setting_profile_phone_wellness_warning'), ['class' => 'hint-warning']) ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-site"><?= THelper::t('website_blog'); ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->site = $user->links->site ?>
                    <?= $form->field($model, 'site')->textInput(['class' => 'form-control site_usr_prof', 'name' => 'site'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-odnoklassniki"><?= THelper::t('page_odnoklassniki'); ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->odnoklassniki = $user->links->odnoklassniki ?>
                    <?= $form->field($model, 'odnoklassniki')->textInput(['class' => 'form-control odnoklassniki_usr_prof', 'name' => 'odnoklassniki'])->label(false) ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-vk"><?= THelper::t('page_vkontakte'); ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->vk = $user->links->vk ?>
                    <?= $form->field($model, 'vk')->textInput(['class' => 'form-control vk_usr_prof', 'name' => 'vk'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-fb"><?= THelper::t('page_facebook'); ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->fb = $user->links->fb ?>
                    <?= $form->field($model, 'fb')->textInput(['class' => 'form-control fb_usr_prof', 'name' => 'fb'])->label(false) ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-youtube"><?= THelper::t('youtube_channel'); ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->youtube = $user->links->youtube ?>
                    <?= $form->field($model, 'youtube')->textInput(['class' => 'form-control youtube_usr_prof', 'name' => 'youtube'])->label(false) ?>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <div class="row">
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="select-country"><?= THelper::t('country') ?></label>
                </div>
                <div class="col-xs-12">
                    <select id="select-country" class="form-control block country_prof" name="country">
                        <?php foreach ($countries as $country) { ?>
                            <option value="<?= $country->alpha2 ?>" <?= strtoupper($user->countryCode) == $country->alpha2 ? 'selected="selected"' : ''?>><?= $country->name ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-mobile"><?= THelper::t('state') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->state = $user->state; ?>
                    <?= $form->field($model, 'state')->textInput(['class' => 'form-control', 'name' => 'state'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-mobile"><?= THelper::t('city') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->city = $user->city; ?>
                    <?= $form->field($model, 'city')->textInput(['class' => 'form-control', 'name' => 'city'])->label(false) ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-xs-12">
                    <label class="control-label" for="user-mobile"><?= THelper::t('address') ?></label>
                </div>
                <div class="col-xs-12">
                    <?php $model->address = $user->address; ?>
                    <?= $form->field($model, 'address')->textInput(['class' => 'form-control user-address', 'name' => 'address'])->label(false) ?>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div style="margin-top:25px">
            <section class="bg-white">
                <section id="gmap_geocoding" style="min-height:350px;"></section>
                <input id="latitude" type="hidden"/><br/>
                <input id="longitude" type="hidden"/>
            </section>
        </div>
    </div>
</section>
<div class="for_btn_center">
    <?= Html::submitButton(THelper::t('save'), array('class' => 'btn btn-s-md btn-success')); ?>
</div>
<input type="hidden" id="usr_city" value="">
<input type="hidden" id="usr_avat" value="<?= Yii::$app->session->get('avatar') ?>">

<?php $form = ActiveForm::end(); ?>

<script>
    $('.phone').keyup(function() {
        if ($(this).val()[0] != '+' && $(this).val().length > 0) {
            $(this).val('+' + $(this).val());
        }
    });
</script>

<?php $this->registerJsFile('//maps.google.com/maps/api/js?key=AIzaSyD7tLRljBzY8xLAggpXY-YaHY0EiGBpe0U'); ?>
<?php $this->registerJsFile('/js/maps/gmaps.js'); ?>
<?php $this->registerJsFile('/js/main/business_profile.js'); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>
