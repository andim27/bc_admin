<?php use app\components\THelper; ?>
<div class="row">
    <div class="col-md-12">
        <h3><?= THelper::t('sidebar_promotion_spain'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php if ($promotions) { ?>
            <div class="panel">
                <div class="table-responsive">
                    <table class="table table-striped table-results">
                        <thead>
                        <tr>
                            <th><?= THelper::t('promotion_turkey_forum_table_username') ?></th>
                            <th><?= THelper::t('promotion_turkey_forum_table_firstname') ?></th>
                            <th><?= THelper::t('promotion_turkey_forum_table_secondname') ?></th>
                            <th><?= THelper::t('promotion_turkey_forum_table_country') ?></th>
                            <th><?= THelper::t('promotion_turkey_forum_table_city') ?></th>
                            <th><?= THelper::t('promotion_current_table_points') ?></th>
                            <th><?= THelper::t('promotion_current_table_progress') ?></th>
                            <th><?= THelper::t('promotion_turkey_forum_table_completed') ?></th>
                            <th><?= THelper::t('promotion_travel_table_dateCompleted') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($promotions as $promotion) { ?>
                            <tr>
                                <td>
                                    <?= $promotion->username ?>
                                </td>
                                <td>
                                    <?= $promotion->firstName ?>
                                </td>
                                <td>
                                    <?= $promotion->secondName ?>
                                </td>
                                <td>
                                    <?= $promotion->country ?>
                                </td>
                                <td>
                                    <?= $promotion->city ?>
                                </td>
                                <td>
                                    <?= $promotion->points ?>
                                </td>
                                <td>
                                    <?php
                                        $promoProgress = $promotion->points * 0.1;
                                        if ($promoProgress >= 100) {
                                            $promoProgress = 100;
                                        }
                                    ?>
                                    <?= $promoProgress ?>%
                                </td>
                                <td>
                                    <?= $promotion->completed ? THelper::t('yes') : THelper::t('no') ?>
                                </td>
                                <td>
                                    <?= isset($promotion->date) ? $promotion->date->toDateTime()->format('d.m.Y') : '' ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } else { ?>
            <?= THelper::t('promotion_current_no_results') ?>
        <?php } ?>
    </div>
</div>
<script>
    $('.table').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });
</script>