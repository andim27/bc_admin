<?php use app\components\THelper; ?>
<div class="row">
    <div class="col-md-12">
        <h3><?= THelper::t('promotion_currenr_results_title'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#i" aria-controls="i" role="tab" data-toggle="tab">I</a></li>
            <li><a href="#ii" aria-controls="ii" role="tab" data-toggle="tab">II</a></li>
            <li><a href="#iii" aria-controls="iii" role="tab" data-toggle="tab">III</a></li>
            <li><a href="#iv" aria-controls="iv" role="tab" data-toggle="tab">IV</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="i">
                <?php if ($promos1) { ?>
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
                                    <th><?= THelper::t('promotion_current_table_points') ?></th>
                                    <th><?= THelper::t('promotion_current_table_points_need') ?></th>
                                    <th><?= THelper::t('promotion_turkey_forum_table_completed') ?></th>
                                    <th><?= THelper::t('promotion_travel_table_dateCompleted') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($promos1 as $i => $promo) { ?>
                                    <tr>
                                        <td>
                                            <?= ++$i ?>
                                        </td>
                                        <td>
                                            <?= $promo->username ?>
                                        </td>
                                        <td>
                                            <?= $promo->firstName ?>
                                        </td>
                                        <td>
                                            <?= $promo->secondName ?>
                                        </td>
                                        <td>
                                            <?= $promo->country ?>
                                        </td>
                                        <td>
                                            <?= $promo->city ?>
                                        </td>
                                        <td>
                                            <?= $promo->steps ?>
                                        </td>
                                        <td>
                                            <?= $promo->needSteps ?>
                                        </td>
                                        <td>
                                            <?= $promo->salesSum ?>
                                        </td>
                                        <td>
                                            3000
                                        </td>
                                        <td>
                                            <?= $promo->completed ? THelper::t('yes') : THelper::t('no') ?>
                                        </td>
                                        <td>
                                            <?= isset($promo->date) ? $promo->date->toDateTime()->format('d.m.Y') : '' ?>
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
            </div>
            <div role="tabpanel" class="tab-pane" id="ii">
                <?php if ($promos2) { ?>
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
                                    <th><?= THelper::t('promotion_current_table_points') ?></th>
                                    <th><?= THelper::t('promotion_current_table_points_need') ?></th>
                                    <th><?= THelper::t('promotion_turkey_forum_table_completed') ?></th>
                                    <th><?= THelper::t('promotion_travel_table_dateCompleted') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($promos2 as $i => $promo) { ?>
                                    <tr>
                                        <td>
                                            <?= ++$i ?>
                                        </td>
                                        <td>
                                            <?= $promo->username ?>
                                        </td>
                                        <td>
                                            <?= $promo->firstName ?>
                                        </td>
                                        <td>
                                            <?= $promo->secondName ?>
                                        </td>
                                        <td>
                                            <?= $promo->country ?>
                                        </td>
                                        <td>
                                            <?= $promo->city ?>
                                        </td>
                                        <td>
                                            <?= $promo->steps ?>
                                        </td>
                                        <td>
                                            <?= $promo->needSteps ?>
                                        </td>
                                        <td>
                                            <?= $promo->salesSum ?>
                                        </td>
                                        <td>
                                            3000
                                        </td>
                                        <td>
                                            <?= $promo->completed ? THelper::t('yes') : THelper::t('no') ?>
                                        </td>
                                        <td>
                                            <?= isset($promo->date) ? $promo->date->toDateTime()->format('d.m.Y') : '' ?>
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
            </div>
            <div role="tabpanel" class="tab-pane" id="iii">
                <?php if ($promos3) { ?>
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
                                    <th><?= THelper::t('promotion_current_table_points') ?></th>
                                    <th><?= THelper::t('promotion_current_table_points_need') ?></th>
                                    <th><?= THelper::t('promotion_turkey_forum_table_completed') ?></th>
                                    <th><?= THelper::t('promotion_travel_table_dateCompleted') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($promos3 as $i => $promo) { ?>
                                    <tr>
                                        <td>
                                            <?= ++$i ?>
                                        </td>
                                        <td>
                                            <?= $promo->username ?>
                                        </td>
                                        <td>
                                            <?= $promo->firstName ?>
                                        </td>
                                        <td>
                                            <?= $promo->secondName ?>
                                        </td>
                                        <td>
                                            <?= $promo->country ?>
                                        </td>
                                        <td>
                                            <?= $promo->city ?>
                                        </td>
                                        <td>
                                            <?= $promo->steps ?>
                                        </td>
                                        <td>
                                            <?= $promo->needSteps ?>
                                        </td>
                                        <td>
                                            <?= $promo->salesSum ?>
                                        </td>
                                        <td>
                                            3000
                                        </td>
                                        <td>
                                            <?= $promo->completed ? THelper::t('yes') : THelper::t('no') ?>
                                        </td>
                                        <td>
                                            <?= isset($promo->date) ? $promo->date->toDateTime()->format('d.m.Y') : '' ?>
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
            </div>
            <div role="tabpanel" class="tab-pane" id="iv">
                <?php if ($promos4) { ?>
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
                                    <th><?= THelper::t('promotion_current_table_points') ?></th>
                                    <th><?= THelper::t('promotion_current_table_points_need') ?></th>
                                    <th><?= THelper::t('promotion_turkey_forum_table_completed') ?></th>
                                    <th><?= THelper::t('promotion_travel_table_dateCompleted') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($promos4 as $i => $promo) { ?>
                                    <tr>
                                        <td>
                                            <?= ++$i ?>
                                        </td>
                                        <td>
                                            <?= $promo->username ?>
                                        </td>
                                        <td>
                                            <?= $promo->firstName ?>
                                        </td>
                                        <td>
                                            <?= $promo->secondName ?>
                                        </td>
                                        <td>
                                            <?= $promo->country ?>
                                        </td>
                                        <td>
                                            <?= $promo->city ?>
                                        </td>
                                        <td>
                                            <?= $promo->steps ?>
                                        </td>
                                        <td>
                                            <?= $promo->needSteps ?>
                                        </td>
                                        <td>
                                            <?= $promo->salesSum ?>
                                        </td>
                                        <td>
                                            3000
                                        </td>
                                        <td>
                                            <?= $promo->completed ? THelper::t('yes') : THelper::t('no') ?>
                                        </td>
                                        <td>
                                            <?= isset($promo->date) ? $promo->date->toDateTime()->format('d.m.Y') : '' ?>
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
            </div>
    </div>
</div>
<script>
    $('.table').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });
</script>