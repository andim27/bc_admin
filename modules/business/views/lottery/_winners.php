<?php
    use app\components\THelper;
?>
<?php if ($winners) { ?>
    <section class="panel">
        <div class="table-responsive">
            <table class="table table-striped table-winners">
                <thead>
                <tr>
                    <th><?= THelper::t('settings_lottery_rules_table_number') ?></th>
                    <th><?= THelper::t('settings_lottery_rules_table_ticket') ?></th>
                    <th><?= THelper::t('settings_lottery_rules_table_username') ?></th>
                    <th><?= THelper::t('settings_lottery_rules_table_firstName') ?></th>
                    <th><?= THelper::t('settings_lottery_rules_table_secondName') ?></th>
                    <th><?= THelper::t('settings_lottery_rules_table_country') ?></th>
                    <th><?= THelper::t('settings_lottery_rules_table_city') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $k = 1; foreach ($winners as $w) { ?>
                    <tr>
                        <td>
                            <?= $k ?>
                        </td>
                        <td>
                            <span class="text-danger"><b><?= $w->ticket()->ticket ?></b></span>
                        </td>
                        <td>
                            <?= $w->ticket()->user()->username ?>
                        </td>
                        <td>
                            <?= $w->ticket()->user()->firstName ?>
                        </td>
                        <td>
                            <?= $w->ticket()->user()->secondName ?>
                        </td>
                        <td>
                            <?= $w->ticket()->user()->getCountry() ?>
                        </td>
                        <td>
                            <?= $w->ticket()->user()->city ?>
                        </td>
                    </tr>
                    <?php $k++; } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } else { ?>
    <?= THelper::t('lottery_rules_no_winners') ?>
<?php } ?>