<?php

use yii\helpers\Html;
use app\components\THelper;
$this->title = THelper::t('create_new_record');
$this->params['breadcrumbs'][] = ['label' => THelper::t('careers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= Html::encode($this->title) ?></h3>
</div>
<section class="scrollable pull-in">
    <?= $this->render('_form', ['model' => $model]) ?>
</section>

