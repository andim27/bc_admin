<?php

use yii\helpers\Html;
use \app\components\THelper;

$this->title = THelper::t('Administrators');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-index" id="admins_index">

    <div class="m-b-md"><h3 class="m-b-none"><?= Html::encode($this->title) ?></h3></div>
    <div class="alert default_alert"></div>

    <section class="panel panel-default">
        <header class="panel-heading">
            <?=THelper::t('table_administrators')?><!--Таблица администраторов-->
            <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
        </header>
        <div class="table-responsive">
            <?= Html::beginForm(['update'], 'post', ['enctype' => 'multipart/form-data', 'id'=>'update_admins']); ?>
            <table id="users_list_table" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                <thead>
                <tr>
                    <th width="8%"><?=THelper::t('avatar')?><!--Аватар--></th>
                    <th width="11%"><?=THelper::t('login')?><!--Логин--></th>
                    <th width="15%"><?=THelper::t('email')?></th>
                    <th width="15%"><?=THelper::t('full_name')?><!--ФИО--></th>
                    <th width="10%"><?=THelper::t('phone')?><!--Телефон--></th>
                    <th width="10%"><?=THelper::t('status')?><!--Статус--></th>
                    <th width="10%"><?=THelper::t('city')?><!--Город--></th>
                    <th width="10%"><?=THelper::t('language')?><!--Язык--></th>
                    <th width="10%"><?=THelper::t('skype')?><!--Скайп--></th>
                    <th width="1%"></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($admins as $admin) : ?>
                    <tr data-id="<?= $admin->id ?>">
                        <td><?= Html::img($dir.$admin->avatar_img, array('width'=>80, 'height'=>80)); ?></td>
                        <td><?= $admin->login; ?></td>
                        <td><?= $admin->email; ?></td>
                        <td><?= $admin->second_name.' '.$admin->name.' '.$admin->middle_name; ?></td>
                        <td><?= $admin->mobile; ?></td>
                        <td><?= $admin->usersStatus->title; ?></td>
                        <td><?= $admin->country->title; ?>, <?= $admin->city->title; ?></td>
                        <td><?= $admin->localisation->title; ?></td>
                        <td><?= $admin->skype; ?></td>
                        <td><?= Html::a('<i class="fa fa-pencil"></i>', ['update', 'id'=>$admin->id]); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?= Html::endForm(); ?>
        </div>
    </section>

    <?= Html::a(THelper::t('add_administrator'), ['create'], ['class' => 'btn btn-s-md btn-danger pull-right']) ?>

    <?php $this->registerJsFile('js/main/users.js',['depends'=>['app\assets\AppAsset']]); ?>
    <?php $this->registerJsFile('js/main/ajaxAdminUsers.js',['depends'=>['app\assets\AppAsset']]); ?>
</div>
