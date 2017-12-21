<?php
    use app\components\THelper;
    use yii\helpers\Html;
    $this->title = THelper::t('genealogy');
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-5">
        <div class="user-panel-body bg-danger">
            <a href="#" class="thumb pull-left m-r">
                <img src="<?= $model->avatar ? $model->avatar : '/images/avatar_default.png'; ?>" class="img-circle">
            </a>
            <div class="clear">
                <h4 class="user_id main_user" top-id="<?= $model->id; ?>" data-id="<?= $model->id; ?>"><?= $model->username; ?></h4>
                <small class="block"><?= THelper::t('setting_construction_of_structure') ?></small>
            </div>
        </div>
        <div class="panel">
            <div class="row p-t-10 p-b-5">
                <div class="col-sm-7">
                    <label class="padding">
                        <i class="fa fa-users icon">
                            <b class="bg-success"></b>
                        </i>
                        <?= THelper::t('send_registration') ?>
                    </label>
                </div>
                <div class="btn-group pull-right col-sm-5">
                    <button type="button" class="btn btn-default <?= ($model->sideToNextUser == 1) ? 'active' : ""; ?> sideToNextUser" data-side='1'><?= THelper::t('Left') ?></button>
                    <button type="button" class="btn btn-default <?= ($model->sideToNextUser == 0) ? 'active' : ""; ?> sideToNextUser" data-side='0'><?= THelper::t('Right') ?></button>
                </div>
            </div>
            <div class="line_t"></div>
            <div class="row p-t-10 p-b-5">
                <div class="col-sm-7">
                    <label class="padding">
                        <i class="fa fa-random"></i>
                        <?= THelper::t('manual_control') ?>
                    </label>
                </div>
                <div class="col-sm-5">
                    <label class="switch">
                        <input type="checkbox" <?= $model->settings->manualRegistrationControl == 1 ? 'checked="checked"' : '' ?>>
                        <span></span>
                        <div id="manual-registration-error" style="color: #a94442; display: none;"></div>
                    </label>
                </div>
            </div>
            <div class="line_t"></div>
            <div class="row p-t-10 p-b-5">
                <div class="col-sm-7">
                    <label class="padding">
                        <i class="fa fa-male"></i>
                        <?= THelper::t('build_a_login') ?>
                    </label>
                </div>
                <div class="col-sm-5">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= Html::textInput('login_list', ($model->settings->manualRegistrationControl == 1 && isset($model->nextRegistration->username) && $model->nextRegistration->username) ? $model->nextRegistration->username : '', ['data-next' => (isset($model->nextRegistration->username) && $model->nextRegistration->username) ? $model->nextRegistration->username : '', 'id' => 'login-list', 'class' => 'form-control block limit', 'disabled' => 'disabled']) ?>
                            <div class="help-block" id="login-list-error" style="display: none;"></div>
                        </div>
                        <div class="col-sm-6">
                            <?= Html::button('<i class="fa fa-floppy-o" aria-hidden="true"></i>', ['id' => 'manual-reg-save-btn', 'class' => 'btn btn-danger']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="partner_data" class="col-md-3">
        <div class="clearfix m-b">
            <a href="#" class="pull-left thumb m-r">
                <img id="partner-data-avatar" src="<?= $model->avatar ? $model->avatar : '/images/avatar_default.png'; ?>" class="img-circle">
            </a>
            <div class="clear">
                <div class="h3 m-t-xs m-b-xs" id="partner-data-username">
                    <?= $model->username ?>
                </div>
                <small class="text-muted"><?= THelper::t('status') ?>: <span id="partner-data-status"><?= THelper::t('rank_' . $model->rank) ?></span></small>
            </div>
        </div>
        <div class="panel wrapper panel-success">
            <div class="row">
                <div class="col-xs-4 text-center">
                    <a href="#">
                        <span class="m-b-xs h4 block" id="left-side-number-users"><?= $model->leftSideNumberUsers; ?></span> <small class="text-muted"><?= THelper::t('left_structure') ?></small>
                    </a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">
                        <span class="m-b-xs h4 block" id="number-users"><?= $model->rightSideNumberUsers + $model->leftSideNumberUsers; ?></span> <small class="text-muted"><?= THelper::t('structure') ?></small>
                    </a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">
                        <span class="m-b-xs h4 block" id="right-side-number-users"><?= $model->rightSideNumberUsers; ?></span>
                        <small class="text-muted"><?= THelper::t('right_structure') ?></small>
                    </a>
                </div>
            </div>
        </div>
        <div id="user-side-0" class="btn-group btn-group-justified m-b" style="display: none;">
            <span class="btn btn-info btn-rounded">
                <span class="text"> <?= THelper::t('internal_structure') ?> </span>
            </span>
            <span class="btn btn-primary btn-rounded">
                <span class="text"> <?= THelper::t('sponsorship') ?> </span>
            </span>
        </div>
        <div id="user-side-1" class="btn-group btn-group-justified m-b" style="display: none;">
            <span class="btn btn-primary btn-rounded">
                <span class="text"> <?= THelper::t('sponsorship') ?> </span>
            </span>
            <span class="btn btn-info btn-rounded">
                <span class="text"> <?= THelper::t('internal_structure') ?> </span>
            </span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel clearfix user-panel-body" style="border: 1px solid #4cc0c1;">
            <?php if ($nextReg) { ?>
                <a href="#" class="thumb pull-right m-r">
                    <img src="<?= $nextReg->avatar ? $nextReg->avatar : '/images/avatar_default.png'; ?>" class="img-circle">
                </a>
                <div class="clear">
                    <label class="text-info"> <?= THelper::t('next_registration') ?></label>
                    <small class="block text-muted" id="next-reg-username"><?= $nextReg->username ? $nextReg->username : THelper::t('login_not_found') ?></small>
                    <a href="#" class="btn btn-xs btn-success m-t-xs" id="search-reg-username"><?= THelper::t('find') ?></a>
                </div>
            <?php } else { ?>
                <a href="#" class="thumb pull-right m-r">
                    <img src="/images/avatar_default.png" class="img-circle">
                </a>
                <div class="clear">
                    <label class="text-info"> <?= THelper::t('next_registration') ?></label>
                    <small class="block text-muted"><?= THelper::t('login_not_found') ?></small>
                </div>
            <?php } ?>
        </div>
        <div class="col-sm-7">
            <div class="input-group m-b">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_text" placeholder="<?= THelper::t('search') ?>">
            </div>
        </div>
        <div class="col-sm-5 m-b">
            <a href="#" class="btn btn-s-md btn-info search_login"><?= THelper::t('search') ?></a>
        </div>
        <?= Html::a(THelper::t('create_a_new_account'), ['/business/team/registration'], ['class' => 'btn m-b btn-s-md btn-danger btn-rounded col-xs-12', 'data-toggle' => 'ajaxModal']) ?>
    </div>
</div>
<div class="row">
    <section class="panel">
        <header class="panel-heading bg-light">
            <ul class="nav nav-tabs ">
                <li class="active">
                    <a href="#tree" class="tree" data-toggle="tab"><?= THelper::t('tree') ?></a>
                </li>
                <li>
                    <a href="#circle" data-toggle="tab"><?= THelper::t('circle') ?></a>
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
                    <div id="content_tree" class="row">
                    </div>
                </div>
                <div class="tab-pane" id="circle">
                    <div class="doc-buttons col-md-2">
                        <a href="#" class="btn btn-s-md btn-default upstairs_tree" top-id="<?= $model->id; ?>"><?= THelper::t('upstairs') ?></a>
                        <a href="#" class="btn btn-s-md btn-default above_tree" parent-above=""><?= THelper::t('above') ?></a>
                        <a href="#" class="btn btn-s-md btn-default left_bottom_tree" lb-id="<?= $model->id; ?>"><?= THelper::t('left_bottom') ?></a>
                        <a href="#" class="btn btn-s-md btn-default right_bottom_tree" rb-id="<?= $model->id; ?>"><?= THelper::t('right_bottom') ?></a>
                    </div>
                    <div class="col-md-12 container-svg">
                        <div id="sunburst"><img src=""></div>
                    </div>
                </div>
                <div class="tab-pane" id="tree-invited">
                    <div id="content-tree-invited" class="row"></div>
                </div>
            </div>
        </div>
    </section>
    <img src="/images/preloader.gif" alt="load..." class="preloader-img" style="display:none;position: absolute;top: 25%;right: 50%;z-index: 99999;max-width: 200px;">
</div>
<div class="row m-b">
    <div class="col-sm-12">
        <?= THelper::t('genealogy_right_button_info') ?>
    </div>
</div>
<?php echo $this->render('context_menu');?>
<style>
    tspan {
        font-size: 11px;
    }
    .user-panel-body {
        overflow: hidden;
        height: 120px;
        max-height: 120px;
        padding: 15px;
    }
    path {
        stroke: #000;
        stroke-width: 1.5;
        cursor: pointer;
    }
</style>

<?php $this->registerJsFile('js/select2/select2.min.js', ['depends' => ['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/d3.v3.min.js', ['depends' => ['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/diagram2.0.js', ['depends' => ['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/genealogy.js', ['depends' => ['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/tree.js', ['depends' => ['app\assets\AppAsset']]); ?>