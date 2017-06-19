<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;

use app\models\PaymentCard;
$listCard = PaymentCard::getListCards();

/** @var \app\models\Transaction $item */
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_withdrawal') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/transactions/save-withdrawal',
                'options' => ['name' => 'saveWithdrawal'],
            ]); ?>

            <?=$formCom->field($model, '_id')->hiddenInput()->label(false)?>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td><?=THelper::t('login')?></td>
                        <td class="info"><?=$model->infoUser->username?></td>
                    </tr>
                    <tr>
                        <td><?=THelper::t('full_name')?></td>
                        <td class="info">
                            <?=(!empty($model->infoUser->firstName) ? $model->infoUser->firstName : '')?>
                            <?=(!empty($model->infoUser->secondName) ? $model->infoUser->secondName : '')?>
                        </td>
                    </tr>
                    <tr>
                        <td><?=THelper::t('amount')?></td>
                        <td class="info">
                            <?=$model->amount?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= THelper::t('card_type') ?></td>
                        <td class="info">
                            <?=THelper::t($listCard[(!empty($model->card['type']) ? $model->card['type'] : '1')])?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= THelper::t('card_number') ?></td>
                        <td class="info">
                            <?=(!empty($model->card['number']) ? $model->card['number'] : '')?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="row form-group">
                <div class="col-md-5">
                    <?=Html::a(THelper::t('reject'),['/business/transactions/canceled-withdrawal','id'=>$model->_id->__toString()],['class'=>'btn btn-danger btn-block']);?>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <?=Html::submitButton(THelper::t('confirm'), ['class' => 'btn btn-success btn-block']);?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
