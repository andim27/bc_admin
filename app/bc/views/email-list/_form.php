<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $model app\models\EmailList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'message')->widget(CKEditor::className(),[
										'editorOptions' => [
											'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
											'inline' => false, //по умолчанию false
										],
				]);
 ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? THelper::t('create') : THelper::t('update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
