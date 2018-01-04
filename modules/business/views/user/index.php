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
                        <?=THelper::t('user_country_city')?>
                    </th>
                    <th>
                        <?=THelper::t('user_parent_login')?>
                    </th>
                    <th>
                        <?=THelper::t('user_parent_fname_sname')?>
                    </th>
                    <th>
                        <?=THelper::t('user_rank')?>
                    </th>
                    <th>
                        <?=THelper::t('user_edit_profile')?>
                    </th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</section>

<script>
    var table = $('.table-users');

    table = table.dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/' + LANG + '/business/user'
        },
        "columns": [
            {"data": "accountId"},
            {"data": "username"},
            {"data": "created"},
            {"data": "structure_status"},
            {"data": "full_name"},
            {"data": "country_city"},
            {"data": "sponsor_username"},
            {"data": "sponsor_full_name"},
            {"data": "rank"},
            {"data": "action"}
        ],
        "order": [[ 5, "desc" ]]
    });

</script>