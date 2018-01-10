<?php

use app\components\THelper;

$this->title = THelper::t('notes');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->session->getFlash('error');
?>

<section class="hbox stretch">
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
                            <ul id="note-items" class="list-group list-group-sp">
                                <?php echo $this->render('note', [
                                    'notes' => $notes,
                                    'user' => $user
                                ]); ?>
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
                <p id="note-date"><?= THelper::t('added') ?> <span class="dat"></span></p>
            </header>
            <section class="bg-light lter">
                <section class="hbox stretch">
                    <aside>
                        <section class="vbox b-b">
                            <section class="paper">
                                <textarea type="text" id="area" class="form-control scrollable" placeholder="<?=THelper::t('type_your_note_here')?>"></textarea>
                            </section>
                        </section>
                    </aside>
                </section>
            </section>
        </section>
    </aside>
</section>
<input type="hidden" id = "hid">

<!--Валидация-->
<?php $this->registerJsFile('js/parsley/parsley.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/parsley/parsley.extend.js',['depends'=>['app\assets\AppAsset']]); ?>
<!--endВалидация-->

<!--Заметки-->
<?php $this->registerJsFile('js/main/business_center_notes.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
<!--enfЗаметки-->