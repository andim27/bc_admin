<?php

use yii\helpers\Html;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $model app\models\EmailList */

$this->title = THelper::t('update_email_list') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => THelper::t('email_lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = THelper::t('udate');
?>
<div class="email-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
