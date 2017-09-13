<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;

/** @var \app\models\Transaction $item */

use app\models\PaymentCard;
$listCard = PaymentCard::getListCards();

?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_withdrawal') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th><?= THelper::t('from_whom') ?></th>
                    <th><?= THelper::t('full_name') ?></th>
                    <th><?= THelper::t('amount') ?></th>
                    <th><?= THelper::t('card_type') ?></th>
                    <th><?= THelper::t('card_number') ?></th>
                    <th><?= THelper::t('date_create') ?></th>
                    <th><?= THelper::t('status') ?></th>
                    <th><?= THelper::t('date_reduce') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model as $k=>$item) { ?>
                    <tr>
                        <td><?=(!empty($item->infoUser->username) ? $item->infoUser->username : '');?></td>
                        <td>
                            <?=(!empty($item->infoUser->firstName) ? $item->infoUser->firstName : '')?>
                            <?=(!empty($item->infoUser->secondName) ? $item->infoUser->secondName : '')?>
                        </td>
                        <td><?=$item->amount?></td>
                        <td><?=$listCard[(!empty($item->card['type']) ? $item->card['type'] : '1')]?>..</td>
                        <td><?=(!empty($item->card['number']) ? $item->card['number'] : '')?></td>
                        <td><?=$item->dateCreate->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=THelper::t($item->getStatus())?></td>
                        <td><?=(!empty($item->dateReduce) ? $item->dateReduce->toDateTime()->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'))?></td>
                        <td>
                            <?= ($item->confirmed == 0 ?
                                Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/transactions/update-withdrawal','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) :
                                '') ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>

<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 5, "desc" ]]
    });
</script>

