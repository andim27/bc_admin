<?php
use app\components\THelper;
use app\models\Users;
?>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <?= THelper::t('status_was_save') ?>
        </div>
    </div>
</div>

<script>
    var idChangeRow = '<?=$idSale?>';
    var set = '<?=$set?>';
    var userChangeStatus = '<?=Users::getUserEmail($idUser)?>';
    var newStatus = '<?= $status ?>';
    var newStatusTranslate = '<?= THelper::t($status) ?>';

    changeRow = $(document).find('#row_' + idChangeRow);
    changeRow.find("[data-set='" + set + "'] .statusOrder").text(newStatusTranslate);

//    if(newStatus == 'status_sale_issued'){
//        changeRow.find("[data-set='" + set + "'] .actionOrder").html(' Выдан кассиром: ' + userChangeStatus);
//    }
</script>

