<?php
    use yii\helpers\Url;
    use app\components\THelper;
?>
<aside class="bg-dark ?> lter b-r aside-md hidden-print hidden-xs" id="nav">
    <section class="vbox">
        <section id="menu_primary" class="w-f scrollable">
            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                <nav class="nav-primary hidden-xs">
                    <ul class="nav">
                        <li <?= ($currentController == 'default') ? $class_a : '' ?>>
                            <a href="<?= Url::to(['/business/default']) ?>" <?= ($currentModule == 'default') ? $class_a : '' ?>>
                                <span><?= THelper::t('sidebar_home') ?></span>
                            </a>
                        </li>
                        <li <?= ($currentController == 'user') ? $class_a : '' ?>>
                            <a href="#user" <?= ($currentModule == 'user') ? $class_a : '' ?>>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span><?= THelper::t('sidebar_users') ?></span>
                            </a>
                            <ul class="nav lt">
                                <li <?= ($currentAction == 'user') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/user']) ?>" <?= ($currentAction == 'user') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_users_users')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'qualification') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/user/qualification']) ?>" <?= ($currentAction == 'qualification') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_users_qualification')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'genealogy') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/user/genealogy']) ?>" <?= ($currentAction == 'genealogy') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_users_genealogy')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'purchase') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/user/purchase']) ?>" <?= ($currentAction == 'purchase') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_users_purchases')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'commission') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/user/commission']) ?>" <?= ($currentAction == 'commission') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_users_commission')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'info') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/user/info']) ?>" <?= ($currentAction == 'info') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_users_info')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'docs') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/user/docs']) ?>" <?= ($currentAction == 'docs') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_users_docs')?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li <?= ($currentController == 'backoffice') ? $class_a : '' ?>>
                            <a href="#backoffice" <?= ($currentModule == 'backoffice') ? $class_a : '' ?>>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span><?= THelper::t('sidebar_backoffice') ?></span>
                            </a>
                            <ul class="nav lt">
                                <li <?= ($currentAction == 'user') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/news']) ?>" <?= ($currentAction == 'news') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_news')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'promotion') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/promotion']) ?>" <?= ($currentAction == 'promotion') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_promotion')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'conference') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/conference']) ?>" <?= ($currentAction == 'conference') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_conference')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'resource') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/resource']) ?>" <?= ($currentAction == 'resource') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_resource')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'marketing') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/marketing']) ?>" <?= ($currentAction == 'marketing') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_marketing')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'career') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/career']) ?>" <?= ($currentAction == 'career') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_career')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'price') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/price']) ?>" <?= ($currentAction == 'price') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_price')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'charity') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/charity']) ?>" <?= ($currentAction == 'charity') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_charity')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'instruction') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/instruction']) ?>" <?= ($currentAction == 'instruction') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_instruction')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'document') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/document']) ?>" <?= ($currentAction == 'document') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_document')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'agreement') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/agreement']) ?>" <?= ($currentAction == 'agreement') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_agreement')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'videofs') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/videofs']) ?>" <?= ($currentAction == 'videofs') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_videofs')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'regbutton') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/backoffice/regbutton']) ?>" <?= ($currentAction == 'regbutton') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_backoffice_regbutton')?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li <?= ($currentController == 'setting') ? $class_a : '' ?>>
                            <a href="#setting" <?= ($currentModule == 'setting') ? $class_a : '' ?>>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span><?= THelper::t('sidebar_settings') ?></span>
                            </a>
                            <ul class="nav lt">
                                <li <?= ($currentAction == 'admin') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/setting/admin']) ?>" <?= ($currentAction == 'admin') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_settings_admin')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'admin-rules') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/setting/admin-rules']) ?>" <?= ($currentAction == 'admin-rules') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_settings_admin_rules')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'translation') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/setting/translation']) ?>" <?= ($currentAction == 'translation') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_settings_translation')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'password') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/setting/password']) ?>" <?= ($currentAction == 'password') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_settings_password')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'whitelabel') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/setting/whitelabel']) ?>" <?= ($currentAction == 'whitelabel') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_settings_whitelabel')?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li <?= ($currentController == 'lottery') ? $class_a : '' ?>>
                            <a href="#setting" <?= ($currentModule == 'lottery') ? $class_a : '' ?>>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span><?= THelper::t('sidebar_lottery') ?></span>
                            </a>
                            <ul class="nav lt">
                                <li <?= ($currentAction == 'admin') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/lottery/index']) ?>" <?= ($currentAction == 'index') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_lottery_index')?></span>
                                    </a>
                                </li>
                                <li <?= ($currentAction == 'admin-rules') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/lottery/rules']) ?>" <?= ($currentAction == 'rules') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_lottery_rules')?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li <?= ($currentController == 'promotion') ? $class_a : '' ?>>
                            <a href="#setting" <?= ($currentModule == 'promotion') ? $class_a : '' ?>>
                                <span class="pull-right">
                                  <i class="fa fa-angle-down text"></i>
                                  <i class="fa fa-angle-up text-active"></i>
                                </span>
                                <span><?= THelper::t('sidebar_promotion') ?></span>
                            </a>
                            <ul class="nav lt">
                                <li <?= ($currentAction == 'travel') ? $class_a : '' ?>>
                                    <a href="<?= Url::to(['/business/promotion/travel/']) ?>" <?= ($currentAction == 'travel') ? $class_a : '' ?>>
                                        <b class="badge bg-info pull-right non_seen_promo"></b>
                                        <i class="fa fa-angle-right"></i>
                                        <span><?=THelper::t('sidebar_promotion_travel')?></span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php /**
                        <?php if (isset($items->news) && $items->news) { ?>
                            <li <?= ($currentController == 'news') ? $class_a : '' ?>>
                                <a href="<?= Url::to(['/business/news']) ?>" <?= ($currentModule == 'news') ? $class_a : '' ?>>
                                    <b class="badge bg-info pull-right non_seen"></b>
                                    <i class="fa fa-calendar icon">
                                        <b class="bg-menu-93C47D"></b>
                                    </i>
                                    <span><?=THelper::t('news')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->information) && $items->information) { ?>
                            <li <?= ($currentController == 'information') ? $class_a : '' ?>>
                                <a href="#information" <?= ($currentController == 'information') ? $class_a : '' ?>>
                                    <i class="fa fa-info-circle icon">
                                        <b class="bg-menu-00FFFF"></b>
                                    </i>
                                    <span class="pull-right">
                                      <i class="fa fa-angle-down text"></i>
                                      <i class="fa fa-angle-up text-active"></i>
                                    </span>
                                    <span><?=THelper::t('information')?></span>
                                </a>
                                <ul class="nav lt">
                                    <?php if (isset($items->promotions) && $items->promotions) { ?>
                                        <li <?= ($currentAction == 'promotions')?$class_a:'' ?>>
                                            <a href="<?= Url::to(['/business/information/promotions']) ?>" <?= ($currentAction == 'promotions') ? $class_a : '' ?>>
                                                <b class="badge bg-info pull-right non_seen_promo"></b>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('promotions')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->conferenceSchedule) && $items->conferenceSchedule) { ?>
                                        <li <?= ($currentAction == 'timesheet') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/information/timesheet']) ?>" <?= ($currentAction == 'timesheet') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('schedule_online_conferenc')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->marketingPlan) && $items->marketingPlan) { ?>
                                        <li <?= ($currentAction == 'marketing') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/information/marketing']) ?>" <?= ($currentAction == 'marketing') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('marketing_plan')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->careerPlan) && $items->careerPlan) { ?>
                                        <li <?= ($currentAction == 'carrier') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/information/carrier']) ?>" <?= ($currentAction == 'carrier') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('career_plan')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->priceList) && $items->priceList) { ?>
                                        <li <?= ($currentAction == 'price') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/information/price']) ?>" <?= ($currentAction == 'price') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('price_list')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li <?= ($currentAction == 'instructions') ? $class_a : '' ?>>
                                        <a href="<?= Url::to(['/business/information/instructions']) ?>" <?= ($currentAction == 'instructions') ? $class_a : '' ?>>
                                            <i class="fa fa-angle-right"></i>
                                            <span><?=THelper::t('information_instructions')?></span>
                                        </a>
                                    </li>
                                    <li <?= ($currentAction == 'documents') ? $class_a : '' ?>>
                                        <a href="<?= Url::to(['/business/information/documents']) ?>" <?= ($currentAction == 'documents') ? $class_a : '' ?>>
                                            <i class="fa fa-angle-right"></i>
                                            <span><?=THelper::t('information_documents')?></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->command) && $items->command) { ?>
                            <li <?= ($currentController == 'team') ? $class_a : '' ?>>
                                <a href="#team" <?= ($currentController == 'team') ? $class_a : '' ?>>
                                    <i class="fa fa-users icon">
                                        <b class="bg-menu-FF0000"></b>
                                    </i>
                    <span class="pull-right">
                        <i class="fa fa-angle-down text"></i>
                        <i class="fa fa-angle-up text-active"></i>
                    </span>
                                    <span><?=THelper::t('command')?></span>
                                </a>
                                <ul class="nav lt">
                                    <?php if (isset($items->genealogy) && $items->genealogy) { ?>
                                        <li <?= ($currentAction == 'genealogy') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/team/genealogy']) ?>" <?= ($currentAction == 'genealogy') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('genealogy')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->teamGeography) && $items->teamGeography) { ?>
                                        <li <?= ($currentAction == 'geography') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/team/geography']) ?>" <?= ($currentAction == 'geography') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('geography_structure')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->personalInvitations) && $items->personalInvitations) { ?>
                                        <li <?= ($currentAction == 'self') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/team/self']) ?>" <?= ($currentAction == 'self') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('personal_invitation')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->career) && $items->career) { ?>
                            <li <?= ($currentController == 'carrier') ? $class_a : '' ?>>
                                <a href="#carrier" <?= ($currentController == 'carrier') ? $class_a : '' ?>>
                                    <i class="fa fa-trophy icon">
                                        <b class="bg-menu-4A86E8"></b>
                                    </i>
                    <span class="pull-right">
                      <i class="fa fa-angle-down text"></i>
                      <i class="fa fa-angle-up text-active"></i>
                    </span>
                                    <span><?=THelper::t('careers')?></span>
                                </a>
                                <ul class="nav lt">
                                    <?php if (isset($items->status) && $items->status) { ?>
                                        <li <?= ($currentAction == 'status') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/carrier/status']) ?>" <?= ($currentAction == 'status') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('status')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->certification) && $items->certification) { ?>
                                        <li <?= ($currentAction == 'certificate') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/carrier/certificate']) ?>" <?= ($currentAction == 'certificate') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('certification')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->statusHistory) && $items->statusHistory) { ?>
                                        <li <?= ($currentAction == 'history') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/carrier/history']) ?>" <?= ($currentAction == 'history') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('status_history')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->statistics) && $items->statistics) { ?>
                            <li <?= ($currentController == 'statistic') ? $class_a : '' ?>>
                                <a href="<?= Url::to(['/business/statistic']) ?>" <?= ($currentModule == 'statistic') ? $class_a : '' ?>>
                                    <i class="fa fa-bar-chart-o icon">
                                        <b class="bg-menu-DD7E6B"></b>
                                    </i>
                                    <span><?=THelper::t('statistic')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <li <?= ($currentController == 'sale') ? $class_a : '' ?>>
                            <a href="<?= Url::to(['/business/sale']) ?>" <?= ($currentModule == 'sale') ? $class_a : '' ?>>
                                <i class="fa fa-shopping-cart icon">
                                    <b class="bg-menu-1DFF00"></b>
                                </i>
                                <span><?=THelper::t('sale')?></span>
                            </a>
                        </li>
                        <?php if (isset($items->finance) && $items->finance) { ?>
                            <li <?= ($currentController == 'finance') ? $class_a : '' ?>>
                                <a href="<?= Url::to(['/business/finance']) ?>" <?= ($currentModule == 'finance') ? $class_a : '' ?>>
                                    <i class="fa fa-money icon">
                                        <b class="bg-menu-F9CB9C"></b>
                                    </i>
                                    <span><?=THelper::t('finance')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->charity) && $items->charity) { ?>
                            <li <?= ($currentController == 'charity') ? $class_a : '' ?>>
                                <a href="<?= Url::to(['/business/charity']) ?>" <?= ($currentModule == 'charity') ? $class_a : '' ?>>
                                    <i class="fa fa-medkit icon">
                                        <b class="bg-menu-CFE2F3"></b>
                                    </i>
                                    <span><?=THelper::t('charity')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->resources) && $items->resources) { ?>
                            <li <?= ($currentController == 'resource') ? $class_a : '' ?>>
                                <a href="<?= Url::to(['/business/resource']) ?>" <?= ($currentModule == 'resource') ? $class_a : '' ?>>
                                    <i class="fa fa-globe icon">
                                        <b class="bg-menu-B4A7D6"></b>
                                    </i>
                                    <span><?=THelper::t('all_resources')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->documentsUpload) && $items->documentsUpload) { ?>
                            <li <?= ($currentController == 'uploaded') ? $class_a : '' ?>>
                                <a href="<?= Url::to(['/business/uploaded']) ?>" <?= ($currentModule == 'uploaded') ? $class_a : '' ?>>
                                    <i class="fa fa-file-text-o icon">
                                        <b class="bg-menu-FFF2CC"></b>
                                    </i>
                                    <span><?=THelper::t('documents_upload')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->settings) && $items->settings) { ?>
                            <li <?= ($currentController == 'setting') ? $class_a : '' ?>>
                                <a href="#setting" <?= ($currentController == 'setting') ? $class_a : '' ?>>
                                    <i class="fa fa-cogs icon">
                                        <b class="bg-menu-C27BA0"></b>
                                    </i>
                    <span class="pull-right">
                      <i class="fa fa-angle-down text"></i>
                      <i class="fa fa-angle-up text-active"></i>
                    </span>
                                    <span><?=THelper::t('settings')?></span>
                                </a>
                                <ul class="nav lt">
                                    <?php if (isset($items->profile) && $items->profile) { ?>
                                        <li <?= ($currentAction == 'profile') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/setting/profile']) ?>" <?= ($currentAction == 'profile') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('profile')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->mergingCells) && $items->mergingCells) { ?>
                                        <li <?= ($currentAction == 'unioncell') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/setting/unioncell']) ?>" <?= ($currentAction == 'unioncell') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('merging_cells')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->passwords) && $items->passwords) { ?>
                                        <li <?= ($currentAction == 'passwords') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/setting/passwords']) ?>" <?= ($currentAction == 'passwords') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('passwords')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if (isset($items->alerts) && $items->alerts) { ?>
                                        <li <?= ($currentAction == 'alert') ? $class_a : '' ?>>
                                            <a href="<?= Url::to(['/business/setting/alert']) ?>" <?= ($currentAction == 'alert') ? $class_a : '' ?>>
                                                <i class="fa fa-angle-right"></i>
                                                <span><?=THelper::t('alert')?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($supportHref) { ?>
                            <li>
                                <a href="<?= $supportHref ?>" target="_blank">
                                    <i class="fa fa-comments-o icon">
                                        <b class="bg-menu-A4C2F4"></b>
                                    </i>
                                    <span><?=THelper::t('support')?></span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if (isset($items->notes) && $items->notes) { ?>
                            <li id="notes" <?= ($currentController == 'notes') ? $class_a : '' ?>>
                                <a href="<?= Url::to(['/business/notes']) ?>" <?= ($currentModule == 'notes') ? $class_a : '' ?>>
                                    <i class="fa fa-file-text-o icon">
                                        <b class="bg-menu-B6D7A8"></b>
                                    </i>
                                    <span><?=THelper::t('notes')?><!--Заметки--></span>
                                </a>
                            </li>
                        <?php } ?>
                        <li id="tour" class="hidden-xs">
                            <a href="javascript:void(0)">
                                <i class="fa fa-eye icon">
                                    <b class="bg-menu-F4CCCC"></b>
                                </i>
                                <span><?=THelper::t('tour')?></span>
                            </a>
                        </li>
 **/ ?>
                    </ul>
                </nav>
            </div>
        </section>
    </section>
</aside>