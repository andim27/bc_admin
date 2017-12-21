<?php
    use app\components\THelper;
    use yii\helpers\Html;

?>
<div class="modal fade" id="pincode-sposnor">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=THelper::t('buy_product_with_pincode')?></h4>
            </div>
            <div class="modal-body">
                <div class="blError"></div>

                <?php if (!empty($status)) { ?>
                    <div class="alert alert-<?=$status?>">
                        <?=$message?>
                    </div>
                <?php } ?>

                <?php if (!empty($buttons)) { ?>
                    <div class="form-group text-right">
                        <?=Html::button(THelper::t('yes'), ['type' => 'button', 'class' => 'btn btn-success', 'id' => 'pincode-continue', 'data-dismiss' => "modal"])?>
                        <?=Html::button(THelper::t('no'), ['type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => "modal"])?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    <?php $form_id = !empty($form_id) ? $form_id : 'pincode' ?>

    $('#pincode-continue').on('click', function () {
        var form = $('#<?=$form_id?>');
        var params = form.serializeArray();

        params.push({
            name : 'accepted',
            value : true
        });

        params.push({
            name : 'partner_accepted',
            value : true
        });

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: $.param(params),
            success: function(data) {
                if (data === 'warehouse') {
                    $("#listWarehouse").modal();
                } else {
                    $('#listWarehouse').after(data);
                    $('#pincode-sposnor').modal();
                }
            }
        });
    });
</script>



