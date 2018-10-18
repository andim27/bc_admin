<?php
use app\components\THelper;
use MongoDB\BSON\UTCDatetime;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_users_cancel_sale_log') ?></h3>
</div>
<div class="row">
    <section class="panel panel-default">
        <div class="table-responsive">
            <table id="table-cancel-sale-log" class="table  table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?= THelper::t('sale_product_name') ?>
                    </th>
                    <th>
                        <?= THelper::t('price') ?>
                    </th>
                    <th>
                        <?= THelper::t('date_cancel') ?>
                    </th>
                    <th>
                        <?= THelper::t('user_login') ?>
                    </th>
                    <th>
                        <?= THelper::t('who_cancel') ?>
                    </th>

                </tr>
                </thead>
                <tbody>

                <?php foreach ($cancelSales as $cancelSale) {

                    ?>
                    <tr>
                        <td width="25%"><?= $cancelSale->productName ?></td>
                        <td><?= $cancelSale->price ?></td>
                        <td><?= (isset($cancelSale->updated_at)?$cancelSale->updated_at->toDateTime()->format('d.m.Y  H:i:s'):'??') ?></td>
                        <td>
                            <?php $uid_view =uniqid(); ?>
                            <button type="button" class="btn btn-link"  onclick="getUserData('<?= $cancelSale->username ?>','<?=@$uid_view ?>')" ><?= $cancelSale->username ?></button>
                            <div id="user-view-<?=@$uid_view ?>" style="display:none"></div>
                        </td>
                        <td>
                            <?= (isset($cancelSale->comment_user_name)?$cancelSale->comment_user_name:'??') ?><br>
                            <?= (isset($cancelSale->comment)?(THelper::t('reason').': '.$cancelSale->comment):'') ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('#table-cancel-sale-log').dataTable({
            language: TRANSLATION,
            lengthMenu: [50, 50, 75, 100],
           // aaSorting: [2, 'asc']
        });
    });
    function showUserDataView(uid_view,data) {
        var user_data_html='<p><strong>Fio: </strong>'+data.fio+'</p>';
        if (data.email !=undefined) {
            user_data_html=user_data_html+'<p><strong>Email: </strong>'+data.email+'</p>';
        }
        if (data.skype !=undefined) {
            user_data_html=user_data_html+'<p><strong>Skype: </strong>'+data.skype+'</p>';
        }

        $('#user-view-'+uid_view).html(user_data_html).show();
    }
    function getUserData(login,uid_view) {
        var url="/<?=Yii::$app->language?>/business/user/get-user-data";
        $.post(url,{'login':login}).done(function (data) {
            if (data.success == true) {
                showUserDataView(uid_view,data.data);
            } else {
                console.log('Error:getUserData login='+login);
            }
        });
    }
</script>