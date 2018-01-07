<?php use app\components\THelper; ?>
<div class="row m-b-20" style="position: relative">
    <img src="/images/lottery.png">
    <div id="display" class="text-center" style="font-size: 60px; position: absolute; left: 132px; top: 284px; width: 637px; overflow: hidden;"></div>
    <input type="image" id="start-btn" style="position: absolute; top: 455px; left: 230px;" disabled="disabled" width="130px" src="/images/lottery_start_btn.png" />
    <input type="image" id="save-btn" style="position: absolute; top: 455px; left: 380px;" disabled="disabled" width="130px" src="/images/lottery_save_btn.png" />
    <input type="image" id="clear-btn" style="position: absolute; top: 455px; left: 530px;" disabled="disabled" width="130px" src="/images/lottery_clear_btn.png" />
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title"><?= THelper::t('settings_lottery_rules_winners_title'); ?></span>
    </div>
    <div class="panel-body">
        <div id="winner-users">
            <?= $this->render('_winners', [
                'winners' => $winners
            ]); ?>
        </div>
    </div>
</div>
<script>
    var tickets;
    var winner;

    function getTickets() {
        $.ajax({
            url: '/' + LANG + '/ru/business/lottery/get-tickets',
            method: 'post',
            data: {},
            success: function(data) {
                if (data.length > 0) {
                    tickets = data;
                    $('#start-btn').removeAttr('disabled');
                }
            }
        });
    }

    getTickets();

    function reloadWinners() {
        $.ajax({
            url: '/' + LANG + '/ru/business/lottery/get-winners',
            method: 'get',
            data: {},
            success: function(data) {
                $('#winner-users').html(data);
            }
        });
    }

    $('#start-btn').click(function() {
        $.ajax({
            url: '/' + LANG + '/ru/business/lottery/get-tickets',
            method: 'post',
            data: {},
            success: run
        });
        function run(data) {
            if (data.length > 0) {
                tickets = data;
                var currentTicket;

                $('#start-btn').attr('disabled', 'disabled');
                $('#save-btn').attr('disabled', 'disabled');
                $('#clear-btn').attr('disabled', 'disabled');

                var timerId = setInterval(function () {
                    if (tickets.length > 0) {
                        currentTicket = tickets.pop();
                        $('#display').html(currentTicket.ticket);
                    } else {
                        stop(currentTicket);
                    }
                }, 25);

                function stop(ticket) {
                    clearInterval(timerId);
                    winner = ticket;
                    $('#save-btn').removeAttr('disabled');
                    $('#clear-btn').removeAttr('disabled');
                    $('#start-btn').removeAttr('disabled');
                }
            }
        }
    });

    $('#save-btn').click(function() {
        $('#start-btn').attr('disabled', 'disabled');
        $('#save-btn').attr('disabled', 'disabled');
        $('#clear-btn').attr('disabled', 'disabled');
        $.ajax({
            url: '/' + LANG + '/ru/business/lottery/add-winner',
            method: 'post',
            data: {id: winner.id},
            success: function(data) {
                if (data.success) {
                    reloadWinners();
                    clear();
                    getTickets();
                } else {
                    alert(data.error);
                }
            }
        });
    });

    $('#clear-btn').click(function() {
        clear();
    });

    function clear() {
        winner = undefined;
        $('#display').html('');
        $('#save-btn').attr('disabled', 'disabled');
        $('#clear-btn').attr('disabled', 'disabled');
    }
</script>