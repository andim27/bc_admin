<?php

use yii\helpers\Html;
use mihaildev\ckeditor\CKEditor;
use app\components\THelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmailListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = THelper::t('email_lists');
$this->params['breadcrumbs'][] = $this->title;

     Yii::$app->session->getFlash('error');
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
		<div class="header b-b clearfix panel panel-default"><?=THelper::t('choose_one_of_the')?><!--Выберите один из вариантов--></div>
	</aside>
</section>



<?php
/*
?>
<div class="email-list-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Email List',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uid',
            'title',
            'message:ntext',
            'data',
            // 'status',
            // 'lang',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
*/
?>