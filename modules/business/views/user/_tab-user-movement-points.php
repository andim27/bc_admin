<?php
    use app\components\THelper;
    /** @var \app\models\Transaction $item */
?>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th width="15%"><?=THelper::t('date')?></th>
                <th width="20%"><?=THelper::t('from')?></th>
                <th width="20%"><?=THelper::t('to')?></th>
                <th width="15%"><?=THelper::t('amount')?></th>
                <th width="15%"><?=THelper::t('finance_operations_saldo_from')?></th>
                <th width="15%"><?=THelper::t('for_what')?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($modelMovementPoints)){
                foreach ($modelMovementPoints as $item){ ?>
                    <tr>
                        <td><?= $item->dateCreate->toDateTime()->format('Y-m-d') ?></td>
                        <td><?= $item->idFrom ?></td>
                        <td><?= $item->idTo ?></td>
                        <td><?= $item->amount ?></td>
                        <td><?= $item->saldoFrom ?></td>
                        <td><?= $item->forWhat ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>