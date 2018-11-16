<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use app\components\AlertWidget;
    use app\models\PaymentCard;
    $listCard = PaymentCard::getListCards();
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_withdrawal') ?></h3>
</div>

<div class="row" style="padding: 0 15px 0 15px;">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
</div>
<div class="row">
    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-file-o"></i>',['/business/transactions/withdrawal-excel'],['class'=>'btn btn-default btn-block','title'=>'Выгрузка в excel'])?>
    </div>
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
                    <th><?= THelper::t('withdrawal_admin') ?></th>
                    <th><?= THelper::t('date_reduce') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model as $k => $item) { ?>
                    <tr>
                        <td><?= isset($item->infoUser->username) ? $item->infoUser->username : '' ?></td>
                        <td>
                            <?= isset($item->infoUser->firstName) ? $item->infoUser->firstName : '' ?>
                            <?= isset($item->infoUser->secondName) ? $item->infoUser->secondName : '' ?>
                        </td>
                        <td><?= $item->amount ?></td>
                        <td><?= ($listCard ? $listCard[(!empty($item->card['type']) ? $item->card['type'] : '1')] : '') ?></td>
                        <td><?= (!empty($item->card['number']) ? $item->card['number'] : '') ?></td>
                        <td><?= $item->dateCreate->toDateTime()->format('Y-m-d H:i:s') ?></td>
                        <td><?= THelper::t($item->getStatus()) ?></td>
                        <td><?= isset($item->infoAdmin->username) ? $item->infoAdmin->username : '' ?></td>
                        <td><?= (!empty($item->dateConfirm) ? $item->dateConfirm->toDateTime()->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'))?></td>
                        <td>
                            <?= ($item->confirmed == 0 ? Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/transactions/update-withdrawal','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) : '') ?>
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
        order: [[ 5, 'desc' ], [8, 'desc']]
    });
</script>

