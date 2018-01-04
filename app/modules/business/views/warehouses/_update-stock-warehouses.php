<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;


$listProduct = PartsAccessories::getListPartsAccessoriesForSaLe();

?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_stock_warehouses') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/warehouses/save-stock-warehouses'
            ]); ?>

            <div class="form-group row">
                <div class="col-md-12">
                    <?=(!empty($model) ? Html::hiddenInput('warehouse_id',(string)$model->_id) : '')?>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-md-9">
                    <?=THelper::t('goods');?>
                </div>
                <div class="col-md-3">
                    <?=THelper::t('stock')?>
                </div>
            </div>

            <?php foreach($listProduct as $k=>$v) {?>
                <div class="form-group row">
                    <div class="col-md-9">
                        <?=Html::input('text','',$v,['class'=>'form-control','disabled' => true])?>
                        <?=Html::hiddenInput('product_id[]',$k)?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::input('number','stock[]',(!empty($infoProduct[$k]) ? $infoProduct[$k]['count'] : 0 ),[
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
