<?php
use app\components\THelper;
use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('users_qualification'); ?></h3>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-users table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('user_account_id')?>
                </th>
                <th>
                    <?=THelper::t('user_login')?>
                </th>
                <th>
                    <?=THelper::t('user_created')?>
                </th>
                <th>
                    <?=THelper::t('user_fname_sname')?>
                </th>
                <th>
                    <?=THelper::t('user_country_city')?>
                </th>
                <th>
                    <?=THelper::t('user_rank')?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $key => $user) { ?>
                <tr>
                    <td>
                        <?= $user->accountId ?>
                    </td>
                    <td>
                        <?= $user->username ?>
                    </td>
                    <td>
                        <?= gmdate('d.m.Y', $user->created) ?>
                    </td>
                    <td>
                        <?= $user->firstName ?> <?= $user->secondName ?>
                    </td>
                    <td>
                        <?= $user->getCountryCityAsString() ?>
                    </td>
                    <td>
                        <?= $user->rankString ? $user->rankString : '' ?>
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
</script>