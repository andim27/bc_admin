<?php
use app\components\THelper;
?>
<div class="clearfix m-b">
            <a href="#" class="pull-left thumb m-r">
                <img src="<?=(!empty($model->avatar_img))?$model->avatar_img:'/images/avatar_default.png';?>" class="img-circle">
            </a>
            <div class="clear">
                <div class="h3 m-t-xs m-b-xs">
                    <?=$model->login?>
                </div>
                <small class="text-muted"><?=THelper::t('status')?>: <?=$status?></small>
</div>
</div>
<div class="panel wrapper panel-success">
    <div class="row">
        <div class="col-xs-4">
            <a href="#">
                <span class="m-b-xs h4 block">245</span> <small class="text-muted"><?=THelper::t('left_structure')?></small>
            </a>
        </div>
        <div class="col-xs-4">
            <a href="#">
                <span class="m-b-xs h4 block"><?=$count_all?></span> <small class="text-muted"><?=THelper::t('structure')?></small>
            </a>
        </div>
        <div class="col-xs-4">
            <a href="#">
                <span class="m-b-xs h4 block">2,035</span>
                <small class="text-muted"><?=THelper::t('right_structure')?></small>
            </a>
        </div>
    </div>
</div>
<div class="btn-group btn-group-justified m-b">
    <a class="btn btn-primary btn-rounded" data-toggle="button">
        <span class="text"> <?=THelper::t('sponsorship')?> </span>
        <span class="text-active"> <?=THelper::t('sponsorship')?> </span>
    </a>
    <a class="btn btn-dark btn-rounded" data-toggle="button">
        <span class="text-active"> <?=THelper::t('internal_structure')?> </span>
        <span class="text"> <?=THelper::t('internal_structure')?> </span>
    </a>
</div>