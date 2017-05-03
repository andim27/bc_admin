<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('cancellation') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-cancellation'
            ]); ?>

            <div class="form-group">
                <?=Html::label(THelper::t('goods'))?>
                <?=Html::dropDownList('parts_accessories_id','',PartsAccessories::getListPartsAccessories(),[
                    'class'=>'form-control',
                    'id'=>'selectChangeStatus',
                    'required'=>'required',
                    'options' => [
                        '' => ['disabled' => true]
                    ]
                ])?>
            </div>

            <div class="form-group">
                <?=Html::label(THelper::t('sidebar_suppliers_performers'))?>
                <?=Html::dropDownList('suppliers_performers_id','',SuppliersPerformers::getListSuppliersPerformers(),[
                    'class'=>'form-control',
                    'id'=>'selectChangeStatus',
                    'required'=>'required',
                    'options' => [
                        '' => ['disabled' => true]
                    ]
                ])?>
            </div>

            <div class="form-group">
                <?=Html::label(THelper::t('reason_cancellation'))?>
                <?=Html::textarea('comment','',[
                    'class'=>'form-control',
                    'required'=>'required',
                ])?>
            </div>


            <div class="form-group">
                <?=Html::label(THelper::t('number'))?>
                <?=Html::input('number','number','0',[
                    'class'=>'form-control',
                    'min'=>'1',
                    'step'=>'1',
                ])?>
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

