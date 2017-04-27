<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;

?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_interchangeable_goods') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-interchangeable-goods',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <div class="row">
                <div class="col-md-12">
                    <?=Html::label(THelper::t('goods'))?>
                    <?=Html::dropDownList('id',(!empty($id) ? $id : ''),PartsAccessories::getListPartsAccessories(),[
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
                <div class="col-md-12">
                    <?=Html::label(THelper::t('goods'))?>
                    <?=Html::dropDownList('idInterchangeable',(!empty($idInterchangeable) ? $idInterchangeable : ''),PartsAccessories::getListPartsAccessories() ,[
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
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
