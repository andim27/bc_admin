<div class="row">
    <div class="col-md-12 m-b-20">
        <b>Общее кол-во долей:</b> <?= $payInfo->total ?>
    </div>
    <div class="col-md-12 m-b-20">
        <b>Максимальное кол-во долей:</b> <?= $payInfo->max ?>
    </div>
    <div class="col-md-8 m-b-20">
        <input type="number" min="0" id="stock-bonus-amount-<?= $payInfo->type ?>" class="form-control" placeholder="Cумма к распределению" value="<?= $payInfo->amount ?>" />
    </div>
    <div class="col-md-8 m-b-20">
        <input type="button" id="get-stock-bonus-<?= $payInfo->type ?>" class="form-control btn-success" value="Расчитать" />
    </div>
    <div class="col-md-8 m-b-20">
        <div class="panel panel-default">
            <div class="panel-heading">Претенденты</div>
            <div class="panel-body">
                <?php if ($payInfo->users) { ?>
                <table id="table-<?= $payInfo->type ?>" class="table table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th>Логин</th>
                        <th>ФИО</th>
                        <th>Сумма долей</th>
                        <th>Сумма бонуса</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($payInfo->users as $payInfoUser) { ?>
                        <tr>
                            <td><?= $payInfoUser->login ?></td>
                            <td><?= trim($payInfoUser->firstName . ' ' . $payInfoUser->secondName) ?></td>
                            <td><?= $payInfoUser->total ?></td>
                            <td><?= $payInfoUser->bonus ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                    Претендентов нет
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php if (!is_null($amount)) { ?>
    <div class="row">
        <div class="col-md-8 m-b-20">
            <input type="button" id="set-stock-bonus-<?= $payInfo->type ?>" class="form-control btn-success" value="Запустить в расчет" />
        </div>
    </div>
    <script>
        $('#set-stock-bonus-<?= $payInfo->type ?>').click(function() {
            var amount = $('#stock-bonus-amount-<?= $payInfo->type ?>').val();
            $('#stock-bonus-pay-info-<?= $payInfo->type ?>').html('');
            $('.progress-<?= $payInfo->type ?>').show();
            $.ajax({
                url: '/' + LANG + '/business/transactions/set-stock-bonus',
                method: 'post',
                data: {
                    type: '<?= $payInfo->type ?>',
                    amount: amount
                },
                success: function () {
                    <?php if ($payInfo->type == 'wellness') { ?>
                        loadStockBonusPayInfoWellness();
                    <?php }; ?>
                    <?php if ($payInfo->type == 'vipvip') { ?>
                        loadStockBonusPayInfoVipVip();
                    <?php } ?>
                },
                error: function () {
                    $('.progress-<?= $payInfo->type ?>').hide();
                }
            });
        });
    </script>
<?php } ?>
<script>
    $('#table-<?= $payInfo->type ?>').dataTable({
        language: TRANSLATION,
        order: [[ 2, 'desc' ]]
    });
</script>