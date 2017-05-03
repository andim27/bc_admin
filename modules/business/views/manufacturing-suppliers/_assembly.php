<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use yii\helpers\ArrayHelper;
use app\models\PartsAccessories;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('assembly') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-cancellation',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <div class="form-group">
                    <?=Html::label(THelper::t('goods'))?>
                    <?=Html::dropDownList('id','',ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessories()),[
                        'class'=>'form-control',
                        'id'=>'selectGoods',
                        'required'=>'required',
                        'options' => [
                            '' => ['disabled' => true]
                        ]
                    ])?>
            </div>
            
            <div class="row blPartsAccessories">
                
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('cancellation'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>

<script>
    $(document).on('change','#selectGoods',function () {

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['manufacturing-suppliers/kit-for-accessories'])?>',
            type: 'POST',
            data: {
                PartsAccessoriesId : $(this).val(),
            },
            success: function (data) {
                $('.blPartsAccessories').html(data);
            }
        });

    });
</script>
