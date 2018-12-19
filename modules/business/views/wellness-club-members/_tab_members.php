<?php
    use app\components\THelper;
?>
<section class="panel panel-default">
    <div class="panel-body">
        <div class="row center-block">
            <div class="col-sm-3 col-sm-offset-4">
                <form class="form-inline" action="<?= \yii\helpers\Url::to(['wellness-club-members/apply']) ?>">
                    <div class="form-group">
                        <label for="ch"><?= THelper::t('unconfirmed_claims'); ?></label>
                        <?php  if ($ch == 1) { ?>
                             <input type="checkbox" checked class="form-control" id="ch" name="ch">
                        <?php } else { ?>

                            <input type="checkbox"  class="form-control" id="ch" name="ch">
                        <?php } ?>
                    </div>
                </form>
            </div>

        </div>
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
    <?php  if (Yii::$app->request->get('ch') == 1) { ?>
    c_m_empty = 1;
    <?php } else {?>
    c_m_empty = 0;
    <?php } ?>
    table = table.dataTable({
        language: TRANSLATION,
        "paging": true,
        lengthMenu: [ 25, 50, 75, 100 ],
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/' + LANG + '/business/wellness-club-members',
            "data": function ( d ) {
                d.ch = c_m_empty;
            }
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
            {"data": "wellness_club_partner_date_end"},
            {"data": "action"}
        ],
        "order": [[ 0, "desc" ]]
    });

    $(document).on('click', '.apply', function () {

        var $this = $(this);

        $.ajax({
            url: '<?= \yii\helpers\Url::to(['wellness-club-members/apply']) ?>',
            type: 'POST',
            data: {
                userId: $this.data('id')
            },
            success: function (response) {
                if (response) {
                    location.reload();
                }
            }
        });
    });

    $('#ch').click(function () {
        if (document.getElementById('ch').checked) {
            window.location.href ='/' + LANG + '/business/wellness-club-members?ch=1';
        } else {
            window.location.href ='/' + LANG + '/business/wellness-club-members?ch=0';
        }
    });
</script>