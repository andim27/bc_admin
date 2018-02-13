<?php
use app\components\THelper;
use yii\helpers\Html;
?>

<section class="panel">
    <div class="table-responsive" style="overflow-y:scroll;">
        <table class="table table-striped table-queue">
            <thead>
            <tr>
                <th><?= THelper::t('letter_title') ?></th>
                <th><?= THelper::t('language') ?></th>
                <th><?= THelper::t('sending_date_time') ?></th>
                <th><?= THelper::t('delivery_condition') ?></th>
                <th><?= THelper::t('view') ?></th>
                <th><?= THelper::t('status') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($queue as $k => $item) { ?>
                <tr>
                    <td><?=$item->title?></td>
                    <td><?=$item->language?></td>
                    <td><?=is_numeric($item->datetime) ? date("F j, Y, H:i", $item->datetime) : $item->datetime; ?></td>
                    <td><?=$item->event?></td>
                    <td><a href="/business/notification/queue-view?id=<?=$item->_id?>" class="view" data-toggle="ajaxModal">Просмотр</a></td>
                    <td>
                        <?php if ($item->status === 'not_sent') { ?>
                            Ожидание
                            <br>
                            <a href="/business/notification/queue-delete?id=<?=$item->_id?>&type=current-one" class="delete" data-toggle="ajaxModal">Удалить</a>
                            <br>
                            <a href="/business/notification/queue-delete?id=<?=$item->_id?>&type=current-all" class="delete" data-toggle="ajaxModal">Удалить все не отправленные этому пользователю</a>
                            <br>
                            <a href="/business/notification/queue-delete?id=<?=$item->_id?>&type=all" class="delete" data-toggle="ajaxModal">Удалить все подобные сообщения с очереди</a> &nbsp;
                        <?php } else { ?>
                            Отправлено &nbsp;
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
