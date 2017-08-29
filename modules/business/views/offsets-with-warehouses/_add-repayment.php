<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\Warehouse;


$myWarehouseId = Warehouse::getIdMyWarehouse();
if($myWarehouseId != '592426f6dca7872e64095b45'){
    $typeRepayment = $object.'_company';
} else{
    $typeRepayment = 'company_'.$object;
}
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_repayment') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/offsets-with-warehouses/save-repayment'
            ]); ?>

            <?=Html::hiddenInput($object.'_id',$id)?>

            <div class="form-group row">
                <div class="col-md-4"><?=Html::label(THelper::t('amount'),'amount')?></div>
                <div class="col-md-8">
                        <?=Html::input('number','price',0,[
                            'class'=>'form-control',
                            'id'=>'amount',
                            'min'=>'0',
                            'step'=>'0.01',
                            'required'=> true,
                        ])?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4"><?=Html::label(THelper::t('type_repayment'),'typeRepayment')?></div>
                <div class="col-md-8">
                    <?=Html::hiddenInput('type_repayment',$typeRepayment);?>
                    <?=Html::input('text','',THelper::t($typeRepayment),[
                        'class'     =>  'form-control',
                        'disabled'  =>  true,
                    ])?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4"><?=Html::label(THelper::t('method_repayment'),'method_repayment')?></div>
                <div class="col-md-8">
                    <?=Html::input('text','method_repayment','',[
                        'class'=>'form-control',
                        'id'=>'method_repayment',
                        'required'=> true,
                    ])?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
