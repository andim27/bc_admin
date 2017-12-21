<?php
    use app\components\THelper;
    use yii\helpers\Html;
use yii\web\View;

?>
<div class="modal fade" id="partner-confirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=THelper::t('partner_confirm_with_name')?></h4>
            </div>
            <div class="modal-body">
                <?php if(isset($full_name)) { ?>
                    <?=THelper::t('full_name_partner_confirmation') . ': <strong>' . $full_name . '</strong>'?>
                <?php } ?>

                <div class="form-group text-right">
                    <?=Html::button(THelper::t('continue'), ['type' => 'button', 'class' => 'btn btn-success', 'id' => 'partner-confirm-continue', 'data-dismiss' => "modal"])?>
                    <?=Html::button(THelper::t('no'), ['type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => "modal"])?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$JS = <<< JS
    $('#partner-confirm-continue').on('click', function () {
        var form = $('#voucher');
        var params = form.serializeArray();

        params.push({
            name : 'partner_accepted',
            value : true
        });

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: $.param(params),
            success: function(data) {
                $('#listWarehouse').after(data);
                  jQuery.noConflict();
                $('#pincode-sposnor').modal();
            }
        });
    });
JS;
$this->registerJs($JS, View::POS_END);
?>



