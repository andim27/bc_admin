<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('wellness_club_members'); ?></h3>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-users table-striped datagrid m-b-sm">
            <thead>
                <tr>
                    <th>
                        <?=THelper::t('surname')?>
                    </th>
                    <th>
                        <?=THelper::t('name')?>
                    </th>
                    <th>
                        <?=THelper::t('middleName')?>
                    </th>
                    <th>
                        <?=THelper::t('country')?>
                    </th>
                    <th>
                        <?=THelper::t('state')?>
                    </th>
                    <th>
                        <?=THelper::t('city')?>
                    </th>
                    <th>
                        <?=THelper::t('address')?>
                    </th>
                    <th>
                        <?=THelper::t('mobile')?>
                    </th>
                    <th>
                        <?=THelper::t('email')?>
                    </th>
                    <th>
                        <?=THelper::t('skype')?>
                    </th>
                    <th>
                        <?=THelper::t('created')?>
                    </th>
                    <th>
                        <?=THelper::t('action')?>
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
            "url": '/' + LANG + '/business/wellness-club-members'
        },
        "columns": [
            {"data": "surname"},
            {"data": "name"},
            {"data": "middleName"},
            {"data": "countryId"},
            {"data": "state"},
            {"data": "city"},
            {"data": "address"},
            {"data": "mobile"},
            {"data": "email"},
            {"data": "skype"},
            {"data": "created"},
            {"data": "action"}
        ],
        "order": [[ 5, "desc" ]]
    });

</script>