<!-- Modal -->
<?php
    use app\components\THelper;
    use app\components\UrlHelper;
?>
<div class="modal fade" id="greetings" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= THelper::t('greetings_title') ?></h4>
            </div>
            <div class="modal-body">
                <p>
                    <!-- Здравствуйте, приветствуем Вас в бизнес-центре компании Business Process Technologies.
                    По всем вопросам Вы можете обращаться к Вашему консультанту. Его координаты: -->
                    <?=tHelper::t('greetings_description')?>
                </p>
                <div  class="thumb pull-right m-l">
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
<!--            <div class="modal-footer">-->
<!--                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
<!--            </div>-->
        </div>

    </div>
</div>