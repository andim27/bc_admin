<?php use app\components\THelper; ?>
    <h3><?= THelper::t('promotion_currenr_results_title'); ?></h3>
<?php if ($promos) { ?>
    <section class="panel">
        <div class="table-responsive">
            <table class="table table-striped table-results">
                <thead>
                <tr>
                    <th><?= THelper::t('promotion_turkey_forum_table_number') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_username') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_firstname') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_secondname') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_country') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_city') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_steps') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_steps_need') ?></th>
                    <th><?= THelper::t('promotion_current_table_points_need') ?></th>
                    <th><?= THelper::t('promotion_current_table_points_completed') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_completed') ?></th>
                    <th><?= THelper::t('promotion_travel_table_dateCompleted') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($promos as $i => $u) { ?>
                    <tr>
                        <td>
                            <?= ++$i ?>
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
                            <?= $u->country ?>
                        </td>
                        <td>
                            <?= $u->city ?>
                        </td>
                        <td>
                            <?= $u->steps ?>
                        </td>
                        <td>
                            <?= $u->needSteps ?>
                        </td>
                        <td>
                            <?= $u->salesSum ?>
                        </td>
                        <td>
                            2500
                        </td>
                        <td>
                            <?= $u->completed ? THelper::t('yes') : THelper::t('no') ?>
                        </td>
                        <td>
                            <?= isset($u->dateCompleted) ? $u->dateCompleted->toDateTime()->format('d.m.Y') : '' ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } else { ?>
    <?= THelper::t('promotion_current_no_results') ?>
<?php } ?>