<?php use app\components\THelper; ?>
    <h3><?= THelper::t('promotion_turkey_forum_results_title'); ?></h3>
<?php if ($promos) { ?>
    <section class="panel">
        <div class="table-responsive">
            <table class="table table-striped table-results">
                <thead>
                <tr>
                    <th><?= THelper::t('promotion_turkey_forum_table_number') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_username') ?></th>
                    <th><?= THelper::t('promotion_turkey_forum_table_steps') ?></th>
                    <th><?= THelper::t('promotion_travel_table_dateComplete') ?></th>
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
                            <?= $u->steps ?>
                        </td>
                        <td>
                            <?= isset($u->dateComplete) ? gmdate('d.m.Y', $u->dateComplete) : '' ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } else { ?>
    <?= THelper::t('promotion_turkey_forum_no_results') ?>
<?php } ?>