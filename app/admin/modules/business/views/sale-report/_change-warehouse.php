<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\components\THelper;
use app\models\Users;

$listWarehouseUser =  Users::getListWarehouseAdmin();
$listWarehouseUser =  ArrayHelper::merge(['none'=>'none'],Users::getListWarehouseAdmin());


?>

<div class="modal-dialog popupPartsOrdering">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('warehouse') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/sale-report/save-change-warehouse',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <?=Html::hiddenInput('idSale',$info['idSale']); ?>
            <?=Html::hiddenInput('key',$info['key']); ?>

            <div class="form-group row">
                <div class="col-md-12">
                    <?=Html::dropDownList('warehouse_id',
                        (!empty($info['idUserWarehpouse']) ? $info['idUserWarehpouse'] : ''),
                        $listWarehouseUser,[
                            'class'=>'form-control',
                            'options' => [
                                '' => ['disabled' => true]
                            ]
                        ])?>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
