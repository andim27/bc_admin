<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\settings\models\CountryList;
use app\models\Condition;
use app\components\THelper;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\CityList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-list-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'country_id')->dropDownList(
        ArrayHelper::map(CountryList::find()->asArray()->all(), 'id', 'title')
    ); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'state')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'region')->textInput(['maxlength' => 255]) ?>
    <div class="modal-footer form-group">
        <?= Html::submitButton(THelper::t('close'), ['class' => 'btn btn-default', 'data-dismiss'=>'modal']) ?>
        <?= Html::submitButton($model->isNewRecord ? THelper::t('create') : THelper::t('change'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
