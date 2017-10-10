<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\models\PartsAccessoriesInWarehouse;
use kartik\widgets\Select2;


$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

$listSuppliersPerformers=SuppliersPerformers::getListSuppliersPerformers();
$listSuppliersPerformers = ArrayHelper::merge([''=>'Выберите поставщика-испонителя'],$listSuppliersPerformers);

$canRepair = 0;
if(!empty($model) && !empty($model->suppliers_performers_id)){

//    if($model->one_component != 1){
//        $canMake = PartsAccessoriesInWarehouse::getHowMuchCanCollectWithInterchangeable((string)$model->parts_accessories_id,$model->suppliers_performers_id);
//    } else {
//        $canMake = $listGoodsFromMyWarehouse[(string)$model->parts_accessories_id];
//    }

}

$number = 0;
if(!empty($model)){
    $number = $model->number;
}

?>

<div class="modal-dialog modal-more-lg popupSendingRepair">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Отправка на ремонт</h4>
        </div>

        <div class="modal-body">


            <!--single product-->
            <div id="send-posting">
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-sending-repair',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(!empty($model) ? (string)$model->_id : ''));?>
                <?=Html::hiddenInput('one_component',1);?>

                <div class="form-group row infoDanger"></div>

                <div class="form-group row blUnique">
                    <div class="col-md-7">
                        <?=Html::dropDownList('parts_accessories_id',
                            (!empty($model) ?  $model->parts_accessories_id : ''),
                            ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListProductRepair()),[
                                'class'=>'form-control',
                                'id'=>'selectGoods',
                                'required'=>true,
                                'options' => [
                                    '' => ['disabled' => true]
                                ],
                                'disabled' => (!empty($model) ?  true : false)
                            ])?>

                    </div>
                    <div class="col-md-1">
                        можно отремотировать
                    </div>
                    <div class="col-md-2">
                        <?=Html::input('text','can_repair',($canRepair+$number),['class'=>'form-control CanRepair','disabled'=>'disabled'])?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::input('number','number',$number,[
                            'class'=>'form-control WantCollect',
                            'pattern'=>'\d*',
                            'min'=>'1',
                            'max'=>'0',
                            'step'=>'1',
                        ])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?=Html::label(THelper::t('sidebar_suppliers_performers'))?>
                        <?=Html::dropDownList('suppliers_performers_id',
                            (!empty($model) ? (string)$model->suppliers_performers_id : ''),
                            $listSuppliersPerformers,[
                                'class'=>'form-control',
                                'id'=>'selectChangeStatus',
                                'required'=>'required',
                                'options' => [
                                    '' => ['disabled' => true]
                                ]
                            ])?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success assemblyBtn','style'=>'display:none']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('change','#selectGoods',function () {
        blForm = $(this).closest('.popupSendingRepair');

        clearPopup();

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['submit-execution-posting/can-repair'])?>',
            type: 'POST',
            data: {
                partsAccessoriesId  : $(this).val()
            },
            success: function (data) {
                blForm.find('.CanRepair').val(data);

                if(data > 0){
                    $(".assemblyBtn").show();
                    blForm.find(".WantCollect").attr('max',data);
                }
            }
        });
    });

    function clearPopup() {
        blForm = $('.popupSendingRepair');
        blForm.find(".assemblyBtn").hide();
        blForm.find(".WantCollect").val('0').attr('max','0');

    }
</script>