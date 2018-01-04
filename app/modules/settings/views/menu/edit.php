<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\LocaleWidget;
use app\modules\settings\models\Localisation;
use app\components\THelper;
?>
<div class="modal-dialog">
    <?php $form = ActiveForm::begin(); ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?=($title==1)?THelper::t('editing'):THelper::t('create');?></h4>
        </div>
        <div class="modal-body">
            <input type="hidden" id="locales-category_id" class="form-control" name="Locales[category_id]" value="1" maxlength="255">
            <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'url')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'class')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'parent_id')->textInput(['maxlength' => 5]) ?>
            <?= $form->field($model, 'language_id')->dropDownList(
                ArrayHelper::map(Localisation::find()->asArray()->all(), 'id', 'title')
            ); ?>
        </div>
        <div class="modal-footer">
            <?= Html::submitButton(($title==1)?THelper::t('editing'):THelper::t('create'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>