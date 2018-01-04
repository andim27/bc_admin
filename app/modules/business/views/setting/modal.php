<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
    use bupy7\cropbox\Cropbox;
?>
<div class="modal-dialog">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title"><?= THelper::t('created') ?></h4>
            </div>
            <div class="modal-body">
                <?=$form->field($model, 'avatar')->label(THelper::t('avatar'))->widget(Cropbox::className(), [
                    'attributeCropInfo' => 'crop_info',
                ]);?>
                <input type="hidden" id="flag" value="0">
                <input type="hidden" id="img" name="img">
            </div>
            <div class="modal-footer">
                <?= Html::submitButton(THelper::t('created'), ['class' => 'btn btn-success edit']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(document).ready(function(){
        if($('#flag').val()==0){
            $('.edit').attr('disabled',true);
        }
        $('.btnCrop').click(function(){
            setTimeout(function(){
                $('.edit').attr('disabled',false);
                $('#img').val($('.img-thumbnail').attr('src'));
            }, 1000)
        });
    });
</script>