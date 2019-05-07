<?php
    use yii\helpers\Html;
    use app\models\Users;
    use app\models\Settings;
    use MongoDB\BSON\ObjectID;

    $rule_admin_menu = Yii::$app->cache->get('rule_admin_menu');
    if ($rule_admin_menu == false) {
        $can_main_menu = Settings::find()->where(['_id'=> new ObjectID('576912f443f9c4f46bc23a0d')])->one();
    } else {
        $can_main_menu['adminMainMenu'] = $rule_admin_menu;
    }

?>
<aside class="bg-dark  lter b-r aside-md hidden-print hidden-xs" id="nav">
    <section class="vbox">
        <section id="menu_primary" class="w-f scrollable">
            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                <nav class="nav-primary hidden-xs">
                    <ul class="nav">
                        <?php foreach ($items as $item) { ?>
                            <?php
                                  $can_show_item = true;
                                  $can_show_item = @(!in_array($item['key'],(array)$can_main_menu['adminMainMenu']["hideMenu"]))
                            ?>
                            <?php
                            if ($can_show_item == true) {
                                if (Users::checkRule('showMenu', $item['key']) === true) { ?>
                                    <li <?= (!empty($item['controller']) && $currentController == $item['controller']) ? $class_a : '' ?>>
                                        <?= Html::a(
                                            (!empty($item['items']) ?
                                                '<span class="pull-right">
                                          <i class="fa fa-angle-down text"></i>
                                          <i class="fa fa-angle-up text-active"></i>
                                    </span>' : '') .
                                            '<span>' . $item['label'] . '</span>',
                                            $item['url'], [
                                                ((!empty($item['controller']) && $currentModule == $item['controller']) ? $class_a : '')
                                            ]
                                        ) ?>
                                        <?php if (!empty($item['items'])) { ?>
                                            <ul class="nav lt">
                                                <?php foreach ($item['items'] as $subitem) { ?>
                                                    <?php
                                                     $can_show_sub_item = true;
                                                     $can_show_sub_item = @(!in_array($subitem['key'],(array)$can_main_menu['adminMainMenu']["hideMenu"]));
                                                     if ($can_show_sub_item == true) {
                                                         if (Users::checkRule('showMenu', $subitem['key']) === true) { ?>
                                                             <li <?= ((!empty($subitem['action']) && $currentAction == $subitem['action']) ? $class_a : '') ?> >
                                                                 <?= Html::a(
                                                                     '<b class="badge bg-info pull-right non_seen_promo"></b>
                                            <i class="fa fa-angle-right"></i>
                                            <span>' . $subitem['label'] . '</span>',
                                                                     $subitem['url'],
                                                                     [
                                                                         ((!empty($subitem['action']) && $currentAction == $subitem['action']) ? $class_a : '')
                                                                     ]) ?>
                                                             </li>
                                                         <?php }
                                                     }//--can_show_item
                                                       ?>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </li>
                                <?php }
                            }//--can_show_item
                              ?>
                        <?php } ?>

                    </ul>
                </nav>
            </div>
        </section>
    </section>
</aside>