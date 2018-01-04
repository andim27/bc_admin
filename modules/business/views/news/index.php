<?php
    use app\components\THelper;
    $this->params['breadcrumbs'][] = THelper::t('news');
?>

<section class="hbox stretch">
    <aside class="aside-xl b-l b-r" id="note-list">
        <section class="vbox flex">
            <header class="header clearfix"  style="text-align: center">
                <p class="h1"><?=THelper::t('news')?></p>
            </header>
            <section>
                <section>
                    <section>
                        <div class="padder">
                            <!-- note list -->
                            <ul id="note-items" class="list-group list-group-sp">
                                <?php foreach($news as $n){?>
                                    <li class="list-group-item news-it hover <?= in_array($n->id, $unreadedIds) ? 'active' : ''; ?>" id="note-<?= $n->id ?>" data-id="<?= $n->id ?>">
                                        <div class="view" id="note-<?= $n->id ?>" data-id="<?= $n->id ?>">
                                            <div class="note-name">
                                                <strong>
                                                    <?= $n->title ?>
                                                </strong>
                                            </div>
                                            <div class="note-desc"></div>
                                            <span class="text-xs text-muted"><?= gmdate('d-m-Y, H:i', $n->dateOfPublication) ?></span>
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
        <section class="vbox"">
            <header class="header bg-light lter bg-gradient b-b">
                <p id="note-date"><?=THelper::t('added')?> <span class="dat"></span></p>
            </header>
            <section class="bg-light lter" style="overflow: auto;display: block;">
                <section class="hbox stretch">
                    <aside>
                        <section class="vbox b-b" style="padding: 5px;">
                            <h3 class="here" style="text-align: center"></h3>
                            <p class="cont o-a"></p>
                        </section>
                    </aside>
                </section>
            </section>
        </section>
    </aside>
</section>

<?php $this->registerJsFile('js/main/date.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/business_center_news.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
