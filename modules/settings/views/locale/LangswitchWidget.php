<?php use yii\helpers\Html; ?>
<?php if (count($languages) > 1) { ?>
	<div class="language_select">
			<button class="btn btn-success dropdown-toggle" style="text-transform: uppercase; border-radius: 0; background-color: #3d6b75; border-color: #3d6b75;" data-toggle="dropdown"><?= Yii::$app->language ?> <span class="caret"></span></button>
			<ul class="dropdown-menu">
				<?php foreach ($languages as $language) {
					if ($language->alpha2 != Yii::$app->language) { ?>
						<?= '<li>' . Html::a($language->native, '/' . $language->alpha2 . Yii::$app->getRequest()->getLangUrl()) . '</li>' ?>
				<?php }
				} ?>
			</ul>
	</div>
<?php } ?>