<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use app\models\Warehouse;
use app\models\PercentForRepaymentAmounts;

$list = [];
if($object == 'representative') {
    $list = Warehouse::getListHeadAdmin();
} else {
    $list = Warehouse::getListHeadAdminWarehouse($representative_id);
}

/** @var \app\models\PercentForRepaymentAmounts $model */
?>

<div class="modal-dialog" id="modal-persent-for-repayment">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_repayment_amounts') ?></h4>
        </div>

        <div class="modal-body">

            <?php if(empty($error_message)){ ?>

            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/offsets-with-warehouses/save-recovery-for-repayment'
            ]); ?>

            <?= Html::hiddenInput('object',$object)?>
            <?= Html::hiddenInput('representative_id',(!empty($representative_id) ? $representative_id : ''))?>

            <div class="form-group row">
                <div class="col-md-12">
                    <?= Html::hiddenInput('month_recovery',$lastMonth)?>

                    <?=Html::label(THelper::t('date'))?>
                    <?=Html::input('text','',
                        $lastMonth,
                        [
                            'class'=>'form-control',
                            'disabled' => true
                        ]
                    )?>
                </div>
            </div>



            <table  class="table table-bordered">
                <thead>
                <tr>
                    <th><?=THelper::t($object);?></th>
                    <th><?=THelper::t('recovery')?></th>
                    <th><?=THelper::t('settings_translation_comment')?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($list)) {?>
                    <?php foreach($list as $k=>$item) {?>
                        <tr>
                            <td>
                                <?= Html::hiddenInput($object.'[]',$k)?>

                                <?=Html::input('text','',
                                    $item,
                                    [
                                        'class'=>'form-control',
                                        'disabled' => true
                                    ]
                                )?>
                            </td>
                            <td>
                                <?=Html::input('number','recovery_amount[]','0',[
                                    'class'=>'form-control',
                                    'required'=>true,
                                    'pattern'=>'\d*',
                                    'min'=>'0',
                                    'step'=>'0.01'])?>
                            </td><td>
                                <?=Html::input('text','comment[]','',[
                                    'class'=>'form-control',
                                ])?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>


            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <?php } else {?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <?=$error_message;?>
                    </div>
                </div>
            <?php } ?>

        </div>

    </div>
</div>
