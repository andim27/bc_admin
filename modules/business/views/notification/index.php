<?php use app\components\SidebarWidget;
use app\components\THelper;
    use yii\helpers\Html; ?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('notifications'); ?></h3>
</div>

<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs " role="tablist">
        <li role="presentation" class="active">
            <a href="#planing-notifications" aria-controls="planing-notifications" role="tab" data-toggle="tab">Запланированная рассылка</a>
        </li>
        <li role="presentation">
            <a href="#variables" aria-controls="variables" role="tab" data-toggle="tab">Переменные</a>
        </li>
        <li role="presentation">
            <a href="#templates" aria-controls="templates" role="tab" data-toggle="tab">Шаблоны писем</a>
        </li>
        <li role="presentation">
            <a href="#queue" aria-controls="queue" role="tab" data-toggle="tab">Очередь сообщений</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content" style="padding: 15px">
        <section class="hbox stretch">
            <?= SidebarWidget::widget() ?>
            <section id="content">
                <section class="vbox">
                    <section class="scrollable padder">
                        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) { ?>
                            <div class="m-t-md alert alert-<?= $key ?>">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?= is_array($message) ? $message[0] : $message ?>
                            </div>
                        <?php } ?>
                    </section>
                </section>
            </section>
        </section>

        <div role="tabpanel" class="tab-pane active" id="planing-notifications"><?= $this->render('partials/planing', [
                'pushAddForm' => $pushAddForm,
                'languages' => $languages,
                'pushes' => $pushes,
                'notificationUrl' => $notificationUrl,
            ]); ?></div>
        <div role="tabpanel" class="tab-pane" id="variables"><?= $this->render('partials/variables', [
                'variables' => $variables,
                'notificationUrl' => $notificationUrl,
            ]); ?></div>
        <div role="tabpanel" class="tab-pane" id="templates"><?= $this->render('partials/templates', [
                'pushTemplateAddForm' => $pushTemplateAddForm,
                'languages' => $languages,
                'deliveryConditions' => $deliveryConditions,
                'pushTemplates' => $pushTemplates,
                'notificationUrl' => $notificationUrl,
            ]); ?></div>
        <div role="tabpanel" class="tab-pane" id="queue"><?= $this->render('partials/queue', [
                'queue' => $queue
            ]); ?></div>
    </div>

</div>

<?php $this->registerJsFile('//cdn.tinymce.com/4/tinymce.min.js', ['position' => yii\web\View::POS_HEAD]); ?>



