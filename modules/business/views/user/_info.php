<?php

use app\components\THelper;
use yii\helpers\Html;

?>
<?php if ($user) { ?>
    <style>
        .tab-pane {
            padding-top: 5px;
        }
    </style>
    <div>
        <!-- Навигация -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#profile" aria-controls="profile" role="tab"
                                  data-toggle="tab"><?= THelper::t('profile') ?></a></li>
            <li><a href="#accrued_commissions" aria-controls="accrued_commissions" role="tab"
                   data-toggle="tab"><?= THelper::t('accrued_commissions') ?></a></li>
            <li><a href="#history_of_scoring_points" aria-controls="history_of_scoring_points" role="tab"
                   data-toggle="tab"><?= THelper::t('history_of_scoring_points') ?></a></li>
        </ul>

        <!-- Содержимое вкладок -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="profile">
                <div class="row">
                    <div class="col-lg-6">
                        <section class="panel panel-default">
                            <div class="panel-body">
                                <div class="clearfix text-center m-t">
                                    <div class="inline">
                                        <div style="width: 160px; height: 130px; line-height: 130px;"
                                             class="easypiechart easyPieChart" data-percent="75" data-line-width="5"
                                             data-bar-color="#4cc0c1" data-track-color="#f5f5f5"
                                             data-scale-color="false" data-size="130" data-line-cap="butt"
                                             data-animate="1000">
                                            <div class="thumb-lg">
                                                <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png'; ?>"
                                                     class="img-circle" width="128" height="128">
                                            </div>
                                            <canvas width="130" height="130"></canvas>
                                        </div>
                                    </div>
                                    <div class="h4 m-t m-b-xs"><?= $user->firstName ?> <?= $user->secondName ?></div>
                                    <small class="text-muted m-b"><?= THelper::t('status') ?>
                                        : <?= THelper::t('rank_' . $user->rank) ?></small>
                                </div>
                            </div>
                            <footer class="panel-footer bg-info text-center">
                                <div class="row pull-out">
                                    <div class="col-xs-4">
                                        <div class="padder-v">
                                            <?php if ($user->firstPurchase > 0) {
                                                $firstPurchase = date_diff(date_create(date('d-m-Y H:i:s', $user->firstPurchase)), date_create())->days;
                                            } else {
                                                $firstPurchase = 0;
                                            } ?>
                                            <span class="m-b-xs h3 block text-white"><?= $firstPurchase ?></span>
                                            <small class="text-muted">
                                                <?= THelper::t('days_in_the_business') ?><!--Дней в бизнесе--></small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 dk">
                                        <div class="padder-v">
                                            <span class="m-b-xs h3 block text-white"><?= $user->rightSideNumberUsers + $user->leftSideNumberUsers ?></span>
                                            <small class="text-muted">
                                                <?= THelper::t('registrations_in_the_structure') ?><!--Регистраций в структуре--></small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="padder-v">
                                            <span class="m-b-xs h3 block text-white"><?= $user->statistics->partnersWithPurchases ?></span>
                                            <small class="text-muted"><?= THelper::t('default_index_partners') ?></small>
                                        </div>
                                    </div>
                                </div>
                            </footer>
                        </section>
                    </div>
                    <div class="col-lg-6">
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                <span class="h4"><?= THelper::t('sponsor_login') ?></span>
                            </header>
                            <div class="slimScrollDiv">
                                <section class="panel-body slim-scroll">
                                    <div class="text-center wrapper bg-light lt">
                                        <div class=" inline" style="height: 165px; width: 165px;">
                                            <?php if ($user->avatar) : ?>
                                                <img src="/images/ranks/rank_<?= $user->rank ?>.png"
                                                     style="height: 165px; width: 165px;">
                                            <?php else : ?>
                                                <img src="/images/ranks/rank_<?= $user->rank ?>.png"
                                                     style="height: 165px; width: 165px;">
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </section>
                                <ul class="list-group no-radius">
                                    <li class="list-group-item">
                                        <span class="label bg-info">1</span> <?= THelper::t('status') ?>
                                        : <?= THelper::t('rank_' . $user->rank) ?>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="label bg-info">2</span> <?= THelper::t('login') ?>
                                        : <?= $user->username ?>
                                    </li>
                                    <?php if ($user->created) { ?>
                                        <li class="list-group-item">
                                            <span class="label bg-info">3</span> <?= THelper::t('registration_date') ?>
                                            : <?= gmdate('d.m.Y', $user->created) ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                <span class="h4"><?= THelper::t('conditions_of_participation_in_business') ?></span>
                            </header>
                            <div style="position: relative; overflow: hidden; width: auto; height: 100px;"
                                 class="slimScrollDiv">
                                <section style="overflow: hidden; width: auto; height: 100px;"
                                         class="panel-body slim-scroll">
                                    <article class="media">
                                        <div class="media-body">
                                            <?= THelper::t('business_support') . ': '; ?>
                                            <?= ($user->expirationDateBS && $user->expirationDateBS > 0) ? (THelper::t('expiration_date_bs') . ' ' . gmdate('d.m.Y', $user->expirationDateBS)) : '-'; ?>
                                            <br/>
                                            <?php if (!$user->autoExtensionBS) { ?>
                                                <?= THelper::t('automatic_extension_of_business_support') ?>: <span
                                                        class="text-color-red"><?= THelper::t('disable') ?></span><br/>
                                            <?php } else { ?>
                                                <?= THelper::t('automatic_extension_of_business_support') ?>: <span
                                                        class="text-color-green"><?= THelper::t('enable') ?></span><br/>
                                            <?php } ?>
                                            <?php if (!$user->personalBonus) { ?>
                                                <?= THelper::t('personal_award_in_the_personal_account') ?>: <span
                                                        class="text-color-red"><?= THelper::t('not_charge') ?></span>
                                                <br/>
                                            <?php } else { ?>
                                                <?= THelper::t('personal_award_in_the_personal_account') ?>: <span
                                                        class="text-color-green"><?= THelper::t('charge') ?></span><br/>
                                            <?php } ?>

                                            <?php if ($product) { ?>
                                                <?= Thelper::t('business_product'); ?>:
                                                <span><?= $product->productName ?></span>
                                            <?php } ?>

                                        </div>
                                    </article>
                                </section>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-6">
                        <section class="panel panel-default">
                            <header class="panel-heading">
                                <span class="h4"><?= THelper::t('structural_award') ?></span>
                            </header>
                            <div style="position: relative; overflow: hidden; width: auto; height: 100px;"
                                 class="slimScrollDiv">
                                <section style="overflow: hidden; width: auto; height: 100px;"
                                         class="panel-body slim-scroll">
                                    <article class="media">
                                        <div class="media-body">
                                            <?php if (!$user->qualification) { ?>
                                                <?= THelper::t('personal_skills') ?>: <span
                                                        class="text-color-red"><?= THelper::t('not_done') ?></span><br/>
                                            <?php } else { ?>
                                                <?= THelper::t('personal_skills') ?>: <span
                                                        class="text-color-green"><?= THelper::t('done') ?></span><br/>
                                            <?php } ?>
                                            <?php if (!$user->structBonus) { ?>
                                                <?= THelper::t('structural_award') ?>: <span
                                                        class="text-color-red"><?= THelper::t('not_charge') ?></span>
                                            <?php } else { ?>
                                                <?= THelper::t('structural_award') ?>: <span
                                                        class="text-color-green"><?= THelper::t('charge') ?></span>
                                            <?php } ?>
                                        </div>
                                    </article>
                                </section>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel panel-default m-b-20">
                            <header class="panel-heading font-bold"><?= THelper::t('schedule_of_structure') ?></header>
                            <div class="panel-body">
                                <div id="flot-chart" style="width:100%; height:400px"></div>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h3><?= THelper::t('client_account') ?></h3>
                        <section class="panel">
                            <div class="text-center wrapper bg-light lt">
                                <div class="sparkline-index inline" data-type="pie" data-height="165"
                                     data-slice-colors="['#77c587','#41586e']">
                                    <?= $user->statistics->personalIncome ?>, <?= $user->statistics->structIncome ?>
                                </div>
                            </div>
                            <ul class="list-group no-radius">
                                <li class="list-group-item">
                                    <span class="pull-right"><?= isset($user->statistics->personalIncome) ? $user->statistics->personalIncome : 0 ?></span>

                                    <span class="label bg-primary">1</span>
                                    <?= THelper::t('personal_award') ?>
                                </li>
                                <li class="list-group-item">
                                    <span class="pull-right"><?= isset($user->statistics->structIncome) ? $user->statistics->structIncome : 0 ?></span>

                                    <span class="label bg-dark">2</span>
                                    <?= THelper::t('team_award') ?>
                                </li>
                                <li class="list-group-item">
                                    <span class="pull-right"><?= isset($user->statistics->mentorBonus) ? $user->statistics->mentorBonus : 0 ?></span>

                                    <span class="label bg-77382E">3</span>
                                    <?= THelper::t('mentor_bonus') ?>
                                </li>
                                <li class="list-group-item">
                                    <span class="pull-right"><?= isset($user->statistics->careerBonus) ? $user->statistics->careerBonus : 0 ?></span>

                                    <span class="label bg-009A8C">4</span>
                                    <?= THelper::t('career_bonus') ?>
                                </li>
                                <li class="list-group-item">
                                    <span class="pull-right"><?= isset($user->statistics->executiveBonus) ? $user->statistics->executiveBonus : 0 ?></span>

                                    <span class="label bg-AAA100">5</span>
                                    <?= THelper::t('executive_bonus') ?>
                                </li>
                                <li class="list-group-item">
                                    <span class="pull-right"><?= isset($user->statistics->worldBonus) ? $user->statistics->worldBonus : 0 ?></span>

                                    <span class="label bg-AA0900">6</span>
                                    <?= THelper::t('world_bonus') ?>
                                </li>
                            </ul>
                        </section>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <i class="fa fa-bookmark m-r-xs" aria-hidden="true"></i>
                                <span class="label label-danger label-pill pull-right"><?= isset($user->statistics->stocks) ? $user->statistics->stocks : 0 ?></span>

                                <?= THelper::t('shares_vipvip') ?>
                            </li>
                            <li class="list-group-item">
                                <i class="fa fa-bookmark m-r-xs" aria-hidden="true"></i>
                                <span class="label label-success label-pill pull-right"><?= isset($user->statistics->dividendsVIPVIP) ? $user->statistics->dividendsVIPVIP : 0 ?></span>

                                <?= THelper::t('dividends_vipvip') ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-8">
                        <h3><?= THelper::t('current_savings_balance_on_the_personal_account') ?>:</h3>
                        <div class="col-md-7 col-lg-5">
                            <div class="row ">
                                <div class="panel-footer bg-info text-center">
                                    <div class="row pull-out">
                                        <div class="col-xs-6">
                                            <div class="padder-v">
                                                <span class="m-b-xs h3 block text-white"><?= $user->pointsLeft ?></span>
                                                <small class="text-muted"><?= THelper::t('left_team') ?></small>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 dk">
                                            <div class="padder-v">
                                                <span class="m-b-xs h3 block text-white"><?= $user->pointsRight ?></span>
                                                <small class="text-muted"><?= THelper::t('right_team') ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?= Html::a(THelper::t('scoring_history'), ['point', 'u' => $user->username], ['class' => 'col-xs-12 btn btn-s-md btn-warning', 'style' => 'margin: 10px 0px;']); ?>

                                <div class="panel-footer text-center m-b">
                                    <div class="row pull-out">
                                        <div class="col-xs-6 bg-664CC1">
                                            <div class="padder-v">
                                                <span class="m-b-xs h3 block text-white"><?= isset($user->statistics->autoBonus) ? $user->statistics->autoBonus : 0 ?></span>

                                                <small class="text-white"><?= strip_tags($autoBonus) ?></small>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 bg-593FB5">
                                            <div class="padder-v">
                                                <span class="m-b-xs h3 block text-white"><?= isset($user->statistics->propertyBonus) ? $user->statistics->propertyBonus : 0 ?></span>

                                                <small class="text-white"><?= strip_tags($propertyBonus) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <header class="panel-heading font-bold"></header>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-8 text-left">
                                                <?= THelper::t('user_info_right_partners') ?>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <?= $user->rightSideNumberUsers ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 text-left">
                                                <?= THelper::t('user_info_left_partners') ?>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <?= $user->leftSideNumberUsers ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 text-left">
                                                <?= THelper::t('user_info_total_purchases') ?>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <?= $totalPurchases ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 text-left">
                                                <?= THelper::t('user_info_self_points') ?>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <?= $selfPoints ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 text-left">
                                                <?= THelper::t('user_info_self_invitations') ?>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <?= $user->statistics->personalPartners ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 text-left">
                                                <?= THelper::t('user_info_total_earned') ?>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <?= $user->statistics->structIncome + $user->statistics->personalIncome ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 text-left">
                                                <?= THelper::t('user_info_in_balance') ?>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <?= $user->moneys ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if (isset($user->sponsor) && $user->sponsor) { ?>
                        <div class="col-md-6">
                            <section class="panel panel-info">
                                <div class="panel-body">
                                    <a href="#" class="thumb pull-right m-l"><img
                                                src="<?= $user->sponsor->avatar ? $user->sponsor->avatar : '/images/avatar_default.png'; ?>"
                                                class="img-circle"></a>
                                    <div class="clear">
                                        <small class="block text-muted"><?= THelper::t('user_info_sponsor_login') . ': ' . $user->sponsor->username ?></small>
                                        <small class="block text-muted"><?= THelper::t('full_name') . ': ' ?><?= $user->sponsor->firstName ?> <?= $user->sponsor->secondName ?></small>
                                        <small class="block text-muted"><?= THelper::t('user_info_parent_birthday') . ': ' . gmdate('d.m.Y', $user->sponsor->created) ?></small>
                                        <small class="block text-muted"><?= THelper::t('user_info_sponsor_rank') . ': ' . THelper::t('rank_' . $user->sponsor->rank) ?></small>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>
                    <?php if ($parent) { ?>
                        <div class="col-md-6">
                            <section class="panel panel-info">
                                <header class="panel-heading font-bold"> <?= THelper::t('is under cell') ?></header>
                                <div class="panel-body">
                                    <a href="#" class="thumb pull-right m-l"><img
                                                src="<?= $parent->avatar ? $parent->avatar : '/images/avatar_default.png'; ?>"
                                                class="img-circle"></a>
                                    <div class="clear">
                                        <small class="block text-muted"><?= THelper::t('user_info_parent_login') . ': ' . $parent->username ?></small>
                                        <small class="block text-muted"><?= THelper::t('full_name') . ': ' ?> <?= $parent->firstName ?> <?= $parent->secondName ?></small>
                                        <small class="block text-muted"><?= THelper::t('user_info_parent_birthday') . ': ' . gmdate('d.m.Y', $parent->created) ?></small>
                                        <small class="block text-muted"><?= THelper::t('user_info_parent_rank') . ': ' . THelper::t('rank_' . $parent->rank) ?></small>
                                    </div>
                                </div>
                            </section>
                        </div>
                    <?php } ?>
                </div>
                <section class="panel panel-default m-b-20">
                    <header class="panel-heading font-bold"><?= THelper::t('personal_invitations') ?></header>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped datagrid m-b-sm table-self">
                                <thead>
                                <tr>
                                    <th class="sortable">
                                        <?= THelper::t('login') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('name') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('active_not_active') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('status') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('bs') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('partners') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('direction_registrations') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('country') ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($personalPartners) {
                                    foreach ($personalPartners as $personalPartner) { ?>
                                        <tr>
                                            <td>
                                                <?= $personalPartner->username ?>
                                            </td>
                                            <td>
                                                <?= $personalPartner->firstName ?>
                                            </td>
                                            <td>
                                                <?= ($personalPartner->rank > 0) ? THelper::t('yes') : THelper::t('no') ?>
                                            </td>
                                            <td>
                                                <?= THelper::t('rank_' . $personalPartner->rank) ?>
                                            </td>
                                            <td>
                                                <?= $personalPartner->expirationDateBS > 0 ? gmdate('d.m.Y', $personalPartner->expirationDateBS) : '-' ?>
                                            </td>
                                            <td>
                                                <?= THelper::t('left') ?>: <?= $personalPartner->leftSideNumberUsers ?>
                                                , <?= THelper::t('right') ?>
                                                : <?= $personalPartner->rightSideNumberUsers ?>
                                            </td>
                                            <td>
                                                <?= ($personalPartner->sideToNextUser == 1) ? THelper::t('left') : THelper::t('right') ?>
                                            </td>
                                            <td>
                                                <?php $personalPartnerCountry = $personalPartner->getCountry(); ?>
                                                <?= $personalPartnerCountry ? $personalPartnerCountry->name : '' ?>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <section class="panel panel-default">
                    <header class="panel-heading font-bold"><?= THelper::t('users_info_up_spilover') ?></header>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped datagrid m-b-sm table-up-spilover">
                                <thead>
                                <tr>
                                    <th class="sortable">
                                        <?= THelper::t('login') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('name') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('active_not_active') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('status') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('bs') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('partners') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('direction_registrations') ?>
                                    </th>
                                    <th class="sortable">
                                        <?= THelper::t('country') ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if ($upSpilovers) {
                                    foreach ($upSpilovers as $upSpilover) { ?>
                                        <tr>
                                            <td>
                                                <?= $upSpilover->username ?>
                                            </td>
                                            <td>
                                                <?= $upSpilover->firstName ?>
                                            </td>
                                            <td>
                                                <?= ($upSpilover->rank > 0) ? THelper::t('yes') : THelper::t('no') ?>
                                            </td>
                                            <td>
                                                <?= THelper::t('rank_' . $upSpilover->rank) ?>
                                            </td>
                                            <td>
                                                <?= $upSpilover->expirationDateBS > 0 ? gmdate('d.m.Y', $upSpilover->expirationDateBS) : '-' ?>
                                            </td>
                                            <td>
                                                <?= THelper::t('left') ?>: <?= $upSpilover->leftSideNumberUsers ?>
                                                , <?= THelper::t('right') ?>: <?= $upSpilover->rightSideNumberUsers ?>
                                            </td>
                                            <td>
                                                <?= ($upSpilover->sideToNextUser == 1) ? THelper::t('left') : THelper::t('right') ?>
                                            </td>
                                            <td>
                                                <?php $upSpiloverCountry = $upSpilover->getCountry(); ?>
                                                <?= $upSpiloverCountry ? $upSpiloverCountry->name : '' ?>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <script>
                    var months = {
                        "01": "<?=tHelper::t('january')?>",
                        "02": "<?=tHelper::t('february')?>",
                        "03": "<?=tHelper::t('march')?>",
                        "04": "<?=tHelper::t('april')?>",
                        "05": "<?=tHelper::t('may')?>",
                        "06": "<?=tHelper::t('june')?>",
                        "07": "<?=tHelper::t('july')?>",
                        "08": "<?=tHelper::t('august')?>",
                        "09": "<?=tHelper::t('september')?>",
                        "10": "<?=tHelper::t('october')?>",
                        "11": "<?=tHelper::t('november')?>",
                        "12": "<?=tHelper::t('december')?>"
                    }
                    var labelPaid = "<?= tHelper::t('paid') ?>";
                    var labelRegistrations = "<?= tHelper::t('registrations') ?>";
                    var registrationsStatisticsPerMoths = <?= $registrationsStatisticsPerMoths ?>

                    var graphOptions = {
                        series: {
                            lines: {
                                show: true,
                                lineWidth: 1,
                                fill: true,
                                fillColor: {
                                    colors: [{
                                        opacity: 0.2
                                    }, {
                                        opacity: 0.1
                                    }]
                                }
                            },
                            points: {
                                show: true
                            },
                            shadowSize: 2
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#f0f0f0",
                            borderWidth: 0
                        },
                        colors: ["#dddddd", "#89cb4e"],
                        xaxis: {
                            ticks: null
                        },
                        yaxis: {
                            ticks: 10,
                            tickDecimals: 0,
                            min: 0
                        },
                        tooltip: true,
                        tooltipOpts: {
                            content: "%y.4 %s",
                            defaultTheme: false,
                            shifts: {
                                x: 0,
                                y: 20
                            }
                        }
                    };

                    var floatChart = $("#flot-chart");

                    var showRegistrationsStatisticsPerMoths = (function () {
                        var data = registrationsStatisticsPerMoths;
                        floatChart.height(240);
                        var d2 = [];
                        for (var i = 0; i < data.length; i++) {
                            d2.push([i, parseInt(data[i]['paid'])]);
                        }
                        var d3 = [];
                        for (var i = 0; i < data.length; i++) {
                            d3.push([i, parseInt(data[i]['registrations'])]);
                        }
                        var dates = [];
                        for (var i = 0; i < data.length; i++) {
                            var date_splited = data[i]['date'].split('/');
                            var date;
                            if (window.innerWidth >= 768) {
                                date = months[date_splited[0]];
                            } else {
                                date = date_splited[0];
                            }
                            dates.push([i, date]);
                        }
                        graphOptions.xaxis.ticks = dates;
                        floatChart.length && $.plot(floatChart, [{
                                data: d2,
                                label: labelPaid
                            }, {
                                data: d3,
                                label: labelRegistrations
                            }],
                            graphOptions
                        );
                    })();

                    // easypie
                    $('.easypiechart').each(function () {
                        var $this = $(this),
                            $data = $this.data(),
                            $step = $this.find('.step'),
                            $target_value = parseInt($($data.target).text()),
                            $value = 0;
                        $data.barColor || ($data.barColor = function ($percent) {
                            $percent /= 100;
                            return "rgb(" + Math.round(200 * $percent) + ", 200, " + Math.round(200 * (1 - $percent)) + ")";
                        });
                        $data.onStep = function (value) {
                            $value = value;
                            $step.text(parseInt(value));
                            $data.target && $($data.target).text(parseInt(value) + $target_value);
                        }
                        $data.onStop = function () {
                            $target_value = parseInt($($data.target).text());
                            $data.update && setTimeout(function () {
                                $this.data('easyPieChart').update(100 - $value);
                            }, $data.update);
                        }
                        $(this).easyPieChart($data);
                    });

                    $('.table-self').dataTable({
                        language: TRANSLATION,
                        lengthMenu: [25, 50, 75, 100]
                    });

                    $('.table-up-spilover').dataTable({
                        language: TRANSLATION,
                        lengthMenu: [25, 50, 75, 100]
                    });

                </script>
            </div>

            <div role="tabpanel" class="tab-pane" id="accrued_commissions">
                <?= $this->render('/finance/operations', [
                    'user' => $operations,
                    'currentUser' => $parent
                ]); ?>
            </div>

            <div role="tabpanel" class="tab-pane" id="history_of_scoring_points">
                <?= $this->render('/finance/points', [
                    'user' => $points
                ]); ?>
            </div>
        </div>
    </div>


<?php } else { ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-ban-circle"></i><?= THelper::t('user_info_not_found') ?></div>
<?php } ?>