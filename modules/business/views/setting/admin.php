<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('setting_admin_title'); ?></h3>
</div>
<div class="row m-b">
    <div class="col-md-12 text-right">
        <?= Html::a(THelper::t('setting_admin_add'), ['/business/setting/add-admin'], ['data-toggle'=>'ajaxModal', 'class'=>'btn btn-info']) ?>
    </div>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-users table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('setting_admin_account_id')?>
                </th>
                <th>
                    <?=THelper::t('setting_admin_login')?>
                </th>
                <th>
                    <?=THelper::t('setting_admin_created')?>
                </th>
                <th>
                    <?=THelper::t('setting_admin_fname_sname')?>
                </th>
                <th>
                    <?=THelper::t('setting_admin_rank')?>
                </th>
                <th>
                    <?=THelper::t('setting_admin_country_city')?>
                </th>
                <th>
                    <?=THelper::t('warehouse')?>
                </th>
                <th>
                    <?=THelper::t('setting_admin_remove')?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($admins as $admin) { ?>
                <tr>
                    <td>
                        <?= $admin->accountId ?>
                    </td>
                    <td>
                        <?= $admin->username ?>
                    </td>
                    <td>
                        <?= gmdate('d.m.Y', $admin->created) ?>
                    </td>
                    <td>
                        <?= $admin->firstName ?> <?= $admin->secondName ?>
                    </td>
                    <td>
                        <?= THelper::t('rank_' . $admin->rank) ?>
                    </td>
                    <td>
                        <?= $admin->getCountryCityAsString() ?>
                    </td>
                    <td>

                        <?= $admin->username != 'main' ? Html::a('<i class="fa fa-home"></i>', ['/business/setting/admin-warehouse-update', 'username' => $admin->username], ['title'=>'Склад']) : '' ?>
                    </td>
                    <td>
                        <?= $admin->username != 'main' ? Html::a('<i class="fa fa-trash-o"></i>', ['/business/setting/admin-remove', 'u' => $admin->username], ['onclick' => 'return confirmRemoving();','title'=>THelper::t('setting_admin_remove')]) : '' ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    var table = $('.table-users').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });
    function confirmRemoving() {
        if (confirm("<?= THelper::t('setting_admin_confirm_removing') ?>")) {
            return true;
        } else {
            return false;
        }
    }
</script>