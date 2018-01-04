<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>

<a href="#" id="notifications" class="dropdown-toggle dk" data-toggle="dropdown">
    <i class="fa fa-bell"></i>
    <?php if ($notifications) { ?>
        <span style="display: inline-block;" class="badge badge-sm up bg-danger m-l-n-sm non_seen_notifications"></span>
    <?php } ?>
</a>
<section class="dropdown-menu aside-xl">
    <section class="panel bg-white">
        <header class="panel-heading b-<?= $q ?> bg-<?= $q ?>">
            <strong><span class="non_seen_notifications"><?=THelper::t('no')?></span> <?=THelper::t('unread_s_notificatio_s')?></strong>
        </header>
        <div class="list-group list-group-alt animated fadeInRight">
            <?php foreach($notifications as $notification) { ?>
                <?= Html::a( ' <span class="media-body block m-b-none">' . $notification->title . '<br>
                <small class="text-muted">' . gmdate('d-m-Y, H:i', $notification->dateCreate) . '</small>
                </span>', [$notification->getUrl(false)], ['id' => 'notification-from-menu-' . $notification->id, 'class' => 'media list-group-item', 'onclick' => 'location.href = "' . $notification->getUrl($currLangName, true) . '"']) ?>
            <?php } ?>
        </div>
    </section>
</section>