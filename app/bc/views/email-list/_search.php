<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $model app\models\EmailListSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'message') ?>

    <?= $form->field($model, 'data') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'lang') ?>

    <div class="form-group">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(THelper::t('reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
