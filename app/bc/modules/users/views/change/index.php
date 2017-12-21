<?php

use yii\helpers\Html;
use app\components\THelper;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */


$this->title = THelper::t('change_parent_or_sponsor');
$this->params['breadcrumbs'][] = $this->title;
?>


    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <h4><?= THelper::t('change_parent_or_sponsor') ?></h4>
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
        <br>

        <div class="row">
            <label class="control-label" style="margin-left: 15px"><?= THelper::t('login_email_mobile_telephone') ?></label>
            <div class="form-group">
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="find_user1">
                    <input type="hidden" id="sp">
                </div>
                <?= Html::button(THelper::t('change_sponsor'),  ['class' => 'btn btn-success user_enter1', 'style' => 'background-color: #0da904']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <section class="panel panel-info user_info1">
                    <div class="panel-body">
                        <div  class="thumb pull-right m-l" id="img1">

                        </div>
                        <div class="clear">
                            <small class="block text-muted"><?= THelper::t('login') ?>: <span id="login1"></span></small>
                            <small class="block text-muted" id="fio1"></small>
                            <small class="block text-muted"><?= THelper::t('date_of_registration') ?>: <span id="date1"></span></small>
                            <small class="block text-muted"><?= THelper::t('status') ?>: <span id="status1"></span></small>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <br>

        <div class="row">
            <label class="control-label" style="margin-left: 15px"><?= THelper::t('login_email_mobile_telephone') ?></label>
            <div class="form-group">
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="find_user2">
                    <input type="hidden" id="par">
                </div>
                <?= Html::button(THelper::t('change_parent'),  ['class' => 'btn btn-success user_enter2', 'style' => 'background-color: #ff0e2b']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <section class="panel panel-info user_info2">
                    <div class="panel-body">
                        <div  class="thumb pull-right m-l" id="img2">

                        </div>
                        <div class="clear">
                            <small class="block text-muted"><?= THelper::t('login') ?>: <span id="login2"></span></small>
                            <small class="block text-muted" id="fio2"></small>
                            <small class="block text-muted"><?= THelper::t('date_of_registration') ?>: <span id="date2"></span></small>
                            <small class="block text-muted"><?= THelper::t('status') ?>: <span id="status2"></span></small>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div style="position: absolute; top: 50%; left: 33%; display: none; border: 1px solid #0000aa; background-color: #0b93d5; height: 30px; padding: 2px; color: white; font-size: large" id="show_alert1"><?= THelper::t('sponsor_have_already_changed') ?></div>
        <div style="position: absolute; top: 50%; left: 33%; display: none; border: 1px solid #0000aa; background-color: #0b93d5; height: 30px; padding: 2px; color: white; font-size: large" id="show_alert2"><?= THelper::t('parent_have_already_changed') ?></div>
    </div>

<?php $this->registerJsFile('js/main/change_parent_sponsor.js',['depends'=>['app\assets\AppAsset']]); ?>