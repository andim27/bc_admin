<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;

$this->title = THelper::t('edit_profile');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->session->getFlash('error');
?>

<section class="hbox stretch">
    <aside>
        <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?=THelper::t('profile_editor')?><!--Редактор профиля--></span>
                </header>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(
                        [
                            'options' => ['class' => 'form-horizontal', 'data-validate'=>"parsley"],
                        ]
                    ); ?>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?=THelper::t('login')?><!--Логин--></label>
                            <div class="col-sm-9">
                                <input type="text" data-required="true" class="form-control parsley-validated" name="User[login]">
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?=THelper::t('name')?><!--Имя--></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control parsley-validated " data-required="true" name="User[name]">
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?=THelper::t('surname')?><!--Фамилия--></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control parsley-validated " data-required="true" name="User[second_name]">
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?=THelper::t('email')?><!--email--></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control parsley-validated" data-type="email" data-required="true" name="User[email]">
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?=THelper::t('mobile_phone')?><!--Моб.телефон--></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control parsley-validated" data-type="phone" placeholder="(XXX) XXXX XXX" data-required="true" name="User[mobile]">
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"><?=THelper::t('skype')?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control parsley-validated" data-required="true" name="User[skype]">
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group pull-in clearfix">
                            <div class="col-sm-12">
                                <label><?=THelper::t('password_entry')?><!--Пароль на вход--></label>
                                <input type="password" class="form-control parsley-validated" data-required="true" id="pwd" name="User[password]">
                            </div>
                            <div class="col-sm-12">
                                <label><?=THelper::t('repeat_password')?><!--Повторите пароль на вход--></label>
                                <input type="password" class="form-control parsley-validated" data-equalto="#pwd" data-required="true">
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                        <div class="form-group pull-in clearfix">
                            <div class="col-sm-12">
                                <label><?=THelper::t('finance_password')?><!--Финансовый пароль--></label>
                                <input type="password" class="form-control parsley-validated" data-required="true" id="pwdF" name="User[finance_pass]">
                            </div>
                            <div class="col-sm-12">
                                <label><?=THelper::t('repeat_finance_password')?><!--Повторите финансовый пароль--></label>
                                <input type="password" class="form-control parsley-validated" data-equalto="#pwdF" data-required="true">
                            </div>
                        </div>
                        <div class="line line-dashed line-lg pull-in"></div>
                            <div class="form-group">
                                <label class="col-sm-6 padding-0"><?=THelper::t('access_account')?><!--Доступ в кабинет--></label>
                                <div class="col-sm-6">
                                    <label class="switch">
                                        <input name="User[access_account]" class="parsley-validated" type="checkbox" >
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        <div class="form-group">
                            <label class="col-sm-6 padding-0"><?=THelper::t('financial_operations')?><!--Финансовые операции--></label>
                            <div class="col-sm-6">
                                <label class="switch">
                                    <input name="User[financial_operations]" class="parsley-validated" type="checkbox" >
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 padding-0"><?=THelper::t('payment_from_the_account_of_the_goods')?><!--Оплата со счета своего товара--></label>
                            <div class="col-sm-6">
                                <label class="switch">
                                    <input name="User[pfag]" class="parsley-validated" type="checkbox">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group panel-footer text-right bg-light lter">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-s-xs']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
        </section>
    </aside>
    <aside class="aside-xl b-l b-r" id="note-list">
        <section class="vbox flex">
            <header class="header clearfix">
                <span class="pull-right m-t">
                    <button class="btn btn-dark btn-sm btn-icon" id="new-note" data-toggle="tooltip" data-placement="right" title="<?=THelper::t('new')?>">
                        <i class="fa fa-plus"></i>
                    </button>
                </span>
                <p class="h3"><?=THelper::t('notes')?><!--Заметки--></p>
                <div class="input-group m-t-sm m-b-sm">
                    <span class="input-group-addon input-sm"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control input-sm" id="search-note" placeholder="<?=THelper::t('search')?>">
                </div>
            </header>
            <section>
                <section>
                    <section>
                        <div class="padder">
                            <!-- note list -->
                            <ul id="note-items" class="list-group list-group-sp"></ul>
                            <!-- templates -->
                            <script type="text/template" id="item-template">
                                <div class="view" id="note-<%- id%>" data-id="<%- id%>">
                                    <button class="destroy close hover-action" data-id="<%- id%>">&times;</button>
                                    <div class="note-name">
                                        <strong>
                                            <%- (name && name.length) ? name : 'New note' %>
                                        </strong>
                                    </div>
                                    <div class="note-desc">
                                        <%- description.replace(name,'').length ? description.replace(name,'') : 'Добавлено админом <?php if(!empty(Yii::$app->user->id)){ echo User::findOne(Yii::$app->user->id)->name;}else{echo false;} ?>' %>
                                    </div>
                                    <span class="text-xs text-muted"><%- moment(parseInt(date)).format('MMM Do, h:mm a') %></span>
                                </div>
                            </script>
                            <!-- / template  -->
                            <p class="text-center">&nbsp;</p>
                        </div>
                    </section>
                </section>
            </section>
        </section>
    </aside>
    <aside id="note-detail">
        <script type="text/template" id="note-template">
            <section class="vbox">
                <header class="header bg-light lter bg-gradient b-b">
                    <p id="note-date"><?=THelper::t('created')?> <%- moment(parseInt(date)).format('MMM Do, h:mm a') %></p>
                </header>
                <section class="bg-light lter">
                    <section class="hbox stretch">
                        <aside>
                            <section class="vbox b-b">
                                <section class="paper">
                                    <textarea type="text" class="form-control scrollable" placeholder="<?=THelper::t('type_your_note_here')?>"><%- name %></textarea>
                                </section>
                            </section>
                        </aside>
                    </section>
                </section>
            </section>
        </script>
    </aside>
</section>
<!--Валидация-->
<?php $this->registerJsFile('js/parsley/parsley.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/parsley/parsley.extend.js',['depends'=>['app\assets\AppAsset']]); ?>
<!--endВалидация-->

<!--Заметки-->
<?php $this->registerJsFile('js/slimscroll/jquery.slimscroll.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/libs/underscore-min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/libs/backbone-min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/libs/backbone.localStorage-min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/libs/moment.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/apps/notes.js',['depends'=>['app\assets\AppAsset']]); ?>
<!--enfЗаметки-->