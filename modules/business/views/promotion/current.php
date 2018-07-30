<?php use app\components\THelper; ?>
<div class="row">
    <div class="col-md-12">
        <h3><?= THelper::t('promotion_currenr_results_title'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#ietap" aria-controls="ietap" role="tab" data-toggle="tab">I Этап</a></li>
            <li><a href="#iietap" aria-controls="iietap" role="tab" data-toggle="tab">II Этап</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ietap">
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
                                                    <?= $promo->needSaleSum ?>
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
                                                    <?= $promo->needSaleSum ?>
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
                                                    <?= $promo->needSaleSum ?>
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
                                                    <?= $promo->needSaleSum ?>
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
            <div role="tabpanel" class="tab-pane" id="iietap">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#ii2" aria-controls="ii2" role="tab" data-toggle="tab">II</a></li>
                    <li><a href="#iii2" aria-controls="iii2" role="tab" data-toggle="tab">III</a></li>
                    <li><a href="#iv2" aria-controls="iv2" role="tab" data-toggle="tab">IV</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="ii2">
                        <?php if ($promos22) { ?>
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
                                        <?php foreach ($promos22 as $i => $promo) { ?>
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
                                                    <?= $promo->steps2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->needSteps2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->salesSum2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->needSaleSum2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->completed2 ? THelper::t('yes') : THelper::t('no') ?>
                                                </td>
                                                <td>
                                                    <?= isset($promo->date2) ? $promo->date2->toDateTime()->format('d.m.Y') : '' ?>
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
                    <div role="tabpanel" class="tab-pane" id="iii2">
                        <?php if ($promos32) { ?>
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
                                        <?php foreach ($promos32 as $i => $promo) { ?>
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
                                                    <?= $promo->steps2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->needSteps2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->salesSum2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->needSaleSum2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->completed2 ? THelper::t('yes') : THelper::t('no') ?>
                                                </td>
                                                <td>
                                                    <?= isset($promo->date2) ? $promo->date2->toDateTime()->format('d.m.Y') : '' ?>
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
                    <div role="tabpanel" class="tab-pane" id="iv2">
                        <?php if ($promos42) { ?>
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
                                        <?php foreach ($promos42 as $i => $promo) { ?>
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
                                                    <?= $promo->steps2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->needSteps2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->salesSum2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->needSaleSum2 ?>
                                                </td>
                                                <td>
                                                    <?= $promo->completed2 ? THelper::t('yes') : THelper::t('no') ?>
                                                </td>
                                                <td>
                                                    <?= isset($promo->date2) ? $promo->date2->toDateTime()->format('d.m.Y') : '' ?>
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
        </div>
    </div>
</div>
<script>
    $('.table').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });
</script>