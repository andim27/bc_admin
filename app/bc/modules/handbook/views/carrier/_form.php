<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\assets\AppAsset;
use app\components\THelper;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\handbook\models\Carrier */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    $(document).ready(function() {
        $("#carrier-avatar").fileinput({
            'showUpload':false,
            'showRemove':false,
            <?php if($model->avatar):?>
            'initialPreview': [
                "<img src='/uploads/<?= $model->avatar; ?>' class='file-preview-image' alt='' title='Аватар статуса'>"
            ]
            <?php endif; ?>
        });

        $("#carrier-certificate").fileinput({
            'showUpload':false,
            'showRemove':false,
            <?php if($model->certificate):?>
            'initialPreview': [
                "<img src='/uploads/<?= $model->certificate; ?>' class='file-preview-image' alt='' title='Сертификат'>"
            ]
            <?php endif; ?>
        });

    });
</script>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="col-xs-12 col-md-4">

    <?= $form->field($model, 'index_number')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'status_title')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'abbr')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'step_number')->textInput(['maxlength' => 255]) ?>

</div>

<div class="col-xs-12 col-md-4">

    <?= $form->field($model, 'existence_any')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'existence_other')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'period')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'bonus')->textInput(['maxlength' => 255]) ?>

</div>

<div class="col-xs-12 col-md-4">
    <?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showRemove' => true,
            'showUpload' => false,
        ]
    ]); ?>

    <?= $form->field($model, 'certificate')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showRemove' => true,
            'showUpload' => false,
        ]
    ]); ?>
</div>

<div class="col-xs-12">
    <div class="form-group pull-right">
        <?= Html::submitButton(THelper::t('save'), ['class' =>  'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

