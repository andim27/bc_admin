<?php
    use app\components\THelper;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('notifications'); ?></h3>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#planing-notifications" aria-controls="planing-notifications" role="tab" data-toggle="tab">Запланированная рассылка</a>
            </li>
            <li role="presentation">
                <a href="#templates" aria-controls="templates" role="tab" data-toggle="tab">Шаблоны писем</a>
            </li>
            <li role="presentation">
                <a href="#queue" aria-controls="queue" role="tab" data-toggle="tab">Очередь сообщений</a>
            </li>
        </ul>
        <div class="tab-content" style="padding:15px">
            <div role="tabpanel" class="tab-pane active" id="planing-notifications">
                <?= $this->render('partials/planing', [
                    'pushAddForm' => $pushAddForm,
                    'languages' => $languages,
                    'pushes' => $pushes,
                    'notificationUrl' => $notificationUrl,
                ]); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="templates">
                <?= $this->render('partials/templates', [
                    'pushTemplateAddForm' => $pushTemplateAddForm,
                    'languages' => $languages,
                    'pushTemplates' => $pushTemplates,
                    'notificationUrl' => $notificationUrl,
                    'deliveryConditions' => $deliveryConditions
                ]); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="queue">
                <?= $this->render('partials/queue', [
                    'queueForUsers' => $queueForUsers
                ]); ?>
            </div>
        </div>
    </div>
</div>