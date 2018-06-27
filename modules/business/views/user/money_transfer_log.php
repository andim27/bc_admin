<?php
    use app\components\THelper;
    use MongoDB\BSON\UTCDatetime;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('money_transfer_log_title') ?></h3>
</div>
<div class="row">
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-money-transfer-log table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?= THelper::t('money_transfer_log_id_from') ?>
                    </th>
                    <th>
                        <?= THelper::t('money_transfer_log_id_to') ?>
                    </th>
                    <th>
                        <?= THelper::t('money_transfer_balance_from') ?>
                    </th>
                    <th>
                        <?= THelper::t('money_transfer_baklance_to') ?>
                    </th>
                    <th>
                        <?= THelper::t('money_transfer_amount') ?>
                    </th>
                    <th>
                        <?= THelper::t('money_transfer_admin') ?>
                    </th>
                    <th>
                        <?= THelper::t('money_transfer_date') ?>
                    </th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($moneyTransfers as $moneyTransfer) {
                    $userFrom = $moneyTransfer->getUserFrom()->one();
                    $userTo = $moneyTransfer->getUserTo()->one(); ?>
                    <tr>
                        <td><?= $userFrom ? $userFrom->username : '' ?></td>
                        <td><?= $userTo ? $userTo->username : '' ?></td>
                        <td><?= $moneyTransfer->balanceFrom ?></td>
                        <td><?= $moneyTransfer->balanceTo ?></td>
                        <td><?= $moneyTransfer->amount ?></td>
                        <td><?= $moneyTransfer->getAdmin()->one()->username ?></td>
                        <td><?= $moneyTransfer->date->toDateTime()->format('d.m.Y') ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<script>
    $('.table-money-transfer-log').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 6, "desc" ]]
    });
</script>