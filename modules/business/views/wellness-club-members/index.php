<?php
    use app\components\THelper;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('wellness_club_members_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li <?= $currentTab == 'members' ? 'class="active"' : '' ?>>
                <a href="#members" aria-controls="members" role="tab" data-toggle="tab"><?= THelper::t('wellness_club_members_tab_members') ?></a>
            </li>
            <li <?= $currentTab == 'info' ? 'class="active"' : '' ?>>
                <a href="#info" aria-controls="info" role="tab" data-toggle="tab"><?= THelper::t('wellness_club_members_tab_info') ?></a>
            </li>
            <li <?= $currentTab == 'conferences' ? 'class="active"' : '' ?>>
                <a href="#conferences" aria-controls="conferences" role="tab" data-toggle="tab"><?= THelper::t('wellness_club_members_tab_conference') ?></a>
            </li>
            <li <?= $currentTab == 'video' ? 'class="active"' : '' ?>>
                <a href="#video" aria-controls="video" role="tab" data-toggle="tab"><?= THelper::t('wellness_club_members_tab_video') ?></a>
            </li>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane <?= $currentTab == 'members' ? 'active' : '' ?>" id="members">
                <?= $this->render('_tab_members'); ?>
            </div>
            <div role="tabpanel" class="tab-pane <?= $currentTab == 'info' ? 'active' : '' ?>" id="info">
                <?= $this->render('_tab_info', [
                    'language' => $language,
                    'body' => $body,
                    'selectedLanguage' => $selectedLanguage,
                    'translationList' => $translationList
                ]); ?>
            </div>
            <div role="tabpanel" class="tab-pane <?= $currentTab == 'conferences' ? 'active' : '' ?>" id="conferences" style="height: 500px">
                <?= $this->render('_tab_conferences'); ?>
            </div>
            <div role="tabpanel" class="tab-pane <?= $currentTab == 'video' ? 'active' : '' ?>" id="video">
                <?= $this->render('_tab_video', [
                    'language' => $language,
                    'videoUrl' => $videoUrl,
                    'selectedLanguage' => $selectedLanguage,
                    'translationList' => $translationList
                ]); ?>
            </div>
        </div>
    </div>
</div>