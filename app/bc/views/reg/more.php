<?php

/* @var $this yii\web\View */
/* @var $text */

use yii\helpers\Html;
use app\components\THelper;

$this->title = THelper::t('more_information_about_referees');

?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="modal-body">
            <div class="users-create">
                <?= $text ?>
            </div>
        </div>
    </div>
</div>