<?php use app\components\THelper; ?>
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
                </tr>
                <?php $k--; } ?>
            </tbody>
        </table>
    </div>
</section>
<?php } else { ?>
    <?= THelper::t('lottery_rules_no_winners') ?>
<?php } ?>