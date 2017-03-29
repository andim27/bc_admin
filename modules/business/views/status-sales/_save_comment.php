<?php
use app\models\Users;
use app\components\THelper;
?>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <?= THelper::t('you_comment_send') ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <?php if(!empty($arrayRev)){ ?>
        <?php foreach($arrayRev as $item){ ?>
            <div class="media text-left">
                <div class="media-body">
                    <h6 class="media-heading">
                        <?= Users::getUserEmail($item['idUser']['$id'])  ?>

                        <span class="label label-default">
                            <?=Yii::$app->formatter->asDate($item['dateCreate']['sec'],'php:Y-m-d H:i:s')?>
                        </span>
                    </h6>
                    <?=$item['review']?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

