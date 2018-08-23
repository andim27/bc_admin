<?php
use app\components\THelper;
$this->title = THelper::t('sale');
$this->params['breadcrumbs'][] = $this->title;
?>
<!----------------------B:ask pin cancel------------------>
<script>
    function cancelUserSale(id,date,name,price) {
        $('#cancel-product-id').val(id);
        $('#cancel-product-date').html(date);
        $('#cancel-product-name').html(name);
        $('#cancel-product-price').html('Price='+price);
        $('#askCancelSaleModal').modal('show')
    }

    function doCancelSale() {
        var url="/<?=Yii::$app->language?>/business/user/cancel-sale";
        //   /business/user/cancelSale/?sale_id="
        $('#server-message').removeClass('bg-danger');
        $.post(url,{
            sale_id:$('#cancel-product-id').val(),
            comment:$('#cancel-reason').val(),
            comment_user_id:''}).done(function(data) {
            if (data.success == true) {
                mes=data.message;
                mes_canceled='<?= THelper::t('canceled') ?>';
                $('#server-message').addClass('bg-success');

            } else {
                mes=data.message;//

                $('#server-message').addClass('bg-danger')
            }
            console.info('Cancel server result:'+mes);
            $('#server-message').show().html(mes);
            setTimeout(function(){
                $('#server-message').hide();
                $('#askCancelPinModal').modal('hide');

            },2500)

        });

    }
</script>
<style>
    .back-head-ask {
        background-color: #0000CC;
    }
    .pos-ask-modal {
        margin-top: 10%;
    }
</style>
<!-- Modal -->
<div id="askCancelSaleModal" class="modal fade pos-ask-modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header btn-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= THelper::t('sale_canceled') ?></h4>
            </div>
            <div class="modal-body">
                <p><strong><span id="cancel-product-date"></span></strong></p>
                <p><strong><span id="cancel-product-name"></span></strong></p>
                <p><strong><span id="cancel-product-price"></span></strong></p>
                <p>
                <?= THelper::t('are_you_sure') ?>
                <?= THelper::t('reason') ?>?
                </p>
                <input id='cancel-product-id' type="hidden" class="form-control" >
                <input id='cancel-reason' type="text" class="form-control" >
                <p class="text-center" id="server-message" style="margin-top: 15px"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  onclick="doCancelSale()"><?= THelper::t('yes') ?></button>
                <button type="button" class="btn btn-default" onclick="$('#askCancelSaleModal').modal('hide')"><?= THelper::t('no') ?></button>
            </div>
        </div>

    </div>
</div>
<!----------------------E:ask pin cancel------------------>
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
                        <th><?= THelper::t('sale_price') ?></th>
                        <th><?= THelper::t('cancel') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($sales as $sale) { ?>
                        <tr>
                            <td><?= $sale->dateCreate ? gmdate('d.m.Y', $sale->dateCreate) : '' ?></td>
                            <td><?= $sale->product ?></td>
                            <td>
                                <?php
                                $language = Yii::$app->language;
                                echo isset($sale->productData->productNameLangs->{$language}) ? $sale->productData->productNameLangs->{$language} : $sale->productName
                                //$sale->getName(Yii::$app->language)
                                ?>
                            </td>
                            <td><?= $sale->price ?></td>
                            <td>
                                <?php if ($sale->type == -1) { ?>
                                    <?= THelper::t('sale_canceled') ?>
                                <?php } else { ?>
                                    <a class="btn btn-danger" href="#"   data-target="#askCancelSaleModal" onclick="cancelUserSale('<?= $sale->id; ?>','<?=$sale->dateCreate ? gmdate('d.m.Y', $sale->dateCreate) : '' ?>','<?=$sale->productName ?>','<?= $sale->price ?>');">
                                        <i class="fa fa-trash-o fa-lg"></i> <?= THelper::t('sale_canceled') ?>
                                    </a>

                                <?php } ?>
                            </td>
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