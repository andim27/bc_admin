<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsOrdering;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('posting_pre_ordering') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/manufacturing-suppliers/save-posting-pre-ordering',
                'options' => ['name' => 'savePartsAccessories'],
            ]); ?>

            <div class="form-group">
                <?=Html::label(THelper::t('pre_ordering'))?>
                <?=Html::dropDownList('id','',PartsOrdering::getListPreOrdering(),[
                    'class'=>'form-control',
                    'required'=>'required',
                    'options' => [
                        '' => ['disabled' => true]
                    ]
                ])?>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('posting_pre_ordering'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
