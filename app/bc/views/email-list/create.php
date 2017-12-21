<?php

use yii\helpers\Html;
use app\components\THelper;

/* @var $this yii\web\View */
/* @var $model app\models\EmailList */

$this->title = THelper::t('create_email_list');
$this->params['breadcrumbs'][] = ['label' => THelper::t('email_lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


    echo  Yii::$app->session->getFlash('error');
?>
<div class="email-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
