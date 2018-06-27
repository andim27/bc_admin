<?php
    use app\components\THelper;
use yii\helpers\Html;

$this->title = THelper::t('history_of_operations');
    $this->params['breadcrumbs'][] = $this->title;
?>
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
                        <th width="20%"><?=THelper::t('action')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($user)){
                        foreach ($user as $value){ ?>
                            <tr>
                                <th width="20%"><?= $value->usernameFrom ?></th>
                                <th width="20%"><?= $value->usernameTo ?></th>
                                <th width="20%"><?= round($value->amount,2); ?></th>
                                <th width="20%"><?= round($value->saldoFrom,2); ?></th>
                                <th width="20%"><?= $value->forWhat ?></th>
                                <th width="20%"><?= gmdate('d.m.Y', date('U', strtotime($value->dateReduce))) ?></th>
                                <th width="20%">
                                    <?= Html::a(THelper::t('cancel_operation'), 'transaction-cancel?id=' . $value->_id, ['data-toggle'=>'ajaxModal', 'class'=>'btn btn-facebook']); ?>
                                </th>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
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
