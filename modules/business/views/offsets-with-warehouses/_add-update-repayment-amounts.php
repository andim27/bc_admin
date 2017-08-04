<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\Warehouse;
use app\models\PartsAccessories;

$listWarehouse = Warehouse::getArrayWarehouse();
if(empty($id)){
    foreach ($listWarehouse as $k=>$item) {
        if(in_array($k,$useWarehouse)){
            unset($listWarehouse[$k]);
        }
    }
}

$listProduct = PartsAccessories::getListPartsAccessoriesForSaLe();
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_repayment_amounts') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/offsets-with-warehouses/save-repayment-amounts'
            ]); ?>

            <div class="form-group row">
                <div class="col-md-12">
                    <?=Html::label(THelper::t('warehouse'))?>
                    <?=Html::dropDownList('warehouse_id',
                        (!empty($id) ? $id : ''),
                        $listWarehouse,[
                            'class'=>'form-control',
                            'required'=>true,
                            'disabled' => (!empty($id) ? true : false)
                        ])?>

                    <?=(!empty($id) ? Html::hiddenInput('warehouse_id',$id) : '')?>
                </div>
            </div>

            <?php foreach($listProduct as $k=>$v) {?>
            <div class="form-group row">
                <div class="col-md-8">
                    <?=Html::input('text','',$v,['class'=>'form-control','disabled' => true])?>
                    <?=Html::hiddenInput('product_id[]',$k)?>
                </div>
                <div class="col-md-4">
                    <?=Html::input('number','price[]',(!empty($infoProduct[$k]) ? $infoProduct[$k] : 0 ),[
                        'class'=>'form-control',
                        'min'=>'0',
                        'step'=>'1',
                    ])?>
                </div>
            </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
