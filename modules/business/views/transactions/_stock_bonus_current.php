<div class="row">
    <div class="col-md-12 m-b-20">
        <b>Распределенная сумма:</b> <?= $currentStockBonus[0]->totalSum ?>
    </div>
    <div class="col-md-12 m-b-20">
        <b>Коэффициент:</b> <?= $currentStockBonus[0]->k ?>
    </div>
    <div class="col-md-8 m-b-20">
        <input type="button" id="cancel-current-stock-bonus-<?= $currentStockBonus[0]->stockType ?>" class="form-control btn-danger" value="Отменить" />
    </div>
    <div class="col-md-8 m-b-20">
        <div class="panel panel-default">
            <div class="panel-heading">Претенденты</div>
            <div class="panel-body">
                <table id="table-<?= $currentStockBonus[0]->stockType ?>" class="table table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th>Логин</th>
                        <th>ФИО</th>
                        <th>Сумма долей</th>
                        <th>Сумма бонуса</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($currentStockBonus as $user) { ?>
                        <tr>
                            <td><?= $user->login ?></td>
                            <td><?= trim($user->firstName . ' ' . $user->secondName) ?></td>
                            <td><?= $user->stock ?></td>
                            <td><?= $user->amount ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $('#cancel-current-stock-bonus-<?= $currentStockBonus[0]->stockType ?>').click(function() {
        $('#stock-bonus-pay-info-<?= $currentStockBonus[0]->stockType ?>').html('');
        $('.progress-<?= $currentStockBonus[0]->stockType ?>').show();
        $.ajax({
            url: '/' + LANG + '/business/transactions/cancel-current-stock-bonus',
            method: 'post',
            data: {
                type: '<?= $currentStockBonus[0]->stockType ?>',
            },
            success: function () {
                <?php if ($currentStockBonus[0]->stockType == 'wellness') { ?>
                loadStockBonusPayInfoWellness();
                <?php }; ?>
                <?php if ($currentStockBonus[0]->stockType == 'vipvip') { ?>
                loadStockBonusPayInfoVipVip();
                <?php } ?>
            },
            error: function () {
                $('.progress-<?= $currentStockBonus[0]->stockType ?>').hide();
            }
        });
    });
    $('#table-<?= $currentStockBonus[0]->stockType ?>').dataTable({
        language: TRANSLATION,
        order: [[ 2, 'desc' ]]
    });
</script>