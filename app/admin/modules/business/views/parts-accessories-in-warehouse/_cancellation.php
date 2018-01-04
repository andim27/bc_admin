<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;

$listGoods = PartsAccessories::getListPartsAccessories();

$countGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();
$countGoods = $countGoodsFromMyWarehouse[$goodsID];
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Списание «<?=$listGoods[$goodsID]?>»</h4>
        </div>

        <div class="modal-body">
            <div class="infoDanger"></div>

            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/parts-accessories-in-warehouse/save-cancellation'
            ]); ?>

            <?=Html::hiddenInput('parts_accessories_id',$goodsID)?>

            <div class="form-group">
                <?=Html::label(THelper::t('Количество'))?>
                <?=Html::input('number','number','0',[
                    'class'=>'form-control wantCancellations',
                    'min'=>'1',
                    'step'=>'1',
                ])?>
            </div>

            <div class="form-group">
                <?=Html::label(THelper::t('reason_cancellation'))?>
                <?=Html::textarea('comment','',[
                    'class'=>'form-control',
                    'required'=>'required',
                ])?>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('cancellation'), ['class' => 'btn btn-success btnCancellation']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>


<script>

    var countGoodsInWarehouse = <?=$countGoods?>



    $('.wantCancellations').on('change',function () {
        wantCancellations = parseInt($(this).val());

       if((countGoodsInWarehouse - wantCancellations) < 0){
            $(".infoDanger").html(
                '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                'Такого количества нет на складе. Доступно ' + countGoodsInWarehouse + ' шт.' +
                '</div>'
            );

            $('.btnCancellation').hide();
        } else {
            $('.btnCancellation').show();
        }
    });


</script>
