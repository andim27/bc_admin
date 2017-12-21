<?php

/* @var $this yii\web\View */
/* @var $text */
/* @var $title */
use app\components\THelper;
use yii\helpers\Html;

$this->title = THelper::t('promotions');

?>

<div class="modal-dialog" style="word-wrap: break-word;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title"><h6 class="period_prom"></h6></h4>
        </div>
        <div class="modal-body">
            <div class="users-create">
                <h3 class="titl" style="text-align: center;"></h3>
                <h5 class="cont"></h5>
            </div>
        </div>
    </div>
</div>