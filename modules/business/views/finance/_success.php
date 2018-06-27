<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
use yii\web\JsExpression;
use kartik\widgets\Select2;
?>

<style>
    .modal-header-success{
        padding:9px 15px;
        border-bottom:1px solid #eee;
        -webkit-border-top-left-radius: 5px;
        -webkit-border-top-right-radius: 5px;
        -moz-border-radius-topleft: 5px;
        -moz-border-radius-topright: 5px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        background-color: #5cb85c;
        color: #fff;
    }
</style>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header modal-header-success">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('successful_operation') ?></h4>
        </div>

        <div class="modal-body">
            <?= THelper::t('operation_canceled') ?>
        </div>

    </div>
</div>
