<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\LocaleWidget;
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


	    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
	    <?= $form->field($model, 'prefix')->textInput(['maxlength' => 255]) ?>
	    <?= $form->field($model, 'tag')->textInput(['maxlength' => 255]) ?>
	    <?= $form->field($model, 'status')->dropDownList(
	        array(
	            '1'=>THelper::t('on'),
	            '0'=>THelper::t('off')
	        )
	    );
	    ?>
	    </div>
	 	<div class="modal-footer"> 
		 	<?= Html::submitButton(($title==1)?THelper::t('editing'):THelper::t('create'), ['class' => 'btn btn-success']) ?>
	 	</div> 
	 </div>
	<?php ActiveForm::end(); ?>
 </div>