<?php
    use app\components\THelper;
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-results">
                <thead>
                <tr>
                    <th><?= THelper::t('academy_vip_vip_table_username') ?></th>
                    <th><?= THelper::t('academy_vip_vip_table_firstname') ?></th>
                    <th><?= THelper::t('academy_vip_vip_table_secondname') ?></th>
                    <th><?= THelper::t('academy_vip_vip_table_country') ?></th>
                    <th><?= THelper::t('academy_vip_vip_table_city') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($academyVipVipUsers as $academyVipVipUser) { ?>
                    <tr>
                        <td>
                            <?= $academyVipVipUser->username ?>
                        </td>
                        <td>
                            <?= $academyVipVipUser->firstName ?>
                        </td>
                        <td>
                            <?= $academyVipVipUser->secondName ?>
                        </td>
                        <td>
                            <?= $academyVipVipUser->country ?>
                        </td>
                        <td>
                            <?= $academyVipVipUser->city ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $('.table').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });
</script>