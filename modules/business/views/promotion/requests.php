<?php use app\components\THelper; ?>
<div class="row">
    <div class="col-md-12">
        <h3><?= THelper::t('promotion_requests_title'); ?></h3>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
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
                        <th><?= THelper::t('promotion_requests_date') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($requests as $i => $request) { ?>
                        <tr>
                            <td>
                                <?= ++$i ?>
                            </td>
                            <td>
                                <?= $request->username ?>
                            </td>
                            <td>
                                <?= $request->firstName ?>
                            </td>
                            <td>
                                <?= $request->secondName ?>
                            </td>
                            <td>
                                <?= $request->country ?>
                            </td>
                            <td>
                                <?= $request->city ?>
                            </td>
                            <td>
                                <?= $request->created_at->toDateTime()->format('d.m.Y') ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<script>
    $('.table').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });
</script>