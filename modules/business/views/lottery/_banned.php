<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<?php if ($banned) { ?>
    <div class="row">
        <div class="col-md-12">
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
                    <?php $j = 1; foreach ($banned as $b) { ?>
                        <tr>
                            <td>
                                <?= $j ?>
                            </td>
                            <td>
                                <?= $b->user()->username ?>
                            </td>
                            <td>
                                <?= $b->user()->firstName ?>
                            </td>
                            <td>
                                <?= $b->user()->secondName ?>
                            </td>
                            <td>
                                <?= $b->user()->getCountry() ?>
                            </td>
                            <td>
                                <?= $b->user()->city ?>
                            </td>
                            <td>
                                <?= Html::a('<i class="fa fa-trash-o"></i>', 'javascript:void(0);', ['class' => 'remove-banned', 'data-id' => strval($b->userId) ]) ?>
                            </td>
                        </tr>
                        <?php $j++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12 text-right">
            <?= Html::a(THelper::t('settings_lottery_rules_clear'), 'javascript:void(0);', ['id' => 'clear-banned', 'class' => 'btn btn-danger']) ?>
        </div>
    </div>
<?php } else { ?>
    <?= THelper::t('lottery_rules_no_banned') ?>
<?php } ?>