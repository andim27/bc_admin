<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<?php if ($purchases) { ?>
    <table class="table table-striped datagrid m-b-sm table-by-user">
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
    <?php if ($purchases) {
        foreach ($purchases as $purchase) {
            if (! isset($products[$purchase->product])) {
                $product = $products[$purchase->product] = $purchase->getProduct();
            } else {
                $product = $products[$purchase->product];
            }
            ?>
            <tr>
                <td>
                    <?= gmdate('d.m.Y', $purchase->dateCreate) ?>
                </td>
                <td>
                    <?= $product->product ?>
                </td>
                <td>
                    <?= $product->productName ?>
                </td>
                <td>
                    <?= $product->price ?>
                </td>
                <td>
                    <?= $purchase->bonusPoints ?>
                </td>
                <td>
                    <?= $purcahseUser->username ?>
                </td>
                <td>
                    <?= $purcahseUser->firstName . ' ' . $purcahseUser->secondName ?>
                </td>
                <td>
                    <?php if ($purchase->type == 1) { ?>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/user/cancel-purchase', 'id' => $purchase->id], ['onclick' => 'return confirmCancellation();']) ?>
                    <?php } else { ?>
                        <?= THelper::t('users_purchase_deleted') ?>
                    <?php } ?>
                </td>
            </tr>
        <?php }
    }
    ?>
    </tbody>
</table>
<?php } else { ?>
    <div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-ban-circle"></i><?= THelper::t('user_purchase_not_found') ?></div>
<?php } ?>
<script>
    $('.table-by-user').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100]
    });

    function confirmCancellation() {
        if (confirm("<?= THelper::t('user_purchase_confirm_cancellation') ?>")) {
            return true;
        } else {
            return false;
        }
    }
</script>
