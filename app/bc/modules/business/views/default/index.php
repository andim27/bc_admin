<?php
    use app\components\THelper;
    use app\components\UrlHelper;
    use yii\helpers\Html;
?>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="clearfix text-center m-t">
                        <div class="inline">
                            <div style="width: 160px; height: 130px; line-height: 130px;" class="easypiechart easyPieChart" data-percent="75" data-line-width="5" data-bar-color="#4cc0c1" data-track-color="#f5f5f5" data-scale-color="false" data-size="130" data-line-cap="butt" data-animate="1000">
                                <div class="thumb-lg">
                                    <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png'; ?>" class="img-circle" width="128" height="128">
                                </div>
                                <canvas width="130" height="130"></canvas>
                            </div>
                        </div>
                        <div class="h4 m-t m-b-xs"><?= $user->firstName?> <?= $user->secondName ?></div>
                        <small class="text-muted m-b"><?=THelper::t('status')?>: <?= THelper::t('rank_'.$user->rank) ?></small>
                    </div>
                </div>
                <footer class="panel-footer bg-info text-center">
                    <div class="row pull-out">
                        <div class="col-xs-4">
                            <div class="padder-v">
                                <?php if ($user->firstPurchase > 0){
                                    $firstPurchase = date_diff(date_create(date('d-m-Y H:i:s', $user->firstPurchase)), date_create())->days;
                                } else {
                                    $firstPurchase = 0;
                                } ?>
                                <span class="m-b-xs h3 block text-white"><?= $firstPurchase ?></span>
                                <small class="text-muted"><?=THelper::t('days_in_the_business')?><!--Дней в бизнесе--></small>
                            </div>
                        </div>
                        <div class="col-xs-4 dk">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= $user->rightSideNumberUsers + $user->leftSideNumberUsers ?></span>
                                <small class="text-muted"><?=THelper::t('registrations_in_the_structure')?><!--Регистраций в структуре--></small>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= $user->statistics->partnersWithPurchases ?></span>
                                <small class="text-muted"><?=THelper::t('default_index_partners')?></small>
                            </div>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="panel panel-default">
                <div class="text-center wrapper bg-light lt">
                    <div class=" inline" style="height: 165px; width: 165px;">
                        <?php if($user->avatar) :?>
                              <img src="/images/ranks/rank_<?=$user->rank?>.png"  style="height: 165px; width: 165px;">
                        <?php else :?>
                              <img src="/images/ranks/rank_<?=$user->rank?>.png"  style="height: 165px; width: 165px;">
                        <?php endif ?>
                    </div>
                </div>
                <ul class="list-group no-radius">
                    <li class="list-group-item">
                        <span class="label bg-info">1</span> <?=THelper::t('status')?>: <?= THelper::t('rank_'.$user->rank) ?>
                    </li>
                    <li class="list-group-item">
                        <span class="label bg-info">2</span> <?=THelper::t('login')?>: <?= $user->username ?>
                    </li>
                    <?php if ($user->created) { ?>
                        <li class="list-group-item">
                            <span class="label bg-info">3</span> <?=THelper::t('registration_date')?>: <?= gmdate('d.m.Y', $user->created) ?>
                        </li>
                    <?php } ?>
                </ul>
            </section>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('conditions_of_participation_in_business')?></span>
                </header>
                <div style="position: relative; overflow: hidden; width: auto; height: 100px;" class="slimScrollDiv">
                    <section style="overflow: hidden; width: auto; height: 100px;" class="panel-body slim-scroll">
                        <article class="media">
                            <div class="media-body">
                                <?= THelper::t('business_support') . ': '; ?>
                                <?= ($user->expirationDateBS && $user->expirationDateBS > 0) ? (THelper::t('expiration_date_bs') . ' ' . gmdate('d.m.Y', $user->expirationDateBS)) : '-'; ?><br />
                                <?php if (! $user->autoExtensionBS) { ?>
                                    <?= THelper::t('automatic_extension_of_business_support')?>: <span class="text-color-red"><?= THelper::t('disable') ?></span><br/>
                                <?php } else { ?>
                                    <?= THelper::t('automatic_extension_of_business_support')?>: <span class="text-color-green"><?= THelper::t('enable') ?></span><br/>
                                <?php } ?>
                                <?php if (! $user->personalBonus) { ?>
                                    <?= THelper::t('personal_award_in_the_personal_account')?>: <span class="text-color-red"><?= THelper::t('not_charge') ?></span><br/>
                                <?php } else { ?>
                                    <?= THelper::t('personal_award_in_the_personal_account')?>: <span class="text-color-green"><?= THelper::t('charge') ?></span><br/>
                                <?php } ?>
                                <?php if ($user->statistics->pack) { ?>
                                    <?= Thelper::t('pack_type'); ?>: <span><?= THelper::t('pack_type_' . $user->statistics->pack) ?></span>
                                <?php } ?>
                            </div>
                        </article>
                    </section>
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('structural_award')?></span>
                </header>
                <div style="position: relative; overflow: hidden; width: auto; height: 100px;" class="slimScrollDiv">
                    <section style="overflow: hidden; width: auto; height: 100px;" class="panel-body slim-scroll">
                        <article class="media">
                            <div class="media-body">
                                <?php if (! $user->qualification) { ?>
                                    <?= THelper::t('personal_skills') ?>: <span class="text-color-red"><?= THelper::t('not_done') ?></span><br />
                                <?php } else { ?>
                                    <?= THelper::t('personal_skills') ?>: <span class="text-color-green"><?= THelper::t('done') ?></span><br />
                                <?php } ?>
                                <?php if (! $user->structBonus) { ?>
                                    <?= THelper::t('structural_award')?>: <span class="text-color-red"><?= THelper::t('not_charge') ?></span>
                                <?php } else { ?>
                                    <?= THelper::t('structural_award')?>: <span class="text-color-green"><?= THelper::t('charge') ?></span>
                                <?php } ?>
                            </div>
                        </article>
                    </section>
                </div>
            </section>
        </div>
    </div>
</div>
<?php /** if (isset($user->statistics->tokens) && $user->statistics->tokens) { ?>
    <div class="col-lg-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <span class="h4"><?= THelper::t('default_ny_tokens') ?></span>
            </header>
            <section class="panel-body text-center">
                <?php for ($i = 0; $i < $user->statistics->tokens; $i++) { ?>
                    <img src="/images/token.png" />
                <?php } ?>
            </section>
        </section>
    </div>
<?php } **/ ?>
<?php if ($promoShow) { ?>
<div class="col-lg-12">
    <section class="panel panel-default">
        <header class="panel-heading">
            <span class="h4"><?= THelper::t('promo_title') ?></span>
        </header>
        <div class="slimScrollDiv">
            <section class="panel-body slim-scroll">
                <article class="media">
                    <div class="media-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="row">
                                    <div class="col-lg-12 text-center">
                                        <div class="thumb-lg">
                                            <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png'; ?>" class="img-circle" width="128" height="128">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-lg-12 m-b">
                                        <div class="row">
                                            <div class="col-lg-3 text-center text-danger" style="overflow: hidden; padding-top: 20px;">
                                                <span style="font-size: 16px; font-weight: bold;"><?= THelper::t('promo_current_steps') ?>:</span>
                                                <div class="clearfix"></div>
                                                <span id="promo-sri-lanka-your-price" style="font-size: 40px;"><?= $promoYourPriceOne ?></span>
                                                <div class="clearfix"></div>
                                                <span style="font-size: 16px; font-weight: bold;"><?= THelper::t('promo_current_points') ?>:</span>
                                                <div class="clearfix"></div>
                                                <span id="promo-sri-lanka-your-price" style="font-size: 40px;"><?= $promoNeedSalesSum ?></span>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-lg-12 text-center">
                                                        <a href="https://www.youtube.com/watch?v=8BzM7YTga_s" target="_blank"><img src="/images/sri_lanka_player.png" class="img-responsive2"></a>
                                                    </div>
                                                    <div class="col-lg-12 text-center text-danger m-b" style="font-size: 18px; font-weight: bold;">
                                                        <?= THelper::t('promo_sri_lanka_go_by_company') ?>: <?= $qtyCompleteProm ?> / 25
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 text-center text-danger" style="overflow: hidden; padding-top: 20px;">
                                                <span style="font-size: 16px; font-weight: bold;"><?= THelper::t('promo_need_steps') ?>:</span>
                                                <div class="clearfix"></div>
                                                <span id="promo-sri-lanka-travel-price" style="font-size: 40px;"><?= $promoPriceOne ?></span>
                                                <div class="clearfix"></div>
                                                <span style="font-size: 16px; font-weight: bold;"><?= THelper::t('promo_need_points') ?>:</span>
                                                <div class="clearfix"></div>
                                                <span id="promo-sri-lanka-travel-price" style="font-size: 40px;">2500</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <div class="progress progress-striped active" style="height: 10px;">
                                            <div class="progress-bar progress-bar-success" role="progressbar" id="promo-sri-lanka-progressbar" data-progress1="<?= $promoProgressOne ?>" data-progress2="<?= $promoProgressTwo ?>" aria-valuenow="<?= $promoProgressOne ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $promoProgressOne ?>%">
                                                <span class="sr-only"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 text-center">
                                <img src="/images/sri_lanka_img2.png" class="img-responsive2">
                            </div>
                        </div>
                    </div>
                </article>
            </section>
        </div>
    </section>
</div>
<?php } ?>
<!--<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?/*=THelper::t('schedule_of_structure')*/?></header>
                <div class="panel-body">
                    <div id="flot-chart" style="text-align: center;">
                        <i class="fa fa-5x fa-spinner fa-spin"></i>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>-->
<div class="col-lg-12">
    <div class="row">
        <div class="col-lg-6">
            <section class="panel panel-info">
                <div class="panel-body">
                    <div  class="thumb pull-right m-l">
                        <?php
                        if($parent->rank == 0){
                            $rank1 = THelper::t('undefined');
                        } else {
                            $rank1 = THelper::t('finish');
                        }
                        ?>
                        <?php if($parent->avatar) :?>
                            <img src="<?= $parent->avatar ?>">
                        <?php else :?>
                            <img src="/images/avatar_default.png"/>
                        <?php endif; ?>
                    </div>
                    <div class="clear">
                        <?php if ($parent->firstName && $parent->secondName) { ?>
                            <?= THelper::t('your_mentor') ?>: <span style="color: #4cc3d2"><?= $parent->firstName ?> <?= $parent->secondName ?></span><br>
                        <?php } ?>
                        <?php if ($parent->username) { ?>
                            <?= THelper::t('login') ?>: <span style="color: #4cc3d2"><?= $parent->username ?></span><br>
                        <?php } ?>
                        <?php if ($parent->phoneNumber) { ?>
                            <?= THelper::t('phone') ?>: <span style="color: #4cc3d2"><?= $parent->phoneNumber ?></span><br>
                        <?php } ?>
                        <?php if ($parent->email) { ?>
                            <?= THelper::t('email') ?>: <span style="color: #4cc3d2"><?= $parent->email ?></span><br>
                        <?php } ?>
                        <?php if ($parent->skype) { ?>
                            <?= THelper::t('skype') ?>: <span style="color: #4cc3d2"><?= $parent->skype ?></span><br>
                        <?php } ?>
                        <?=THelper::t('status') ?>: <span style="color: #4cc3d2"><?= THelper::t('rank_' . $parent->rank) ?></span><br>
                        <?php if ($parent->links->site) { ?>
                            <?= THelper::t('website_blog') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->site) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                        <?php if ($parent->links->odnoklassniki) { ?>
                            <?= THelper::t('page_odnoklassniki') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->odnoklassniki) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                        <?php if ($parent->links->vk) { ?>
                            <?= THelper::t('page_vkontakte') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->vk) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                        <?php if ($parent->links->fb) { ?>
                            <?= THelper::t('page_facebook') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->fb) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                        <?php if ($parent->links->youtube) { ?>
                            <?= THelper::t('youtube_channel') ?>: <span style="color: #4cc3d2"><a href="<?= UrlHelper::getValidUrl($parent->links->youtube) ?>" target="_blank" style="color: #0000CC;"><?= THelper::t('open') ?></a></span><br>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('your_affiliate_links')?><!--Ваши партнерские ссылки--></span>
                </header>
                <div class="panel-body">
                    <p><?=THelper::t('link_to_register')?>: <span id="toclip"><?= $linkToRegister ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip" id="copy-link-wrap"><?=THelper::t('copy')?></a></p>
                    <?php if ($links->site) { ?>
                        <p><?=THelper::t('main_site')?>: <span id="toclip3"><?= $links->site . '/' . (Yii::$app->language === 'en' ? 'en' : '') . '?ref=' . $user->username ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip3" id="copy-link-wrap3"><?=THelper::t('copy')?></a></p>
                    <?php } ?>
                    <?php if ($links->market) { ?>
                        <p><?=THelper::t('link_to_shop')?>: <span id="toclip2"><?= $links->market . (Yii::$app->language === 'en' ? '?lang=en' : '') ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip2" id="copy-link-wrap2"><?=THelper::t('copy')?></a></p>
                    <?php } ?>

                    <p>
                        <?=THelper::t('landing_for_business')?>:
                        <span id="toclip4"><?= $linkBusinessLanding[0] ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip4" id="copy-link-wrap4"><?=THelper::t('copy')?></a>
                        <span id="toclip5"><?= $linkBusinessLanding[1] ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip5" id="copy-link-wrap5"><?=THelper::t('copy')?></a>
                    </p>
                    <p><?=THelper::t('landing_for_vipvip')?>: <span id="toclip6"><?= $linkVipVipLanding ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip6" id="copy-link-wrap6"><?=THelper::t('copy')?></a></p>

                    <p><?=THelper::t('link_site_for_vipvip_app')?>: <span id="toclip7"><?= $linkSiteAppVipVip ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip7" id="copy-link-wrap7"><?=THelper::t('copy')?></a></p>

                    <p><?=THelper::t('landing_for_webwellness')?>: <span id="toclip8"><?= $linkWebWellnessLanding ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip8" id="copy-link-wrap8"><?=THelper::t('copy')?></a></p>

                    <p><?=THelper::t('link_site_for_webwellness')?>: <span id="toclip9"><?= $linkSiteAppWebWellness ?></span> <a href="javascript:void(0);" data-clipboard-target="#toclip9" id="copy-link-wrap9"><?=THelper::t('copy')?></a></p>
                </div>
            </section>
        </div>
    </div>
</div>
<script>
    var months = {
        "01":"<?=tHelper::t('january')?>",
        "02":"<?=tHelper::t('february')?>",
        "03":"<?=tHelper::t('march')?>",
        "04":"<?=tHelper::t('april')?>",
        "05":"<?=tHelper::t('may')?>",
        "06":"<?=tHelper::t('june')?>",
        "07":"<?=tHelper::t('july')?>",
        "08":"<?=tHelper::t('august')?>",
        "09":"<?=tHelper::t('september')?>",
        "10":"<?=tHelper::t('october')?>",
        "11":"<?=tHelper::t('november')?>",
        "12":"<?=tHelper::t('december')?>"
    }

    var labelPaid          = "<?= tHelper::t('paid') ?>";
    var labelRegistrations = "<?= tHelper::t('registrations') ?>";
    var linkCopiedToClipboardText = "<?= tHelper::t('link_copied_to_clipboard') ?>";

    jQuery(document).ready(function() {
        (function(){
            if ($('#copy-link-wrap')) {
                new Clipboard('#copy-link-wrap');
            }

            for (var i = 2; i <= 9; i+=1) {
                if ($('#copy-link-wrap' + i)) {
                    new Clipboard('#copy-link-wrap' + i);
                }
            }
        })();
    });

    $('#promo-sri-lanka-select').change(function() {
        var value = $(this).val();

        var promoSriLankaYourPrice = $('#promo-sri-lanka-your-price');
        var yourPrice = promoSriLankaYourPrice.data('price' + value);
        promoSriLankaYourPrice.html(yourPrice);

        var promoSriLankaTravelPrice = $('#promo-sri-lanka-travel-price');
        var price = promoSriLankaTravelPrice.data('price' + value);
        promoSriLankaTravelPrice.html(price);

        var promoSriLankaProgressbar = $('#promo-sri-lanka-progressbar');
        var progress = promoSriLankaProgressbar.data('progress' + value);
        promoSriLankaProgressbar.attr('aria-valuenow', progress);
        promoSriLankaProgressbar.css('width', progress + '%');
    });
</script>

<?php $this->registerJsFile('js/main/flot_graph.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('/js/clipboard.min.js'); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>