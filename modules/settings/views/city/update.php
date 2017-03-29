<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\CityList */

$this->title = \app\components\THelper::t('refresh_city').': '.$model->title;
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="modal-body">
            <div class="city-update">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>

