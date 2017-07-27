<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;

?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Подтверждение списания</h4>
        </div>

        <div class="modal-body">
            <div class="infoDanger"></div>

            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/parts-accessories-in-warehouse/save-confirmation-cancellation'
            ]); ?>

            <?=Html::hiddenInput('_id',$cancellationID)?>

            <div class="form-group row">
                <div class="col-md-5 text-left">Отклонить</div>
                <div class="col-md-2">
                    <label class="control-label switch-center"></label>
                    <label class="switch">
                        <input  type="checkbox" name="flConfirm" value="1" class="btnflConfirm" checked="checked"/>
                        <span></span>
                    </label>
                </div>
                <div class="col-md-5 text-right">Подтвердить</div>
            </div>

            <div class="form-group row blReason" style="display: none">
                <div class="col-md-12">
                    <?=Html::label(THelper::t('reason'))?>
                    <?=Html::textarea('comment','',[
                        'class'=>'form-control blReason',
                        'required'=>false,
                    ])?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>

<script>

    $('.btnflConfirm').on('change',function () {
        if($(this).is(':checked')) {
            $(this).closest('.modal-body').find('.blReason').hide().find('textarea').removeAttr('required');
        } else{
            $(this).closest('.modal-body').find('.blReason').show().find('textarea').attr('required','required');
        }
    })
</script>