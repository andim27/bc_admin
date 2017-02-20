<?php
    use app\components\THelper;
    $this->title = THelper::t('sale');
    $this->params['breadcrumbs'][] = $this->title;
?>
<section class="panel panel-default">
    <div class="panel-body">
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped m-b-none sales-table">
                    <thead>
                        <tr>
                            <th><?= THelper::t('sale_date_create') ?></th>
                            <th><?= THelper::t('sale_product') ?></th>
                            <th><?= THelper::t('sale_product_name') ?></th>
                            <th><?= THelper::t('sale_bonus_points') ?></th>
                            <th><?= THelper::t('sale_price') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale) { ?>
                            <tr>
                                <td><?= gmdate('d.m.Y', $sale->dateCreate) ?></td>
                                <td><?= $sale->product ?></td>
                                <td><?= $sale->productName ?></td>
                                <td><?= $sale->bonusPoints ?></td>
                                <td><?= $sale->price ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</section>
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
    $('table.sales-table').dataTable({
        columnDefs: [
            {type: 'date-eu', targets: 0}
        ],
        language: TRANSLATION,
        sDom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        lengthMenu: [ 25, 50, 75, 100 ],
        aaSorting: [0, 'desc']
    });
</script>