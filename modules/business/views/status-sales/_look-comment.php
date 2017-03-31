<?php
use app\components\THelper;
use \app\models\Users;

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('view_comments') ?></h4>
        </div>


        <div class="modal-body">
            <?php if(!empty($arrayRev)){ ?>
                <?php foreach($arrayRev as $item){ ?>
                    <div class="media text-left">
                        <div class="media-body">
                            <h6 class="media-heading">
                                <?= Users::getUserEmail($item['idUser']['$id'])  ?>

                                <span class="label label-default">
                                    <?=$item->dateCreate->toDateTime()->format('Y-m-d H:i:s')?>
                                </span>
                            </h6>
                            <?=$item['review']?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        По данному заказу комментарии отсутствуют.
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>