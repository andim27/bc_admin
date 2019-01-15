<?php
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= $notifications[0]->title?><?= $notifications[0]->count && $notifications[0]->count > 1 ? ' (' . $notifications[0]->count . ')' : '' ?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group">
                <?php foreach ($notifications as $key => $notification) { ?>
                    <li class="list-group-item">
                        <?= $notification->body ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0);" class="btn btn-warning" data-dismiss="modal">
                <?= THelper::t('cancel') ?>
            </a>
        </div>
    </div>
</div>

