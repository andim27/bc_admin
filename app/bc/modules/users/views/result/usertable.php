<?php

use app\components\THelper;
use app\models\User;
use app\modules\business\models\UserCarrier;
use app\modules\business\models\UsersReferrals;

/* @var $this yii\web\View */

?>

<section class="about_user">
    <div class="row">
        <div class="col-sm-6">
            <section class="panel panel-default">
                <div class="panel-body">
                    <div class="clearfix text-center m-t">
                        <div class="inline">
                            <div style="width: 130px; height: 130px; line-height: 130px;" class="easypiechart easyPieChart" data-percent="75" data-line-width="5" data-bar-color="#4cc0c1" data-track-color="#f5f5f5" data-scale-color="false" data-size="130" data-line-cap="butt" data-animate="1000">
                                <div class="thumb-lg image_founded_user">
                                        <?php if($model->avatar_img == ''){
                                            $img = '<img src="/uploads/users.jpg" class="img-circle">';
                                        } else {
                                            $img = '<img src="/uploads/'.$model->avatar_img.'" class="img-circle">';
                                        } ?>
                                    <?= $img ?>
                                </div>
                                <canvas width="130" height="130"></canvas>
                            </div>
                            <div class="h4 m-t m-b-xs n_sn"><?= $model->name ?> <?= $model->second_name ?></div>
                        </div>
                    </div>
                </div>
                <footer class="panel-footer bg-info text-center">
                    <div class="row pull-out">
                        <div class="col-xs-4">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white">доделать</span>
                                <small class="text-muted"><?=THelper::t('days_in_the_business')?><!--Дней в бизнесе--></small>
                            </div>
                        </div>
                        <div class="col-xs-4 dk">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white reg_in_struct"><?= $i ?></span>
                                <small class="text-muted"><?=THelper::t('registrations_in_the_structure')?><!--Регистраций в структуре--></small>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white">доделать</span>
                                <small class="text-muted"><?=THelper::t('partners')?><!--Партнеров--></small>
                            </div>
                        </div>
                    </div>
                </footer>
            </section>
        </div>
        <div class="col-sm-6">
            <section class="panel panel-default">
                <div class="text-center wrapper bg-light lt">
                    <div class=" inline st_ava" style="height: 165px; width: 165px;">
                        <?php if($avatar_status == ''){
                            $a_s = '<img src="/uploads/users.jpg" style="height: 165px; width: 165px;">';
                        } else $a_s = '<img src="/uploads/'.$avatar_status.'" style="height: 165px; width: 165px;">';?>
                        <?= $a_s ?>
                    </div>
                </div>
                <ul class="list-group no-radius">
                    <li class="list-group-item">
                        <span class="label bg-info">1</span> <?=THelper::t('the_account_number')?>: BPT-тут номер
                    </li>
                    <li class="list-group-item">
                        <span class="label bg-info">2</span> <?=THelper::t('login')?>: <span id="login_user"><?= $model->login ?></span>
                    </li>
                    <li class="list-group-item">
                        <span class="label bg-info">3</span> <?=THelper::t('registration_date')?>: <span id="created_user"><?= date('d-m-Y', $model->created_at) ?></span>
                    </li>
                </ul>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('conditions_of_participation_in_business')?><!--Условия участия в бизнесе--></span>
                </header>
                <div style="position: relative; overflow: hidden; width: auto; height: 90px;" class="slimScrollDiv">
                    <section style="overflow: hidden; width: auto; height: 90px;" class="panel-body slim-scroll">
                        <article class="media">
                            <div class="media-body">
                                <?=THelper::t('business_support')?>: до ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('automatic_extension_of_business_support')?>: ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('personal_award_in_the_personal_account')?>: ТУТ ДОДЕЛАТЬ
                            </div>
                        </article>
                    </section>
                </div>
            </section>
        </div>
        <div class="col-sm-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('structural_award')?><!--Структурная премия--></span>
                </header>
                <div style="position: relative; overflow: hidden; width: auto; height: 90px;" class="slimScrollDiv">
                    <section style="overflow: hidden; width: auto; height: 90px;" class="panel-body slim-scroll">
                        <article class="media">
                            <div class="media-body">
                                <?=THelper::t('personal_skills')?>: ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('structural_award')?>: ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('the_maximum_capacity_of_the_business_location')?>: ТУТ ДОДЕЛАТЬ
                            </div>
                        </article>
                    </section>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?=THelper::t('schedule_of_structure')?></header>
                <div class="panel-body">
                    <div class="demo-container">
                        <div id="placeholder" class="demo-placeholder" style="height: 240px"></div>
                        <input id="enableTooltip" type="checkbox" checked="checked" style="visibility: hidden">
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('additional_information')?></span>
                </header>
                <div style="position: relative; overflow: hidden; width: auto; height: 155px;" class="slimScrollDiv">
                    <section style="overflow: hidden; width: auto; height: 155px;" class="panel-body slim-scroll">
                        <article class="media">
                            <div class="media-body">
                                <?=THelper::t('partners_in_the_right_structure')?>: до ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('partners_in_the_left_structure')?>: ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('all_purchases_made')?>: ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('personal_points')?>: ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('personal_invitations')?>: ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('earned_for_the_personal_invitation')?>: ТУТ ДОДЕЛАТЬ<br>
                                <?=THelper::t('all_earned')?>: ТУТ ДОДЕЛАТЬ
                            </div>
                        </article>
                    </section>
                </div>
            </section>
        </div>
        <div class="col-sm-6">
            <br><br><br>
            <div class="form-inline pull-left" style="width: 300px; height: 41px; border: 1px solid #e8e8e8; background-color: white;">
                <div style="margin-left: 38px; margin-top: 6px; font-size: large;  display: block"><?=THelper::t('balance')?></div>
                <div class="pull-right" style="margin-top: -29px; margin-right: 2px; width: 110px; height: 35px; border: 1px solid #e8e8e8; background-color: white; display: inline-block">
                    <div class="sum">
                        <div style="margin: 6px auto">
                            <p id="balance" style="text-align: center">доделать</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-5">
            <section class="panel panel-info user_info1">
                <div class="panel-body">
                    <div  class="thumb pull-right m-l" id="img1">
                        <?php if($sponsor->avatar_img == ''){
                            $img1 = '<img src="/uploads/users.jpg" class="img-circle">';
                        } else {
                            $img1 = '<img src="/uploads/'.$sponsor->avatar_img.'" class="img-circle">';
                        } ?>
                        <?= $img1 ?>
                    </div>
                    <div class="clear">
                        <small class="block text-muted"><?= THelper::t('sponsor_login') ?>: <span id="login1"><?= $sponsor->login ?></span></small>
                        <?php if($sponsor->middle_name == ''){
                            $m = '';
                        } else $m = $sponsor->middle_name?>
                        <small class="block text-muted" id="fio1"><?= $sponsor->name ?> <?= $sponsor->second_name ?> <?= $m ?></small>
                        <small class="block text-muted"><?= THelper::t('date_of_registration') ?>: <span id="date1"><?= date('d-m-Y', $sponsor->created_at) ?></span></small>
                        <small class="block text-muted"><?= THelper::t('status') ?>: <span id="status1"><?= $sp_st ?></span></small>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-sm-5">
            <section class="panel panel-info user_info1">
                <div class="panel-body">
                    <div  class="thumb pull-right m-l" id="img2">
                        <?php if($parent->avatar_img == ''){
                            $img2 = '<img src="/uploads/users.jpg" class="img-circle">';
                        } else {
                            $img2 = '<img src="/uploads/'.$parent->avatar_img.'" class="img-circle">';
                        } ?>
                        <?= $img2 ?>
                    </div>
                    <div class="clear">
                        <small class="block text-muted"><?= THelper::t('parent_login') ?>: <span id="login2"><?= $parent->login ?></span></small>
                        <?php if($parent->middle_name == ''){
                            $p = '';
                        } else $p = $parent->middle_name?>
                        <small class="block text-muted" id="fio2"><?= $parent->name ?> <?= $parent->second_name ?> <?= $p ?></small>
                        <small class="block text-muted"><?= THelper::t('date_of_registration') ?>: <span id="date2"><?= date('d-m-Y', $parent->created_at) ?></span></small>
                        <small class="block text-muted"><?= THelper::t('status') ?>: <span id="status2"><?= $par_st ?></span></small>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12" id="user_table">
            <section class="panel panel-default">
                <header class="panel-heading">
                    <?=THelper::t('personal_invitations')?>
                    <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                </header>
                <div class="table-responsive">
                    <table id="personal_invitation_list_table" class="table table-striped m-b-none unique_table_class asasas" data-ride="datatables">
                        <thead>
                        <tr>
                            <th width="13%"><?=THelper::t('date_of_signing')?></th>
                            <th width="18%"><?=THelper::t('login')?></th>
                            <th width="20%"><?=THelper::t('full_name')?></th>
                            <th width="13%"><?=THelper::t('status')?></th>
                            <th width="13%"><?=THelper::t('personal_invitations')?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        if(!empty($dat)){
                            foreach($dat as $users) {
                                $user = User::find()->where(['id' => $users->uid])->one();
                                ?>
                            <tr>
                                <td><?= date('d-m-Y', $user->created_at) ?></td>

                                <td><?= $user->login ?></td>

                                <?php if($user->middle_name == ''){
                                    $father = '';
                                } else $father = $user->middle_name; ?>

                                <td><?= $user->name ?> <?= $user->second_name ?> <?= $father ?></td>


                                <?php $sta = UserCarrier::find()->where(['uid' => $user->id])->one();
                                if(!empty($sta)){
                                    if(!empty($sta->status)){
                                        $qwer = $sta->status;
                                    } else $qwer = '';
                                } else $qwer = '';
                                ?>

                                <td><?= $qwer ?></td>


                                <?php $zxvc = UsersReferrals::find()->where(['parent_id' => $user->id])->all();
                                $j = 0;
                                foreach($zxvc as $dvfdgvfv){
                                    $j++;
                                }
                                ?>


                                <td><?= $j ?></td>
                            </tr>
                        <?php }
                        }?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</section>



<?php $this->registerJsFile('js/main/flot_graph.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('js/intro/introjs.css',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/intro/intro.min.js',['depends'=>['app\assets\AppAsset']]); ?>
