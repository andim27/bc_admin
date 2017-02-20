<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('users_title'); ?></h3>
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
                        <?=THelper::t('user_deleted')?>
                    </th>
                    <th>
                        <?=THelper::t('user_fname_sname')?>
                    </th>
                    <th>
                        <?=THelper::t('user_rank')?>
                    </th>
                    <th>
                        <?=THelper::t('user_country_city')?>
                    </th>
                    <th>
                        <?=THelper::t('user_parent_login')?>
                    </th>
                    <th>
                        <?=THelper::t('user_parent_fname_sname')?>
                    </th>
                    <th>
                        <?=THelper::t('user_edit_profile')?>
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
                            <?= 'В структуре / Удален' ?>
                        </td>
                        <td>
                            <?= $user->firstName ?> <?= $user->secondName ?>
                        </td>
                        <td>
                            <?= THelper::t('rank_' . $user->rank) ?>
                        </td>
                        <td>
                            <?= $user->getCountryCityAsString() ?>
                        </td>
                        <td>
                            <?= $user->sponsor ? $user->sponsor->username : '' ?>
                        </td>
                        <td>
                            <?= $user->sponsor ? $user->sponsor->firstName : '' ?> <?= $user->sponsor ? $user->sponsor->secondName : '' ?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/user', 'u' => $user->username]) ?>
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
        lengthMenu: [25, 50, 75, 100],
        bSort: false
    });

    var limit = 500;
    var i = 1;

    function load(offset) {
        i++;
        $.ajax({
            url: '/' + LANG + '/business/user/load',
            method: 'GET',
            data: {
                offset: offset
            },
            success: function(data) {
                if (data.length > 0) {
                    table.fnAddData(data);
                    load(limit * i);
                }
            }
        });
    }

    load(limit);
</script>