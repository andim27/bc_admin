<?php
    use app\components\THelper;
    use yii\helpers\Url;
    $this->title = THelper::t('status');
    $this->params['breadcrumbs'][] = $this->title;
?>
<section>
    <div class="pull-left" style="padding-bottom: 10px">
        <div class="panel panel-body">
            <div class="thumb pull-left m-r">
                <img src="<?= $user->avatar ? $user->avatar : '/images/avatar_default.png'; ?>" class="img-circle">
            </div>
            <div class="clear">
                <h4 class="user_id"><?=(isset($user->username)) ? $user->username : '';?></h4>
                <small class="block"><?=THelper::t('status')?>: <?=THelper::t('rank_' . $user->rank)?></small>
            </div>
        </div>
        <a class="btn btn-s-md btn-danger btn-rounded col-xs-12" href="<?= Url::to('/business/information/carrier'); ?>"><?=THelper::t('description_of_career_plan')?></a>
    </div>
    <div class="panel-body pull-right">
        <div class="thumb pull-left m-r">
            <img class="thumb pull-left m-r img-circle" src="/images/ranks/rank_<?=$user->rank?>.png">
        </div>
    </div>
</section>