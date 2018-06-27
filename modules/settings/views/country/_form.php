<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Condition;
use app\components\THelper;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\CountryList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="country-list-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'iso_code')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'status')->dropDownList(
        ArrayHelper::map(Condition::find()->asArray()->all(), 'id', 'title')
    ); ?>
    <div class="modal-footer form-group">
        <?= Html::submitButton(THelper::t('close'), ['class' => 'btn btn-default', 'data-dismiss'=>'modal']) ?>
        <?= Html::submitButton($model->isNewRecord ? THelper::t('create') : THelper::t('change'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
