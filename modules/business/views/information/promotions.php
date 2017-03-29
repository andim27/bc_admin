<?php
    use app\components\THelper;
    $this->title = THelper::t('promotions');
    $this->params['breadcrumbs'][] = $this->title;
?>
<section class="hbox stretch">
    <aside class="aside-xl b-l b-r" id="note-list">
        <section class="vbox flex">
            <header class="header clearfix"  style="text-align: center">
                <p class="h1"><?=THelper::t('promotions')?></p>
            </header>
            <section>
                <section>
                    <section>
                        <div class="padder">
                            <ul id="note-items" class="list-group list-group-sp">
                                <?php foreach($promotions as $p) { ?>
                                    <li class="list-group-item prom-it hover <?= in_array($p->id, $unreadedIds) ? 'active' : ''; ?>" id="note-<?= $p->id ?>" data-id="<?= $p->id ?>">
                                        <div class="view" id="note-<?= $p->id ?>" data-id="<?= $p->id ?>">
                                            <div class="note-name">
                                                <strong>
                                                    <?= $p->title ?>
                                                </strong>
                                            </div>
                                            <div class="note-desc"></div>
                                            <span class="text-xs text-muted"><?= gmdate('d-m-Y, H:i', $p->dateCreate) ?></span>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                            <p class="text-center">&nbsp;</p>
                        </div>
                    </section>
                </section>
            </section>
        </section>
    </aside>
    <aside id="note-detail" class="op" style="visibility: hidden">
        <section class="vbox">
            <header class="header bg-light lter bg-gradient b-b">
                <p id="note-date"><?= THelper::t('promotion_is_valid_from')?> <span class="da1"></span> <?= THelper::t('who_is_charged') ?> <span class="da2"></span></p>
            </header>
            <section class="bg-light lter" style="overflow: auto;display: block;">
                <section class="hbox stretch">
                    <aside>
                        <section class="vbox b-b" style="padding: 5px">
                            <h3 class="he" style="text-align: center"></h3>
                            <p class="co o-a"></p>
                        </section>
                    </aside>
                </section>
            </section>
        </section>
    </aside>
</section>

<!--Валидация-->
<?php $this->registerJsFile('js/parsley/parsley.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/parsley/parsley.extend.js',['depends'=>['app\assets\AppAsset']]); ?>
<!--endВалидация-->

<!--Заметки-->
<?php $this->registerJsFile('js/libs/underscore-min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/libs/backbone-min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/libs/backbone.localStorage-min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/libs/moment.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/business_center_promotions.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
<!--enfЗаметки-->