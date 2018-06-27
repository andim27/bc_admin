<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="loader" style="display:none;"></div>
<h3><?= THelper::t('settings_lottery_rules_title'); ?></h3>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title"><?= THelper::t('settings_lottery_rules_winners_title'); ?></span>
    </div>
    <div class="panel-body">
        <div id="winner-users">
            <?= $this->render('_winners_admin', [
                'winners' => $winners
            ]); ?>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title"><?= THelper::t('settings_lottery_rules_banned_title'); ?></span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 m-b-20">
                <?= Html::textInput('add_banned_user_login', '', ['id' => 'add-banned-user-login', 'class' => 'form-control', 'placeholder' => THelper::t('lottery_banned_user_login')]) ?>
            </div>
            <div class="col-md-6 m-b-20">
                <?= Html::a(THelper::t('lottery_rules_ban'), null, ['id' => 'add-banned-btn', 'class' => 'btn btn-danger']) ?>
            </div>
        </div>
        <div id="banned-users">
            <?= $this->render('_banned', [
                'banned' => $banned
            ]); ?>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <span class="panel-title"><?= THelper::t('settings_lottery_rules_users_title'); ?></span>
    </div>
    <div class="panel-body">
        <?php if ($users) { ?>
            <div class="table-responsive">
                <table class="table table-striped table-users">
                    <thead>
                    <tr>
                        <th><?= THelper::t('settings_lottery_rules_table_number') ?></th>
                        <th><?= THelper::t('settings_lottery_rules_table_username') ?></th>
                        <th><?= THelper::t('settings_lottery_rules_table_firstName') ?></th>
                        <th><?= THelper::t('settings_lottery_rules_table_secondName') ?></th>
                        <th><?= THelper::t('settings_lottery_rules_table_country') ?></th>
                        <th><?= THelper::t('settings_lottery_rules_table_city') ?></th>
                        <th><?= THelper::t('settings_lottery_rules_table_tickets') ?></th>
                        <th><?= THelper::t('settings_lottery_rules_table_actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $m = count($users); foreach ($users as $userId => $user) { ?>
                        <tr>
                            <td>
                                <?= $m ?>
                            </td>
                            <td>
                                <?= $user['username'] ?>
                            </td>
                            <td>
                                <?= $user['firstName'] ?>
                            </td>
                            <td>
                                <?= $user['secondName'] ?>
                            </td>
                            <td>
                                <?= $user['countryName'] ?>
                            </td>
                            <td>
                                <?= $user['city'] ?>
                            </td>
                            <td class="tickets" data-id="<?= strval($userId) ?>">
                                <?php $x2 = false; foreach ($user['tickets'] as $ticket) {
                                    if ($x2 == false) {
                                        $x2 = $ticket->x2 == true;
                                    } ?>
                                    <p><span class="text-<?= $ticket->x2 ? 'success' : 'danger'?>"><b><?= $ticket->ticket ?></b></span></p>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!$x2) { ?>
                                    <?= Html::a('<i class="fa fa-times"></i> <b>2</b>', 'javascript:void(0);', ['class' => 'btn btn-success x2-tickets', 'data-id' => strval($userId)]) ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $m--; } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <?= THelper::t('lottery_rules_no_users') ?>
        <?php } ?>
    </div>
</div>
<script>
    function bindClearBanned() {
        $('#clear-banned').click(function() {
            if (confirm("<?= THelper::t('settings_lottery_clearing_confirm') ?>")) {
                $.ajax({
                    url: '/' + LANG + '/ru/business/lottery/clear-banned',
                    method: 'post',
                    data: {},
                    success: function (data) {
                        if (data.success) {
                            reloadBanned();
                        } else {
                            alert(data.error);
                        }
                        return false;
                    }
                });
            } else {
                return false;
            }
        });
    }
    bindClearBanned();

    function bindRemoveBanned() {
        $('.remove-banned').click(function() {
            var thisA = $(this);
            if (confirm("<?= THelper::t('settings_lottery_removing_confirm') ?>")) {
                $.ajax({
                    url: '/' + LANG + '/ru/business/lottery/remove-banned',
                    method: 'post',
                    data: {
                        'user-id': thisA.data('id')
                    },
                    success: function (data) {
                        if (data.success) {
                            reloadBanned();
                        } else {
                            alert(data.error);
                        }
                        return false;
                    }
                });
            } else {
                return false;
            }
        });
    }
    bindRemoveBanned();

    function bindClearWinners() {
        $('#clear-winners').click(function() {
            if (confirm("<?= THelper::t('settings_lottery_clearing_confirm') ?>")) {
                $.ajax({
                    url: '/' + LANG + '/ru/business/lottery/clear-winners',
                    method: 'post',
                    data: {},
                    success: function (data) {
                        if (data.success) {
                            reloadWinners();
                        } else {
                            alert(data.error);
                        }
                        return false;
                    }
                });
            } else {
                return false;
            }
        });
    }
    bindClearWinners();

    function bindRemoveWinner() {
        $('.remove-winner').click(function() {
            var thisA = $(this);
            if (confirm("<?= THelper::t('settings_lottery_removing_confirm') ?>")) {
                $.ajax({
                    url: '/' + LANG + '/ru/business/lottery/remove-winner',
                    method: 'post',
                    data: {'id': thisA.data('id')},
                    success: function (data) {
                        if (data.success) {
                            reloadWinners();
                        } else {
                            alert(data.error);
                        }
                        return false;
                    }
                });
            } else {
                return false;
            }
        });
    }
    bindRemoveWinner();

    $('#add-banned-btn').click(function() {
        var userLogin = $('#add-banned-user-login').val();
        if (userLogin) {
            $.ajax({
                url: '/' + LANG + '/ru/business/lottery/add-banned',
                method: 'post',
                data: {'user-login': userLogin},
                success: function(data) {
                    if (data.success) {
                        reloadBanned();
                    } else {
                        alert(data.error);
                    }
                }
            });
        }
        return false;
    });

    function reloadBanned() {
        $.ajax({
            url: '/' + LANG + '/ru/business/lottery/get-banned',
            method: 'post',
            data: {},
            success: function(data) {
                $('#banned-users').html(data);
                bindClearBanned();
                bindRemoveBanned();
            }
        });
    }

    function reloadWinners() {
        $.ajax({
            url: '/' + LANG + '/ru/business/lottery/get-winners',
            method: 'post',
            data: {'for-admin': true},
            success: function(data) {
                $('#winner-users').html(data);
                bindClearWinners();
                bindRemoveWinner();
            }
        });
    }

    $('.x2-tickets').click(function () {
        var thisA = $(this);
        var userId = thisA.data('id');
        if (confirm("<?= THelper::t('settings_lottery_x2_tickets_confirm') ?>")) {
            $.ajax({
                url: '/' + LANG + '/ru/business/lottery/x2-tickets',
                method: 'post',
                data: {'id': userId},
                success: function (data) {
                    if (data.success) {
                        thisA.hide();
                        $('.tickets[data-id="' + userId + '"]').html(data.tickets);
                    } else {
                        alert(data.error);
                    }
                    return false;
                }
            });
        } else {
            return false;
        }
    });

    var table = $('.table-users').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });
</script>