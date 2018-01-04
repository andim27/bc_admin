<?php

use yii\helpers\Html;
use app\components\THelper;

/* @var $this yii\web\View */
/* @var $model app\modules\settings\models\CityList */

$this->title = THelper::t('add_new_city');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="modal-body">
            <div class="city-create">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>

