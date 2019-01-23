<?php

use app\components\THelper;
?>

<ul class="list-group no-radius">
    <li class="list-group-item">
        <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['connectingBonus']),0,',',' ')?></span>
        <span class="label bg-primary">1</span>
        <?= THelper::t('personal_award') ?>
    </li>
    <li class="list-group-item">
        <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['teamBonus']),0,',',' ')?></span>
        <span class="label bg-dark">2</span>
        <?= THelper::t('team_award') ?>
    </li>
    <li class="list-group-item">
        <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['mentorBonus']),0,',',' ')?></span>
        <span class="label bg-77382E">3</span>
        <?= THelper::t('mentor_bonus') ?>
    </li>
    <li class="list-group-item">
        <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['careerBonus']),0,',',' ')?></span>
        <span class="label bg-009A8C">4</span>
        <?= THelper::t('career_bonus') ?>
    </li>
    <li class="list-group-item">
        <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['executiveBonus']),0,',',' ')?></span>
        <span class="label bg-AAA100">5</span>
        <?= THelper::t('executive_bonus') ?>
    </li>
    <li class="list-group-item">
        <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['worldBonus']),0,',',' ')?></span>
        <span class="label bg-AA0900">6</span>
        <?= THelper::t('world_bonus') ?>
    </li>

    <li class="list-group-item">
        <span class="pull-right"><?=number_format(round($statisticInfo['bonus']['equityBonus']),0,',',' ')?></span>
        <span class="label bg-664CC1">7</span>
        <?= THelper::t('bonus_equity'); ?>
    </li>

    <li class="list-group-item">
        <span class="pull-right"><?=$statisticInfo['bonus']['autoBonus'] ?></span>
        <span class="label bg-AAA100">8</span>
        Автомобильный бонус
    </li>
    <li class="list-group-item">
        <span class="pull-right"><?=$statisticInfo['bonus']['propertyBonus'] ?></span>
        <span class="label bg-dark">9</span>
        Недвижимость бонус
    </li>
    <li class="list-group-item">
        <span class="pull-right"><?=$statisticInfo['bonus']['representative'] ?></span>
        <span class="label bg-dark">10</span>
        Затраты представителей
    </li>
</ul>