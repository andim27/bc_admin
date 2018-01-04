<?php

use app\components\THelper; ?>

<div class="main_block">
    <div class="children panel o-h bg-danger"
         data-side="<?= $current_user_model->side; ?>"
         data-lvl="0"
         parent-id="<?= $current_user_model->parentId; ?>"
         data-id="<?= $current_user_model->id; ?>">

<!--        <div class="add_info hidden">
            <?= THelper::t('login'); ?>: sidorenkoa
            <?= THelper::t('account_number_gn'); ?>: BPT-11625
            <?= THelper::t('country'); ?>: Украина
            <?= THelper::t('city'); ?>: Харьков
            <?= THelper::t('name'); ?>: Александр Сидоренко
            <?= THelper::t('email'); ?>: streles77@gmail.com
            <?= THelper::t('mobile'); ?>: +380935722903
            <?= THelper::t('activity'); ?>: есть
            <?= THelper::t('bs'); ?>: Есть, срок действия до 4/29/2013
            <?= THelper::t('status'); ?>: SILVER MASTER
            <?= THelper::t('recommender'); ?>: vilxova (Юлия Вильховая)
        </div>-->

        <div class="pull-left w-69">
            <span class="block">
                <a href="javascript:void(0);" class="thumb m-r m-b-xs">
                    <?php if ($current_user_model->avatar){ ?>
                        <img src="<?= $current_user_model->avatar ?>" class="img-circle">
                    <?php } else { ?>
                        <img src="/images/avatar_default.png" class="img-circle">
                    <?php } ?>
                </a>
            </span>
            <?php if ($current_user_model->statistics->pack > 0) { ?>
                <span class="block text-center"><img src="/images/genealogy/g_<?= $current_user_model->statistics->pack ?>.png?t=<?= time() ?>" class="icon m-r-xs m-b-xs" /></span>
            <?php } ?>
        </div>
        <p class="user_id" data-id="<?= $current_user_model->id; ?>">
            <?= $current_user_model->username; ?>
            <br/>
            <?= $current_user_model->firstName; ?>
            <br/>
            <?= $current_user_model->secondName; ?>
            <br/>
            <?= THelper::t('rank_'.$current_user_model->rank); ?>
            <br/>
            <span class="text-yellow"><?= $current_user_model->pointsLeft; ?></span> / <span class="text-yellow"><?= $current_user_model->pointsRight; ?></span>
            <br/>
            <span><?= $current_user_model->leftSideNumberUsers; ?></span> / <span><?= $current_user_model->rightSideNumberUsers; ?></span>
        </p>
    </div>
    <?= $tree; ?>
    <div class="clearfix"></div>
</div>
