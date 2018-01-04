<?php
    use app\components\THelper;
    use app\assets\DateTableAsset;

    DateTableAsset::register($this);
?>
<div class="modal-dialog" style="width: 90%">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= THelper::t('vouchers_history') ?></h4>
        </div>
        <div class="modal-body">
            <div class="row" style="margin-bottom:25px">
                <div class="col-xs-12">
                    <table class="table table-striped m-b-none vouchers-table">
                        <thead>
                            <tr>
                                <th><?= THelper::t('date') ?></th>
                                <th><?= THelper::t('amount') ?></th>
                                <th><?= THelper::t('code') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vouchers as $voucher) { ?>
                            <tr>
                                <td><?= gmdate('d.m.Y', $voucher->dateCreate) ?></td>
                                <td><?= $voucher->amount ?></td>
                                <td><?= $voucher->forWhat ?></td>
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
    $('table.vouchers-table').dataTable({
        language: TRANSLATION,
        sDom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        lengthMenu: [ 25, 50, 75, 100 ],
        aaSorting: [0, 'desc']
    });
</script>