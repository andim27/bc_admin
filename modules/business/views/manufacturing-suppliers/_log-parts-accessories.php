<?php
use app\components\THelper;

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('history_of_operations') ?></h4>
        </div>


        <div class="modal-body">
            <?php if(!empty($infoLog)){ ?>
                <?php krsort($infoLog); ?>
                <div class="blockRev">
                    <?php foreach($infoLog as $item){ ?>
                        <div class="media text-left">
                            <div class="media-body">
                                <h6 class="media-heading">
                                    <span class="label label-default">
                                        <?=$item['dateCreate']->toDateTime()->format('Y-m-d h:m:s')?>
                                    </span>
                                </h6>
                                <?=$item['log']?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>                
        </div>
    </div>
</div>