<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use yii\widgets\Pjax;
use app\models\Products;

$listSet = Products::getListPack();


?>


<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('make_order') ?></h4>
        </div>

        <div class="modal-body">
            <?php Pjax::begin(['enablePushState' => false,'id' => 'pjaxFormMakeOrder'.rand()]); ?>

            <?= $this->render('_make-order-form', [
                'language'  => $language,
                'request'   => (!empty($request) ? $request : '')
            ]) ?>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".typeInfoUser").on('click',function () {
        nameInput = $(this).data('type');
        contentLabel = $(this).html();

        $(".infoUser").attr('name',nameInput);
        $(".labelUser").html(contentLabel);
    })
</script>