<?php
    use app\components\THelper;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= $title ?></h4>
        </div>
        <div class="modal-body">
            <?= $text ?>
            <a href="javascript:void(0);" class="btn btn-warning" data-dismiss="modal">
                <?= THelper::t('cancel') ?>
            </a>
        </div>
    </div>
</div>

