<?php
    use app\components\THelper;
?>
<section class="panel">
    <div class="table-responsive" style="overflow-y:scroll;">
        <table class="table table-striped table-queue">
            <thead>
            <tr>
                <th><?= THelper::t('letter_title') ?></th>
                <th><?= THelper::t('language') ?></th>
                <th><?= THelper::t('notification_send_to') ?></th>
                <th><?= THelper::t('sending_date_time') ?></th>
                <th><?= THelper::t('view') ?></th>
                <th><?= THelper::t('status') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($queueForUsers as $queue) { ?>
                <tr>
                    <td><?= $queue->title ?><?= $queue->count && $queue->count > 1 ? ' (' . $queue->count . ')' : '' ?></td>
                    <td><?= $queue->language ?></td>
                    <td><?= $queue->username ?></td>
                    <td><?= $queue->date->toDateTime()->format('d.m.Y H:i:s'); ?></td>
                    <td><a href="/<?= Yii::$app->language ?>/business/notification/queue-view?id=<?= $queue->_id ?>" class="view" data-toggle="ajaxModal">Просмотр</a></td>
                    <td>
                        <?php if ($queue->status == 0) { ?>
                            Ожидает отправки
                            <br>
                            <a href="/<?= Yii::$app->language ?>/business/notification/queue-delete?id=<?= $queue->_id ?>&type=current-one" class="delete" data-toggle="ajaxModal">Удалить</a>
                            <br>
                            <a href="/<?= Yii::$app->language ?>/business/notification/queue-delete?id=<?= $queue->_id ?>&type=current-all" class="delete" data-toggle="ajaxModal">Удалить все не отправленные <?= $queue->username ?></a>
                            <br>
                            <a href="/<?= Yii::$app->language ?>/business/notification/queue-delete?id=<?= $queue->_id ?>&type=all" class="delete" data-toggle="ajaxModal">Удалить все подобные сообщения с очереди</a> &nbsp;
                        <?php } else if ($queue->status == 1) { ?>
                            Отправлено
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<script>
    $('.table-queue').dataTable({
        language: 'ru',
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
</script>