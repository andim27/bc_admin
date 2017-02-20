<?php use app\components\THelper; ?>
<div class="row" style="position: relative">
    <img src="/images/lottery.png">
    <div id="display" class="text-center" style="font-size: 60px; position: absolute; left: 132px; top: 284px; width: 637px; overflow: hidden;"></div>
    <input type="image" id="start-btn" style="position: absolute; top: 455px; left: 230px;" disabled="disabled" width="130px" src="/images/lottery_start_btn.png" />
    <input type="image" id="save-btn" style="position: absolute; top: 455px; left: 380px;" disabled="disabled" width="130px" src="/images/lottery_save_btn.png" />
    <input type="image" id="clear-btn" style="position: absolute; top: 455px; left: 530px;" disabled="disabled" width="130px" src="/images/lottery_clear_btn.png" />
</div>
<h4><?= THelper::t('settings_lottery_rules_winners_title'); ?></h4>
<div id="winners-list">
    <?= $this->render('_winners', [
        'winners' => $winners
    ]); ?>
</div>
<script>
    var users = <?= $users ?>;
    var winnableUsers;
    var max;
    var maxAll = <?= $countAll ?>;
    var min = 1;
    var rand;
    var randAll;
    var currentUser;
    var winner;
    var timerId;

    function checkWinnable() {
        $.ajax({
            url: '/' + LANG + '/ru/business/lottery/get-winnable',
            method: 'get',
            data: {},
            success: function(data) {
                winnableUsers = data.winnableUsers;
                max = data.count;
                if (winnableUsers != '') {
                    $('#start-btn').removeAttr('disabled');
                }
            }
        });
    }

    checkWinnable();

    function start() {
        $('#save-btn').attr('disabled', 'disabled');
        $('#clear-btn').attr('disabled', 'disabled');
        timerId = setInterval(function () {
            randAll = min + Math.floor(Math.random() * (maxAll + 1 - min));
            rand = min + Math.floor(Math.random() * (max + 1 - min));
            currentUser = users[randAll];
            winner = winnableUsers[rand];
            $('#display').html(currentUser.username);
        }, 100);

        setTimeout(function () {
            clearInterval(timerId);
            setTimeout(function () {
                currentUser = winner;
                $('#display').html(currentUser.username);
                $('#save-btn').removeAttr('disabled');
                $('#clear-btn').removeAttr('disabled');
                $('#start-btn').removeAttr('disabled');
            }, 100);
        }, 10000);
    }

    $('#start-btn').click(function() {
        $('#start-btn').attr('disabled', 'disabled');
        start();
    });

    $('#save-btn').click(function() {
        $('#save-btn').attr('disabled', 'disabled');
        $('#start-btn').attr('disabled', 'disabled');
        $('#clear-btn').attr('disabled', 'disabled');
        $.ajax({
            url: '/' + LANG + '/ru/business/lottery/add-winner',
            method: 'POST',
            data: {
                id: currentUser.id
            },
            success: function(data) {
                if (data) {
                    $('#winners-list').html(data);
                    currentUser = '';
                    checkWinnable();
                    $('#clear-btn').removeAttr('disabled');
                }
            }
        });
    });

    $('#clear-btn').click(function() {
        $('#display').html('');
        currentUser = '';
        $(this).attr('disabled', 'disabled');
        $('#save-btn').attr('disabled', 'disabled');
    });
</script>