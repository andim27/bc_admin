<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\CountryList */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => THelper::t('country_lists﻿'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-list-view">

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
            'id',
            'title',
            'iso_code',
            'status',
        ],
    ]) ?>

</div>
