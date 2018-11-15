<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('user_commission_title'); ?></h3>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="input-group m-b">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control search_text" placeholder="<?= THelper::t('user_commission_search_placeholder') ?>">
        </div>
    </div>
    <div class="col-sm-1 m-b">
        <a href="#" class="btn btn-s-md btn-info search_login"><?= THelper::t('user_commission_search') ?></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#by-movement-account" class="tab-movement-account" data-toggle="tab"><?= THelper::t('user_commission_movement_account') ?></a>
                    </li>
                    <li class="">
                        <a href="#by-user" class="tab-by-user" data-toggle="tab"><?= THelper::t('user_commission_by_user') ?></a>
                    </li>
                    <li class="">
                        <a href="#by-movement-account-all" class="tab-by-movement-account-all" data-toggle="tab"><?= THelper::t('user_commission_by_movement_account_all') ?></a>
                    </li>
                    <li class="">
                        <a href="#by-all" class="tab-by-all" data-toggle="tab"><?= THelper::t('user_commission_by_all') ?></a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="by-movement-account">
                        <div class="row">
                            <div class="col-xs-12">
                                <section class="panel panel-default">
                                    <header class="panel-heading">
                                        <?=THelper::t('operations_history')?>
                                        <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                                    </header>
                                    <div class="table-responsive">
                                        <table id="for_withdrawal" class="table table-striped m-b-none unique_table_class">
                                            <thead>
                                            <tr>
                                                <th width="20%"><?=THelper::t('from')?></th>
                                                <th width="20%"><?=THelper::t('to')?></th>
                                                <th width="20%"><?=THelper::t('amount')?></th>
                                                <th width="20%"><?=THelper::t('finance_operations_saldo_from')?></th>
                                                <th width="20%"><?=THelper::t('for_what')?></th>
                                                <th width="20%"><?=THelper::t('date')?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if (!empty($user)) {
                                                foreach ($user as $value) {
                                            ?>
                                                <tr>
                                                    <th width="20%"><?= $value->usernameFrom ?? '??'  ?></th>
                                                    <th width="20%"><?= $value->usernameTo ?? '??' ?></th>
                                                    <th width="20%"><?= $value->amount ?? '??' ?></th>
                                                    <th width="20%"><?= $value->saldoFrom ?? '??' ?></th>
                                                    <th width="20%"><?= $value->forWhat ?? '??' ?></th>
                                                    <th width="20%"><?= !empty($value->dateReduce) ? gmdate('d.m.Y', date('U', strtotime($value->dateReduce))) : '' ?></th>
                                                </tr>
                                            <?php }} ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="by-user">

                    </div>
                    <div class="tab-pane" id="by-movement-account-all">

                    </div>
                    <div class="tab-pane" id="by-all">

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "date-eu-pre": function ( date ) {
            date = date.replace(" ", "");
            if ( ! date ) {
                return 0;
            }
            var year;
            var eu_date = date.split(/[\.\-\/]/);
            if ( eu_date[2] ) {
                year = eu_date[2];
            }
            else {
                year = 0;
            }
            var month = eu_date[1];
            if ( month.length == 1 ) {
                month = 0+month;
            }
            var day = eu_date[0];
            if ( day.length == 1 ) {
                day = 0+day;
            }
            return (year + month + day) * 1;
        },
        "date-eu-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },
        "date-eu-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );
    $('table.unique_table_class').dataTable({
        columnDefs: [
            {type: 'date-eu', targets: 5}
        ],
        language: TRANSLATION,
        sDom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        lengthMenu: [ 25, 50, 75, 100 ],
        aaSorting: [5, 'desc']
    });
</script>