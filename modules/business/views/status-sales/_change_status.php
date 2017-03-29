<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
use yii\widgets\Pjax;
use app\models\StatusSales;

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('change_status') ?></h4>
        </div>

        <?php Pjax::begin(['enablePushState' => false]); ?>
        <div class="modal-body">
            <?php $formStatus = ActiveForm::begin([
                'action' => '/' . $language . '/business/status-sales/save-status',
                'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
            ]); ?>

            <?=Html::input('hidden','idSale',$formModel->idSale)?>
            <?=Html::input('hidden','oldStatus',$statusNow)?>
            <?=Html::input('hidden','set',$set)?>

            <div class="row">
                <div class="col-md-9">
                    <?=Html::dropDownList('status',$statusNow,StatusSales::getListStatusSales(),[
                        'class'=>'form-control',
                        'options' => [
                            'status_sale_new' => ['disabled' => true,'style'=>'display:none'],
                            $statusNow => ['disabled' => true]
                        ]
                    ])?>
                </div>
                <div class="col-md-3">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
            
        </div>

        <?php Pjax::end(); ?>
    </div>
</div>