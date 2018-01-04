<?php
    use app\components\THelper;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('users_genealogy_title'); ?></h3>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="input-group m-b">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control search_text" placeholder="<?= THelper::t('user_genealogy_search_placeholder') ?>">
        </div>
    </div>
    <div class="col-sm-1 m-b">
        <a href="#" class="btn btn-s-md btn-info search_login"><?= THelper::t('user_genealogy_search') ?></a>
    </div>
</div>
<section class="panel panel-default">
    <header class="panel-heading bg-light">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tree" class="tree" data-toggle="tab"><?= THelper::t('user_genealogy_tree') ?></a>
            </li>
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="tree">
                <div class="control_tree t col-md-2">
                    <a href="#" class="btn btn-s-md btn-default upstairs_tree" top-id="<?= $model->id; ?>"><?= THelper::t('upstairs') ?></a>
                    <a href="#" class="btn btn-s-md btn-default above_tree" parent-above=""><?= THelper::t('above') ?></a>
                    <a href="#" class="btn btn-s-md btn-default left_bottom_tree" lb-id="<?= $model->id; ?>"><?= THelper::t('left_bottom') ?></a>
                    <a href="#" class="btn btn-s-md btn-default right_bottom_tree" rb-id="<?= $model->id; ?>"><?= THelper::t('right_bottom') ?></a>
                </div>
                <div id="content_tree" class="row user_id" top-id="<?= $model->id; ?>" data-id="<?= $model->id; ?>">
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->render('context_menu') ?>
<?php $this->registerJsFile('js/main/genealogy.js', ['depends' => ['app\assets\AppAsset']]); ?>
