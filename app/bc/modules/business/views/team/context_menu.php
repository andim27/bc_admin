<?php
    use app\components\THelper;
?>
<div id="contextMenu" class="dropdown clearfix">
    <div class="preloader"></div>
    <ul>
        <li data-name="name"><?= THelper::t('name'); ?>: <span></span></li>
        <li data-name="surname"><?= THelper::t('surname'); ?>: <span></span></li>
        <li data-name="birthday"><?= THelper::t('birthday'); ?>: <span></span></li>
        <li data-name="username"><?= THelper::t('login'); ?>: <span></span></li>
        <li data-name="email"><?= THelper::t('email'); ?>: <span></span></a></li>
        <li data-name="skype"><?= THelper::t('skype'); ?>: <span></span></li>
        <li data-name="country"><?= THelper::t('country'); ?>: <span></span></li>
        <li data-name="state"><?= THelper::t('state'); ?>: <span></span></li>
        <li data-name="city"><?= THelper::t('city'); ?>: <span></span></li>
        <li data-name="address"><?= THelper::t('address'); ?>: <span></span></li>
        <li data-name="zipCode"><?= THelper::t('zip'); ?>: <span></span></li>
        <li data-name="phoneNumber"><?= THelper::t('mobile'); ?>: <span></span></li>
        <li data-name="phoneNumber2"><?= THelper::t('another_mobile'); ?>: <span></span></li>
        <li data-name="phoneWellness"><?= THelper::t('context_menu_phone_wellness'); ?>: <span></span></li>
        <li data-name="expirationDateBS"><?= THelper::t('bs'); ?>: <span></span></li>
        <li data-name="status"><?= THelper::t('status'); ?>: <span></span></li>
        <li data-name="sponsor"><?= THelper::t('recommender'); ?>: <span></span></li>
        <li data-name="linkSite"><?= THelper::t('website_blog'); ?>: <span></span></li>
        <li data-name="linkOdnoklassniki"><?= THelper::t('page_odnoklassniki'); ?>: <span></span></li>
        <li data-name="linkVk"><?= THelper::t('page_vkontakte'); ?>: <span></span></li>
        <li data-name="linkFb"><?= THelper::t('page_facebook'); ?>: <span></span></li>
        <li data-name="linkYoutube"><?= THelper::t('youtube_channel'); ?>: <span></span></li>
    </ul>
</div>
<?php $this->registerJsFile('js/preloader/jquery-waiting.js', ['depends' => ['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/context-menu.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/date.js',['depends'=>['app\assets\AppAsset']]); ?>