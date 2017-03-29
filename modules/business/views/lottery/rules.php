<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<h3><?= THelper::t('settings_lottery_rules_title'); ?></h3>
<h4><?= THelper::t('settings_lottery_rules_winners_title'); ?></h4>
<?php if ($winners) { ?>
<section class="panel">
    <div class="table-responsive">
        <table class="table table-striped table-winners">
            <thead>
            <tr>
                <th><?= THelper::t('settings_lottery_rules_table_number') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_username') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_firstName') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_secondName') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_country') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_city') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_delete') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $k = count($winners); foreach ($winners as $w) { ?>
                <tr>
                    <td>
                        <?= $k ?>
                    </td>
                    <td>
                        <?= $w->username ?>
                    </td>
                    <td>
                        <?= $w->firstName ?>
                    </td>
                    <td>
                        <?= $w->secondName ?>
                    </td>
                    <td>
                        <?= $w->countryCode ? $w->getCountry()->name : '' ?>
                    </td>
                    <td>
                        <?= $w->city ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/lottery/remove-winner', 'id' => $w->userId], ['onclick' => 'return confirmRemoving();']) ?>
                    </td>
                </tr>
            <?php $k--; } ?>
            </tbody>
        </table>
    </div>
</section>
<div class="text-right">
    <?= Html::a(THelper::t('settings_lottery_rules_clear'), '/business/lottery/clear-winners', ['onclick' => 'return confirmClearing();']) ?>
</div>
<?php } else { ?>
    <?= THelper::t('lottery_rules_no_winners') ?>
<?php } ?>
<h4><?= THelper::t('settings_lottery_rules_banned_title'); ?></h4>
<?php if ($users) { ?>
    <div class="row m-b-20">
        <div class="col-md-6">
            <select class="form-control" id="add-banned-user-id">
                <option value="0"><?= THelper::t('lottery_rules_select_user_for_ban') ?></option>
                <?php foreach ($users as $user) { ?>
                    <option value="<?= $user->id ?>"><?= $user->username ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-6">
            <?= Html::a(THelper::t('lottery_rules_ban'), ['/business/lottery/add-banned'], ['id' => 'add-banned-btn', 'class' => 'btn btn-danger']) ?>
        </div>
    </div>
<?php } ?>
<?php if ($banned) { ?>
<section class="panel">
    <div class="table-responsive">
        <table class="table table-striped table-banned">
            <thead>
            <tr>
                <th><?= THelper::t('settings_lottery_rules_table_number') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_username') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_firstName') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_secondName') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_country') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_city') ?></th>
                <th><?= THelper::t('settings_lottery_rules_table_delete') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $j = count($banned); foreach ($banned as $b) { ?>
                <tr>
                    <td>
                        <?= $j ?>
                    </td>
                    <td>
                        <?= $b->username ?>
                    </td>
                    <td>
                        <?= $b->firstName ?>
                    </td>
                    <td>
                        <?= $b->secondName ?>
                    </td>
                    <td>
                        <?= $b->countryCode ? $b->getCountry()->name : '' ?>
                    </td>
                    <td>
                        <?= $b->city ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/lottery/remove-banned', 'id' => $b->userId], ['onclick' => 'return confirmRemoving();']) ?>
                    </td>
                </tr>
                <?php $j--; } ?>
            </tbody>
        </table>
    </div>
</section>
<div class="text-right m-b-20">
    <?= Html::a(THelper::t('settings_lottery_rules_clear'), '/business/lottery/clear-banned', ['onclick' => 'return confirmClearing();']) ?>
</div>
<?php } else { ?>
    <?= THelper::t('lottery_rules_no_banned') ?>
<?php } ?>
<?php if ($users) { ?>
    <h4><?= THelper::t('settings_lottery_rules_users_title'); ?></h4>
    <section class="panel">
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
                    <th><?= THelper::t('settings_lottery_rules_table_tokens') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $m = count($users); foreach ($users as $u) { ?>
                    <tr>
                        <td>
                            <?= $m ?>
                        </td>
                        <td>
                            <?= $u->username ?>
                        </td>
                        <td>
                            <?= $u->firstName ?>
                        </td>
                        <td>
                            <?= $u->secondName ?>
                        </td>
                        <td>
                            <?= $u->countryCode ? $u->getCountry()->name : '' ?>
                        </td>
                        <td>
                            <?= $u->city ?>
                        </td>
                        <td>
                            <?= $u->tokens ?>
                        </td>
                    </tr>
                    <?php $m--; } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } else { ?>
    <?= THelper::t('lottery_rules_no_users') ?>
<?php } ?>
<script>
    function confirmRemoving() {
        if (confirm("<?= THelper::t('settings_lottery_removing_confirm') ?>")) {
            return true;
        } else {
            return false;
        }
    }
    function confirmClearing() {
        if (confirm("<?= THelper::t('settings_lottery_clearing_confirm') ?>")) {
            return true;
        } else {
            return false;
        }
    }
    $('#add-banned-btn').click(function() {
        var userId = $('#add-banned-user-id').val();
        if (userId != 0) {
            var href = $(this).attr('href');
            $(this).attr('href', href + '?id=' + userId);
            return true;
        }
        return false;
    });
    var table = $('.table-users').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });
</script>