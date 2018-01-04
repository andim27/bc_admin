<?php
    use app\components\THelper;
    use yii\helpers\Url;
    $this->title = THelper::t('status');
    $this->params['breadcrumbs'][] = $this->title;
?>
<section>
    <div class="pull-left" style="padding-bottom: 10px">
        <div class="panel panel-body">
            <div class="thumb pull-left m-r">
                <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png'; ?>" class="img-circle">
            </div>
            <div class="clear">
                <h4 class="user_id"><?=(isset($user->username)) ? $user->username : '';?></h4>
                <small class="block"><?=THelper::t('status')?>: <?=THelper::t('rank_' . $user->rank)?></small>
            </div>
        </div>
        <a class="btn btn-s-md btn-danger btn-rounded col-xs-12" href="<?= Url::to('/business/information/carrier'); ?>"><?=THelper::t('description_of_career_plan')?></a>
    </div>
    <div class="panel-body pull-right">
        <div class="thumb pull-left m-r">
            <img class="thumb pull-left m-r" src="/images/ranks/rank_<?=$user->rank?>.png" class="img-circle">
        </div>
    </div>
    <div style="clear: both"></div>
    <section class="panel panel-default">
        <header class="panel-heading bg-light">
            <ul class="nav nav-tabs ">
                <li class="active"><a href="#status_history" data-toggle="tab"><?=THelper::t('status_history')?></a></li>
                <li><a href="#deciphering" data-toggle="tab"><?=THelper::t('deciphering_crankcase_status_of_the_first_line')?></a></li>
            </ul>
        </header>
        <div class="panel-body">
            <div class="tab-content">

                <div class="tab-pane active conteiner_tab" id="status_history">
                    <section class="panel panel-default">
                        <div class="table-responsive ajax">
                            <table id="datatable-t" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                                <thead>
                                <tr>
                                    <th><?=THelper::t('carrier_status')?></th>
                                    <th><?=THelper::t('period_closing')?></th>
                                    <th><?=THelper::t('steps')?></th>
                                    <th><?=THelper::t('the_amount_of_bonus')?></th>
                                    <th><?=THelper::t('until_the_end_of_the_period')?></th>
                                    <th><?=THelper::t('premium')?></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historyStatuses as $historyStatus) { ?>
                                    <tr>
                                        <td><?= THelper::t('rank_' . $historyStatus->rank) ?></td>
                                        <td><?= $historyStatus->closingPeriod ?></td>
                                        <td><?=THelper::t('must')?> <?= $historyStatus->stepsMust ?> <?=THelper::t('must_also')?> <?= $historyStatus->stepsMustAlso ?></td>
                                        <td><?= $historyStatus->bonus ?></td>
                                        <td><?= $historyStatus->getUntilEndString() ?></td>
                                        <td><?= $historyStatus->payed ? THelper::t('yes') : THelper::t('no'); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
                <div class="tab-pane conteiner_tab" data-url="shares-step" id="deciphering">
                    <section class="panel panel-default">
                        <div class="table-responsive">
                            <table id="datatable-t1" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                                <thead>
                                <tr>
                                    <th><?=THelper::t('account_number_gn')?></th>
                                    <th><?=THelper::t('login')?></th>
                                    <th><?=THelper::t('position_in_the_structure')?></th>
                                    <th><?=THelper::t('status')?></th>
                                    <th><?=THelper::t('business_support')?></th>
                                    <th><?=THelper::t('step')?></th>
                                    <th><?=THelper::t('email')?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($firstSteps as $firstStep) { ?>
                                    <tr>
                                        <td><?= $firstStep->accountId ?></td>
                                        <td><?= $firstStep->username ?></td>
                                        <td><?= $firstStep->side == 0 ? THelper::t('right') : THelper::t('left'); ?></td>
                                        <td><?= THelper::t('rank_' . $firstStep->rank) ?></td>
                                        <td><?= $firstStep->bs ? THelper::t('yes') : THelper::t('no'); ?></td>
                                        <td><?= $firstStep->steps ?></td>
                                        <td><?= $firstStep->email ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</section>
<?php $this->registerJsFile('/js/main/initialization.js'); ?>