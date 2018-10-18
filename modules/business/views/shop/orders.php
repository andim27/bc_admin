<?php
    use app\components\THelper;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('shop_orders_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="tab-content">
                    <table class="table table-striped datagrid m-b-sm">
                        <thead>
                        <tr>
                            <th><?= THelper::t('shop_orders_order_id') ?></th>
                            <th><?= THelper::t('shop_orders_date') ?></th>
                            <th><?= THelper::t('shop_orders_products') ?></th>
                            <th><?= THelper::t('shop_orders_total') ?></th>
                            <th><?= THelper::t('shop_orders_payment_type') ?></th>
                            <th><?= THelper::t('shop_orders_payment_status') ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order) { ?>
                                <tr>
                                    <td><?= $order->orderId ?></td>
                                    <td><?= $order->created_at->toDateTime()->format('d.m.Y') ?></td>
                                    <td>
                                        <ul class="list-group">
                                        <?php foreach ($order->products as $product) { ?>
                                            <li class="list-group-item"><?= $product['productName']; ?></li>
                                        <?php } ?>
                                        </ul>
                                    </td>
                                    <td><?= $order->total ?></td>
                                    <td><?= THelper::t('shop_orders_payment_type_' . $order->paymentType) ?></td>
                                    <td class="shop_order_<?= $order->_id ?>"><?= THelper::t('shop_orders_payment_status_' . $order->paymentStatus) ?></td>
                                    <td>
                                        <?php if ($order->paymentStatus != 'paid') { ?>
                                            <a data-id="<?= $order->_id ?>" class="btn btn-success btn-sm set-payment-type"><?= THelper::t('shop_orders_set_payment_type') ?></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    $('.set-payment-type').click(function () {
        var thisButton = $(this);
        thisButton.attr('disabled', 'disabled');
        var orderId = thisButton.data('id');
        $.ajax({
            url: '/' + LANG + '/business/shop/pay-order',
            method: 'POST',
            data: {
                order: orderId
            },
            success: function (data) {
                if (data) {
                    $('.shop_order_' + orderId).html('<?= THelper::t("shop_orders_payment_status_paid") ?>');
                    thisButton.hide();
                } else {
                    thisButton.removeAttr('disabled');
                }
            }
        });
    });
    var table = $('.table');
    table = table.dataTable({
        language: TRANSLATION,
        'order': [[ 0, 'desc' ]]
    });
</script>