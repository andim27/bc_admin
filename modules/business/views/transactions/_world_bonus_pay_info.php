<?php
use app\components\THelper;
?>
<div class="row">
    <?php if (is_null($amount)) { ?>
        <div class="col-md-12 m-b-20">
            <b>Общий товарооборот:</b> <?= $payInfo->amount ?>
        </div>
        <div class="col-md-12 m-b-20">
            <b>Статус:</b> <?= $payInfo->status ? 'начислено' : 'не начислено' ?>
        </div>
    <?php } ?>
    <div class="col-md-8 m-b-20">
        <table class="table datagrid m-b-sm">
            <thead>
            <tr>
                <th></th>
                <th>%</th>
                <th>Сумма</th>
                <th>Претендуют партнеров</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= THelper::t('rank_15') ?></td>
                    <td>2.5%</td>
                    <td><?= $payInfo->ranks->rank15->amount ?></td>
                    <td><?= $payInfo->ranks->rank15->count ?></td>
                </tr>
                <tr>
                    <td><?= THelper::t('rank_14') ?></td>
                    <td>2%</td>
                    <td><?= $payInfo->ranks->rank14->amount ?></td>
                    <td><?= $payInfo->ranks->rank14->count ?></td>
                </tr>
                <tr>
                    <td><?= THelper::t('rank_13') ?></td>
                    <td>1%</td>
                    <td><?= $payInfo->ranks->rank13->amount ?></td>
                    <td><?= $payInfo->ranks->rank13->count ?></td>
                </tr>
                <tr>
                    <td><?= THelper::t('rank_12') ?></td>
                    <td>1%</td>
                    <td><?= $payInfo->ranks->rank12->amount ?></td>
                    <td><?= $payInfo->ranks->rank12->count ?></td>
                </tr>
                <tr>
                    <td><?= THelper::t('rank_11') ?></td>
                    <td>0.5%</td>
                    <td><?= $payInfo->ranks->rank11->amount ?></td>
                    <td><?= $payInfo->ranks->rank11->count ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8 m-b-20">
        <div class="panel panel-default">
            <div class="panel-heading">Претенденты</div>
            <div class="panel-body">
                <table class="table table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th>Логин</th>
                        <th>ФИО</th>
                        <th>Статус</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($payInfo->users as $payInfoUser) { ?>
                        <tr>
                            <td><?= $payInfoUser->login ?></td>
                            <td><?= trim($payInfoUser->firstName . ' ' . $payInfoUser->secondName) ?></td>
                            <td><?= THelper::t('rank_' . $payInfoUser->careerRank) ?></td>
                            <td><?= ceil($payInfo->ranks->{'rank' . $payInfoUser->careerRank}->amount / $payInfo->ranks->{'rank' . $payInfoUser->careerRank}->count) ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
if (!$payInfo->status) {
    if (is_null($amount)) { ?>
        <?php if ($payInfo->current) { ?>
        <div class="row">
            <div class="col-md-8 m-b-20">
                <div class="panel panel-default">
                    <div class="panel-heading">Запущенные в расчет</div>
                    <div class="panel-body">
                        <table class="table table-world-bonus table-striped datagrid m-b-sm">
                            <thead>
                            <tr>
                                <th>Логин</th>
                                <th>ФИО</th>
                                <th>Статус</th>
                                <th>Сумма</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($payInfo->current as $current) { ?>
                                <tr>
                                    <td><?= $current->login ?></td>
                                    <td><?= trim($current->firstName . ' ' . $current->secondName) ?></td>
                                    <td><?= THelper::t('rank_' . $current->careerRank) ?></td>
                                    <td><?= $current->amount ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8 m-b-20">
                <input type="button" id="cancel-current-world-bonus" class="form-control btn-success" value="Отменить" />
            </div>
        </div>
        <div class="progress-4 progress progress-sm progress-striped active" style="display: none;">
            <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
        </div>
        <?php } else { ?>
            <div class="row">
                <div class="col-md-2 m-b-20">
                    <input type="number" min="0" id="world-bonus-amount" class="form-control" placeholder="Cумма к распределению" id="world-bonus-amount" value="<?= $payInfo->amount ?>" />
                </div>
                <div class="col-md-2 m-b-20">
                    <input type="button" id="get-world-bonus" class="form-control btn-success" value="Расчитать" />
                </div>
            </div>
            <div class="progress-3 progress progress-sm progress-striped active" style="display: none;">
                <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
            </div>
            <div id="world-bonus-calculated-pay-info"></div>
        <?php } ?>
<?php } else { ?>
    <div class="row">
        <div class="col-md-2 m-b-20">
            <input type="hidden" name="users_as_string" value="<?= $payInfo->usersAsString ?>" />
            <input type="hidden" name="month" value="<?= $payInfo->month ?>" />
            <input type="hidden" name="year" value="<?= $payInfo->year ?>" />
            <input type="button" id="set-world-bonus" class="form-control btn-success" value="Запустить в расчет" />
        </div>
    </div>
    <div class="progress-5 progress progress-sm progress-striped active" style="display: none;">
        <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
    </div>
    <script>
        $('#set-world-bonus').click(function() {
            $('.progress-5').show();
            $.ajax({
                url: '/' + LANG + '/business/transactions/set-world-bonus',
                method: 'post',
                data: {
                    month: $('input[name="month"]').val(),
                    year: $('input[name="year"]').val(),
                    users: $('input[name="users_as_string"]').val()
                },
                success: function (data) {
                    loadWorldBonusPayInfo();
                }
            });
        });
    </script>
<?php } ?>
<?php if (is_null($amount)) { ?>
    <script>
        function getWorldBonusPayInfo() {
            $('.progress-3').show();
            $('#world-bonus-calculated-pay-info').html('');
            $.ajax({
                url: '/' + LANG + '/business/transactions/get-world-bonus-pay-info',
                method: 'post',
                data: {
                    date: $('#month-from-pay').val() + '.' + $('#year-from-pay').val(),
                    amount: $('#world-bonus-amount').val()
                },
                success: function (data) {
                    $('#world-bonus-calculated-pay-info').html(data);
                    $('.progress-3').hide();
                }
            });
        }
        $('#get-world-bonus').click(function () {
            getWorldBonusPayInfo();
        });
        function cancelCurrentWorldBonus() {
            $('.progress-4').show();
            $.ajax({
                url: '/' + LANG + '/business/transactions/cancel-current-world-bonus',
                method: 'post',
                data: {
                    date: $('#month-from-pay').val() + '.' + $('#year-from-pay').val(),
                },
                success: function (data) {
                    loadWorldBonusPayInfo();
                }
            });
        }
        $('#cancel-current-world-bonus').click(function() {
            cancelCurrentWorldBonus();
        });
    </script>
<?php }
} ?>

