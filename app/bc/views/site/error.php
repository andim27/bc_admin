<?php

use yii\helpers\Html;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
<?=THelper::t('the_above_error_occurred')?>
    </p>
    <p>
<?=THelper::t('please_contact_us')?>
    </p>

</div>
