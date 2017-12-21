<?php

use yii\helpers\Html;
use app\components\THelper;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */


$this->title = THelper::t('support');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group wrapper m-b-none">
                <?php $form = ActiveForm::begin(['id' => 'benny_benassi']); ?>
                <div class="form-group">
                    <label class="control-label"><?= THelper::t('write_the_link_for_support_page'); ?></label>
                    <?php $model->isNewRecord ? $model->link = '' : $model->link?>
                    <?= $form->field($model, 'link')->textInput(['class' => 'form-control', 'placeholder' => THelper::t('example.com')])->label(false) ?>
                </div>
                <?= Html::submitButton(THelper::t('save'),  ['class' => 'btn btn-success pull-right']); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>