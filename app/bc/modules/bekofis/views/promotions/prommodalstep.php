<?php

/* @var $this yii\web\View */
/* @var $sku */
/* @var $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
$this->title = THelper::t('shares_of_the_steps');

?>

<div class="modal-dialog" style="word-wrap: break-word;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="modal-body">
            <div class="users-create">

                <?php $form = ActiveForm::begin([
                    'id' => 'bbb'
                ]); ?>

                <?php $model->isNewRecord ? $model->promotion_begin = date('d-m-Y') : $model->promotion_begin = date('d-m-Y', $model->promotion_begin); ?>
                <?= $form->field($model, 'promotion_begin')->textInput([
                    'maxlength'=>16, 'class'=>'input-sm input-s datepicker-input form-control',
                    'data-date-format'=>'dd-mm-yyyy'
                ]) ?>

                <?php $model->isNewRecord ? $model->promotion_end = date('d-m-Y') : $model->promotion_end = date('d-m-Y', $model->promotion_end); ?>
                <?= $form->field($model, 'promotion_end')->textInput([
                    'maxlength'=>16, 'class'=>'input-sm input-s datepicker-input form-control',
                    'data-date-format'=>'dd-mm-yyyy'
                ]) ?>

                <label class="control-label" for="promstep-sku_id"><?=THelper::t('product_code_which_should_own_partner')?><!--Код товара, которым должен владеть партнер--></label>
                <select id="promstep-sku_id" class="form-control" name="PromStep[sku_id]">
                    <?php
                    foreach($sku as $option){?>
                        <option value="<?=$option['sku']?>"><?=$option['sku']?></option>
                    <?php }?>
                </select>

                <?= $form->field($model, 'product_title')->textInput() ?>
                <?= $form->field($model, 'sum')->textInput() ?>

                <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-info pull-right', 'name' => 'save_step']) ?>

                <?php ActiveForm::end(); ?>
                <br>
            </div>
        </div>
    </div>
</div>
