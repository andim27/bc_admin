<?php
    use yii\helpers\Html;
    use app\models\Users;
?>
<aside class="bg-dark  lter b-r aside-md hidden-print hidden-xs" id="nav">
    <section class="vbox">
        <section id="menu_primary" class="w-f scrollable">
            <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                <nav class="nav-primary hidden-xs">
                    <ul class="nav">
                        <?php foreach ($items as $item) { ?>

                            <?php if(Users::checkRule('showMenu',$item['key']) === true ){ ?>

                            <li <?= (!empty($item['controller']) && $currentController == $item['controller']) ? $class_a : '' ?>>

                                <?=Html::a(
                                    (!empty($item['items']) ?
                                    '<span class="pull-right">
                                          <i class="fa fa-angle-down text"></i>
                                          <i class="fa fa-angle-up text-active"></i>
                                    </span>' : '') .
                                    '<span>'. $item['label'].'</span>',
                                    $item['url'], [
                                        ((!empty($item['controller']) && $currentModule == $item['controller']) ? $class_a : '')
                                    ]
                                )?>

                                <?php if(!empty($item['items'])){ ?>
                                <ul class="nav lt">
                                    <?php foreach ($item['items'] as $subitem) {?>

                                        <?php if(Users::checkRule('showMenu',$subitem['key']) === true ){ ?>
                                        
                                        <li <?=((!empty($subitem['action']) && $currentAction == $subitem['action']) ? $class_a : '')?> >
                                        <?=Html::a(
                                            '<b class="badge bg-info pull-right non_seen_promo"></b>
                                            <i class="fa fa-angle-right"></i>
                                            <span>'.$subitem['label'].'</span>',
                                            $subitem['url'],
                                            [
                                                ((!empty($subitem['action']) && $currentAction == $subitem['action']) ? $class_a : '')
                                            ])?>
                                        </li>

                                        <?php } ?>

                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </li>

                            <?php } ?>
                        <?php } ?>

                    </ul>
                </nav>
            </div>
        </section>
    </section>
</aside>