<?php
    use app\components\THelper;
    use yii\helpers\Html;
use MongoDB\BSON\UTCDatetime;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('users_purchase_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="input-group m-b">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control search-purchase-user" placeholder="<?= THelper::t('users_purchase_search_placeholder') ?>">
        </div>
    </div>
    <div class="col-md-1 m-b">
        <a href="javascript:void(0);" class="btn btn-info search-purchase"><?= THelper::t('users_purchase_search') ?></a>
    </div>
    <div class="col-md-8 text-right">
        <?php //= Html::a(THelper::t('users_purchase_add'), ['/business/user/add-purchase'], ['data-toggle'=>'ajaxModal', 'class'=>'btn btn-danger add-purchase']); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#by-user" class="tab-by-user" data-toggle="tab"><?= THelper::t('users_purchase_by_user') ?></a>
                    </li>
                    <li class="">
                        <a href="#by-all" class="tab-by-all" data-toggle="tab"><?= THelper::t('users_purchase_by_all') ?></a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="by-user">
                        <div class="progress progress-sm progress-striped active" style="display: none;"> <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div> </div>
                        <div id="table-content"></div>
                    </div>
                    <div class="tab-pane" id="by-all">
                        <div class="table-responsive">
                            <table class="table table-striped datagrid m-b-sm table-by-all" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>
                                            <?= THelper::t('users_purchase_date') ?>
                                        </th>
                                        <th>
                                            <?= THelper::t('users_purchase_code') ?>
                                        </th>
                                        <th>
                                            <?= THelper::t('users_purchase_name') ?>
                                        </th>
                                        <th>
                                            <?= THelper::t('users_purchase_price') ?>
                                        </th>
                                        <th>
                                            <?= THelper::t('users_purchase_point') ?>
                                        </th>
                                        <th>
                                            <?= THelper::t('users_purchase_user_login') ?>
                                        </th>
                                        <th>
                                            <?= THelper::t('users_purchase_user_fname_sname') ?>
                                        </th>
                                        <th>
                                            <?= THelper::t('users_purchase_cancellation') ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>


    var table = $('.table-by-all');
    table = table.dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/' + LANG + '/business/user/purchase'
        },
        "columns": [
            {"data": "dateCreate"},
            {"data": "product"},
            {"data": "productName"},
            {"data": "price"},
            {"data": "bonusPoints"},
            {"data": "username"},
            {"data": "firstName_secondName"},
            {"data": "action"}
        ],
        "order": [[ 0, "desc" ]]
    });

    $('.search-purchase').click(function() {
        if ($('.search-purchase-user').val()) {
            $('.tab-by-user').click();
            $('#table-content').html('');
            $('.progress').show();
            $.ajax({
                url: '/' + LANG + '/business/user/get-purchase',
                method: 'GET',
                data: {
                    u: $('.search-purchase-user').val()
                },
                success: function (data) {
                    if (data) {
                        $('#table-content').html(data);
                    }
                    $('.progress').hide();
                }
            });
        }
    });

    function confirmCancellation() {
        if (confirm("<?= THelper::t('user_purchase_confirm_cancellation') ?>")) {
            return true;
        } else {
            return false;
        }
    }
</script>