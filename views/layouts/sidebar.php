<?php

use yii\helpers\Url;
use app\components\THelper;
?>
<nav class="nav-primary hidden-xs">
    <ul class="nav">
        <!-- <?php //Yii::$app->controller->module->id . '/'. Yii::$app->controller->id . '/' . Yii::$app->controller->action->id ?>
        <li  class="active">
          <a href="index.html" class="active">
            <i class="fa fa-dashboard icon">
              <b class="bg-danger"></b>
            </i>
            <span>Workset</span>
          </a>
        </li>-->
        <li <?= (Yii::$app->controller->id=='site')?$class_a:'' ?>>
            <a href="<?= Url::to(['/site/index']) ?>" <?= (Yii::$app->controller->id=='site')?$class_a:'' ?>>
                <i class="fa fa-check-square icon">
                    <b class="bg-danger"></b>
                </i>
                <span><?=THelper::t('home')?><!--Главная--></span>
            </a>
        </li>
        <li <?= (Yii::$app->controller->module->id=='users')?$class_a:'' ?>>
            <a href="#users" <?= (Yii::$app->controller->module->id=='users')?$class_a:'' ?>>
                <i class="fa fa-users icon">
                    <b class="bg-warning"></b>
                </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                <span><?=THelper::t('users')?><!--Пользователи--></span>
            </a>
            <ul class="nav lt">
                <li <?= (Yii::$app->controller->id=='user')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/users/user']) ?>" <?= (Yii::$app->controller->id=='user')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('table_users')?><!--Таблица пользователей--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='edit')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/users/edit']) ?>" <?= (Yii::$app->controller->id=='edit')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('edit_profile')?><!--Редактирование профиля--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='signin')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/users/signin']) ?>" <?= (Yii::$app->controller->id=='signin')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('sign_in_as_user')?></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='change')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/users/change']) ?>" <?= (Yii::$app->controller->id=='change')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('change_parent_or_sponsor')?></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='result')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/users/result']) ?>" <?= (Yii::$app->controller->id=='result')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('result_information_about_user')?></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='download')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/users/download']) ?>" <?= (Yii::$app->controller->id=='download')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('uploaded_documents')?></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='users_buy')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/users/users-buy']) ?>" <?= (Yii::$app->controller->id=='users_buy')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('users_buy')?><!--Покупки пользователей--></span>
                    </a>
                </li>
            </ul>
        </li>
        <li <?= (Yii::$app->controller->module->id=='reports')?$class_a:'' ?>>
            <a href="#reports" <?= (Yii::$app->controller->module->id=='reports')?$class_a:'' ?>>
                <i class="fa fa-file-text icon">
                    <b class="bg-warning"></b>
                </i>
                <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                </span>
                <span><?=THelper::t('reports')?><!--Отчеты--></span>
            </a>
            <ul class="nav lt">
                <li <?= (Yii::$app->controller->id=='admin-logs')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/admin-logs']) ?>" <?= (Yii::$app->controller->id=='admin-logs')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('logs_work_on_admin')?><!--Логи работ по админке--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='default')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/reports/default']) ?>" <?= (Yii::$app->controller->id=='default')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('remote_cell')?><!--Удаленные ячейки--></span>
                    </a>
                </li>
            </ul>
        </li>
        <li <?= (Yii::$app->controller->module->id=='settings')?$class_a:'' ?>>
            <a href="#settings" <?= (Yii::$app->controller->module->id=='settings')?$class_a:'' ?>>
                <i class="fa fa-gears icon">
                    <b class="bg-success"></b>
                </i>
                        <span class="pull-right">
                          <i class="fa fa-angle-down text"></i>
                          <i class="fa fa-angle-up text-active"></i>
                        </span>
                <span><?=THelper::t('settings')?><!--Настройки--></span>
            </a>
            <ul class="nav lt">
                <li <?= (Yii::$app->controller->id=='locale')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/locale']) ?>" <?= (Yii::$app->controller->id=='locale')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('locales')?><!--Языковые стандарты--></span>
                    </a>
                </li>
                <!--<li >
                  <a href="<?/*= Url::to(['/settings/menu']) */?>">
                    <b class="badge bg-info pull-right">369</b>
                    <i class="fa fa-angle-right"></i>
                    <span>Пункты меню</span>
                  </a>
                </li>-->
                <li <?= (Yii::$app->controller->id=='admins')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/admins']) ?>" <?= (Yii::$app->controller->id=='admins')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('administrators')?><!--Администраторы--></span>
                    </a>
                </li>

                <li <?= (Yii::$app->controller->id=='administrator-rights')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/administrator-rights']) ?>" <?= (Yii::$app->controller->id=='administrator-rights')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('administrator_rights')?><!--Права администраторов--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='emergency-command')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/emergency-command']) ?>" <?= (Yii::$app->controller->id=='administrator-rights')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('emergency_command')?><!--Аварийные команды--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='country')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/country']) ?>" <?= (Yii::$app->controller->id=='country')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('regions')?><!--Регионы--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='password')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/password']) ?>" <?= (Yii::$app->controller->id=='password')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('change_my_password')?><!--Сменить мой пароль--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='generator')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/generator']) ?>" <?= (Yii::$app->controller->id=='generator')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('generator')?></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='logo')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/logo']) ?>" <?= (Yii::$app->controller->id=='logo')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('logos')?><!--Логотипы--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='binar')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/binar']) ?>" <?= (Yii::$app->controller->id=='binar')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('binar')?><!--Бинар--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='links')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/links']) ?>" <?= (Yii::$app->controller->id=='links')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('back_office_groups_links')?></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='support')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/settings/support']) ?>" <?= (Yii::$app->controller->id=='support')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('support')?></span>
                    </a>
                </li>
            </ul>
        </li>
        <li <?= (Yii::$app->controller->id=='product')?$class_a:'' ?>>
            <a href="<?= Url::to(['/product/index']) ?>" <?= (Yii::$app->controller->id=='product')?$class_a:'' ?>>
                <i class="fa fa-file-text icon">
                    <b class="bg-danger"></b>
                </i>
                <span><?=THelper::t('products')?><!--Товары--></span>
            </a>
        </li>
        <li <?= (Yii::$app->controller->id=='email-list')?$class_a:'' ?>>
            <a href="<?= Url::to(['/email-list']) ?>" <?= (Yii::$app->controller->id=='email-list')?$class_a:'' ?>>
                <i class="fa fa-file-text icon">
                    <b class="bg-success"></b>
                </i>
                <span><?=THelper::t('email_templates')?><!--Email шаблоны--></span>
            </a>
        </li>
        <li <?= (Yii::$app->controller->module->id=='bekofis')?$class_a:'' ?>>
            <a href="#bekofis" <?= (Yii::$app->controller->module->id=='bekofis')?$class_a:'' ?>>
                <i class="fa fa-file-text icon">
                    <b class="bg-primary"></b>
                </i>
                <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                </span>
                <span><?=THelper::t('back_office')?><!--Бэкофис--></span>
            </a>
            <ul class="nav lt">
                <li <?= (Yii::$app->controller->id=='news')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/news']) ?>" <?= (Yii::$app->controller->id=='news')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('news')?><!--Новости--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='promotions')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/promotions/promotions']) ?>" <?= (Yii::$app->controller->id=='promotions')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('promotions')?><!--Рекламные акции--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='timesheet')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/timesheet']) ?>" <?= (Yii::$app->controller->id=='timesheet')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('schedule_online_conferences')?><!--Расписание онлайн-конференций--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='resources')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/resources']) ?>" <?= (Yii::$app->controller->id=='resources')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('all_resources')?></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='marketing')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/marketing']) ?>" <?= (Yii::$app->controller->id=='marketing')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('marketing_plan')?><!--Маркетинг-план--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='carrier')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/carrier']) ?>" <?= (Yii::$app->controller->id=='carrier')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('career_plan')?><!--Карьерный план--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='price')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/price']) ?>" <?= (Yii::$app->controller->id=='price')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('price_list')?><!--Прайс-лист--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='more')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/more']) ?>" <?= (Yii::$app->controller->id=='more')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('more_about_referees')?><!--Подробнее о рекомендателе--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='conditions')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/conditions']) ?>" <?= (Yii::$app->controller->id=='conditions')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('conditions_of_participation_in_the_program')?><!--Условия участия в программе--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='first-steps')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/first-steps']) ?>" <?= (Yii::$app->controller->id=='first-steps')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('video_recording_at_getting_started')?><!--Видео при регистрации “Первые шаги”--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='buttons')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/bekofis/buttons']) ?>" <?= (Yii::$app->controller->id=='buttons')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('buttons_in_the_form_of_registration')?><!--Кнопки в форме регистрации--></span>
                    </a>
                </li>
            </ul>
        </li>

        <li <?= (Yii::$app->controller->module->id=='handbook')?$class_a:'' ?>>
            <a href="#handbook" <?= (Yii::$app->controller->module->id=='handbook')?$class_a:'' ?>>
                <i class="fa fa-file-text icon">
                    <b class="bg-info"></b>
                </i>
                <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                </span>
                <span><?=THelper::t('references')?><!--Справочники--></span>
            </a>
            <ul class="nav lt">
                <li <?= (Yii::$app->controller->id=='shares')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/handbook/shares']) ?>" <?= (Yii::$app->controller->id=='shares')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('shares')?><!--Акции--></span>
                    </a>
                </li>
                <li <?= (Yii::$app->controller->id=='carrier')?$class_a:'' ?>>
                    <a href="<?= Url::to(['/handbook/carrier']) ?>" <?= (Yii::$app->controller->id=='carrier')?$class_a:'' ?>>
                        <i class="fa fa-angle-right"></i>
                        <span><?=THelper::t('careers')?><!--Карьера--></span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>