<?php

use yii\helpers\Html;
use app\components\THelper;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */


$this->title = THelper::t('sign_in_as_user');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <div class="col-sm-5">
            <h4><?= THelper::t('sign_in_as_user') ?></h4>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label"><?= THelper::t('login_email_mobile_telephone') ?></label>
                <input type="text" class="form-control" id="find_user">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <section class="panel panel-info user_info">
                <div class="panel-body">
                    <div  class="thumb pull-right m-l" id="img">

                    </div>
                    <div class="clear">
                        <small class="block text-muted"><?= THelper::t('login') ?>: <span id="login"></span></small>
                        <small class="block text-muted" id="fio"></small>
                        <small class="block text-muted"><?= THelper::t('date_of_registration') ?>: <span id="date"></span></small>
                        <small class="block text-muted"><?= THelper::t('status') ?>: <span id="status"></span></small>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <?= Html::button(THelper::t('enter_in_back_office'),  ['class' => 'btn btn-success user_enter', 'style' => 'background-color: #0e24ff']); ?>
        </div>
    </div>
</div>

<?php $this->registerJsFile('js/main/signin.js',['depends'=>['app\assets\AppAsset']]); ?>