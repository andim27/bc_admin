<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<?php if ($winners) { ?>
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
                <th><?= THelper::t('settings_lottery_rules_table_delete') ?></th>
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
                    <td>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', 'javascript:void(0);', ['class' => 'remove-winner', 'data-id' => strval($w->_id) ]) ?>
                    </td>
                </tr>
                <?php $k++; } ?>
            </tbody>
        </table>
    </div>
    <div class="text-right">
        <?= Html::a(THelper::t('settings_lottery_rules_clear'), 'javascript:void(0);', ['id' => 'clear-winners', 'class' => 'btn btn-danger']) ?>
    </div>
<?php } else { ?>
    <?= THelper::t('lottery_rules_no_winners') ?>
<?php } ?>