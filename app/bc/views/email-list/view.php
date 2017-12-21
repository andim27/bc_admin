<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $model app\models\EmailList */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => THelper::t('email_lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="hbox stretch">
	<aside id="subNav" class="aside-md bg-white b-r">
	  <div class="wrapper b-b header"><?=THelper::t('applications')?><!--Заявки--></div>
	  <ul class="nav">
	  <?php
			$lang = Yii::$app->language;
			foreach($models as $model) {
				echo '<li class="b-b b-light"><a href="/' . $lang . '/email-list/view/?id=' . $model->id . '"><i class="fa fa-chevron-right pull-right m-t-xs text-xs icon-muted"></i>' . $model->title . '</a></li>';
			}
	  ?>
	  
		<li class="b-b b-light"><a href="/email-list/create"><i class="fa fa-chevron-right pull-right m-t-xs text-xs icon-muted"></i><?=THelper::t('create_a_new_template')?><!--Создать новый шаблон--></a></li>
	  </ul>
	</aside>
	<aside>
		<div class="header b-b clearfix panel panel-default">		
			<div class="email-list-view">

				<h1><?= Html::encode($this->title) ?></h1>

				<p>
					<?= Html::a(THelper::t('update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
					<?= Html::a(THelper::t('delete'), ['delete', 'id' => $model->id], [
						'class' => 'btn btn-danger',
						'data' => [
							'confirm' => THelper::t('are_you_sure_you_want_to_delete_this_item'),
							'method' => 'post',
						],
					]) ?>
				</p>

				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'title',
						'message:html',
					],
				]) ?>

			</div>
		</div>
	</aside>
</section>

