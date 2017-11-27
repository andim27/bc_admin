<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use yii\web\JsExpression;
use kartik\widgets\Select2;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sent_loan') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/loan/save-sent-repayment',
                'options' => ['name' => 'saveSentRepayment'],
            ]); ?>

            <div class="row form-group">
                <div class="col-md-12">
                    <?= $formCom->field($model, 'amount')->textInput(['type'=>'number','pattern'=>'\d*','step'=>'1','min'=>'1'])->label(THelper::t('amount')) ?>
                </div>
            </div>

            <?= $formCom->field($model, 'user_id')->widget(Select2::className(),[
                'initValueText' => !empty($model->user_id) ? \app\models\Users::findOne(['_id'=>new \MongoDB\BSON\ObjectID($model->user_id)])->username : '',
                'language' => 'ru',
                'options' => [
                    'placeholder' => '',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Ожидайте...'; }"),
                    ],
                    'ajax' => [
                        'url' => '/business/user/search-list-users',
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
            ])->label(THelper::t('user')) ?>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
