<?php
    use app\components\THelper;
    use app\assets\DateTableAsset;
    DateTableAsset::register($this);
?>
<div class="modal-dialog" style="width: 90%">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= THelper::t('pincode_history') ?></h4>
        </div>
        <div class="modal-body">
            <div class="row" style="margin-bottom: 25px">
                <div class="col-xs-12">
                    <table class="table table-striped m-b-none pins-table">
                        <thead>
                        <tr>
                            <th><?= THelper::t('pincode_history_date') ?></th>
                            <th><?= THelper::t('pincode_history_product_name') ?></th>
                            <th><?= THelper::t('pincode_history_product_price') ?></th>
                            <th><?= THelper::t('pincode_history_pin') ?></th>
                            <th><?= THelper::t('pincode_history_used') ?></th>
                            <th><?= THelper::t('pincode_history_invoice') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pins as $i => $pin) { ?>
                                <tr>
                                    <td><?= gmdate('d.m.Y', $pin->dateUpdate) ?></td>
                                    <td><?= $pin->productName ?></td>
                                    <td><?= $pin->productPrice ?></td>
                                    <td><?= $pin->pin ?></td>
                                    <td><?= ($pin->used || $pin->isActivate) ? THelper::t('pincode_history_used_true') : THelper::t('pincode_history_false') ?></td>
                                    <th style="text-align: center"><a href="/<?=Yii::$app->language?>/business/finance/pincode-info?pin=<?=$pin->pin?>&number=<?= 1000 + $i ?>"><i class="fa fa-file-text-o" aria-hidden="true" title="<?=THelper::t('download')?>"></i></a></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
            } else {
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
    $('table.pins-table').dataTable({
        scrollX: true,
        columnDefs: [
            {type: 'date-eu', targets: 0}
        ],
        language: TRANSLATION,
        sDom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        lengthMenu: [ 25, 50, 75, 100 ],
        aaSorting: [0, 'desc']
    });
</script>