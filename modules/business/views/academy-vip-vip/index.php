<?php
    use app\components\THelper;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('academy_vip_vip_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li <?= $currentTab != 'info' ? 'class="active"' : '' ?>>
                <a href="#users" aria-controls="users" role="tab" data-toggle="tab"><?= THelper::t('academy_vip_vip_tab_users') ?></a>
            </li>
            <li <?= $currentTab == 'info' ? 'class="active"' : '' ?>>
                <a href="#info" aria-controls="info" role="tab" data-toggle="tab"><?= THelper::t('academy_vip_vip_tab_info') ?></a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane <?= $currentTab != 'info' ? 'active' : '' ?>" id="users">
                <?= $this->render('_tab_users', [
                    'academyVipVipUsers' => $academyVipVipUsers
                ]); ?>
            </div>
            <div role="tabpanel" class="tab-pane <?= $currentTab == 'info' ? 'active' : '' ?>" id="info">
                <?= $this->render('_tab_info', [
                    'language' => $language,
                    'academyVipVipInfo' => $academyVipVipInfo,
                    'selectedLanguage' => $selectedLanguage,
                    'translationList' => $translationList
                ]); ?>
            </div>
        </div>
    </div>
</div>