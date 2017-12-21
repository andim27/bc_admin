<?php
    use app\components\THelper;
    $this->title = THelper::t('personal_invitations');
    $this->params['breadcrumbs'][] = $this->title;
?>
<section class="panel panel-default">
<div class="panel-body">
    <div class="tab-content">
        <div class="tab-pane active" id="buy">
            <section class="panel panel-default">
                <div class="table-responsive">
                    <table id="MyStretchGridSelf" class="table table-striped datagrid m-b-sm unique_table_class">
                        <thead>
                        <tr>
                            <th class="sortable">
                                <?=THelper::t('№')?>
                            </th>
                            <th class="sortable">
                               <?=THelper::t('login')?>
                            </th>
                            <th class="sortable">
                                <?=THelper::t('name')?>
                            </th>
                            <th class="sortable">
                                <?=THelper::t('active_not_active')?>
                            </th>
                            <th class="sortable">
                                <?=THelper::t('status')?>
                            </th>
                            <th class="sortable">
                                <?=THelper::t('bs')?>
                            </th>
                            <th class="sortable">
                                <?=THelper::t('partners')?>
                            </th>
                            <th class="sortable">
                                <?=THelper::t('direction_registrations')?>
                            </th>
                            <th class="sortable">
                                <?=THelper::t('country')?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        <?php if ($personalPartners) {
                            foreach ($personalPartners as $personalPartner) { ?>
                            <tr class = "st children" data-id = "<?= $personalPartner->id ?>">
                                <td>
                                    <?= $i ?>
                                </td>
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
                                    <?= THelper::t('left') ?>: <?= $personalPartner->leftSideNumberUsers ?>, <?= THelper::t('right') ?>: <?= $personalPartner->rightSideNumberUsers ?>
                                </td>
                                <td>
                                    <?= ($personalPartner->sideToNextUser == 1) ? THelper::t('left') : THelper::t('right') ?>
                                </td>
                                <td>
                                    <?php $personalPartnerCountry = $personalPartner->getCountry(); ?>
                                    <?= $personalPartnerCountry ? $personalPartnerCountry->name : '' ?>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
</section>
<?php echo $this->render('context_menu');?>
<?php $this->registerJsFile('/js/main/initialization.js'); ?>
