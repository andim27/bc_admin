<?php
    use app\components\THelper;
?>
<section class="panel panel-default">
    <div class="panel-body">
        <table class="table table-users table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('id')?>
                </th>
                <th>
                    <?=THelper::t('surname')?>
                </th>
                <th>
                    <?=THelper::t('name')?>
                </th>
                <th>
                    <?=THelper::t('country')?>
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
                    <?=THelper::t('activity_date')?>
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
        "paging": true,
        lengthMenu: [ 25, 50, 75, 100 ],
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/' + LANG + '/business/wellness-club-members'
        },
        "columns": [
            {"data": "id"},
            {"data": "surname"},
            {"data": "name"},
            {"data": "countryId"},
            {"data": "address"},
            {"data": "mobile"},
            {"data": "email"},
            {"data": "skype"},
            {"data": "created"},
            {"data": "action"}
        ],
        "order": [[ 0, "desc" ]]
    });

    $(document).on('click', '.apply', function () {

        var $this = $(this);

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['wellness-club-members/apply'])?>',
            type: 'POST',
            data: {
                email: $this.data('email')
            },
            success: function (response) {
                if (response) {
                    location.reload();
                }
            }
        });
    });

</script>