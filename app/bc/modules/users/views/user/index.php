<?php
use yii\helpers\Html;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $users */

$this->title = THelper::t('users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <div class="m-b-md"><h3 class="m-b-none"><?= Html::encode($this->title) ?></h3></div>

    <section class="panel panel-default">
        <header class="panel-heading">
            <?=THelper::t('table_users')?><!--Таблица пользователей-->
            <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
        </header>
        <div class="table-responsive">
            <table id="users_list_table" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                <thead>
                <tr>
                    <th><?=THelper::t('gn_number')?></th>
                    <th><?=THelper::t('login')?></th>
                    <th><?=THelper::t('registration')?></th>
                    <th><?=THelper::t('active_or_deleted')?></th>
                    <th><?=THelper::t('full_name')?></th>
                    <th><?=THelper::t('status')?></th>
                    <th><?=THelper::t('position')?></th>
                    <th><?=THelper::t('city')?></th>
                    <th><?=THelper::t('email')?></th>
                    <th><?=THelper::t('phone')?></th>
                    <th><?=THelper::t('skype')?></th>
                    <th><?=THelper::t('sponsor_login')?></th>
                    <th><?=THelper::t('full_name_sponsor')?></th>
                </tr>
                </thead>

                <tbody>
                    <?php foreach($users as $user) : ?>
                    <tr class="update_user_link" onclick="location.href='/users/edit?id=<?= $user->username ?>'">
                        <td><?= (empty($user->accountId)) ? '' : $user->accountId ?></td>
                        <td class="username"><?= $user->username; ?></td>
                        <td><?= gmdate('d.m.Y H:i:s', date('U', strtotime($user->created)))?></td>
                        <td><?= ($user->isDelete == '' || $user->isDelete == 'false') ? 'Active' : 'Deleted'; ?></td>
                        <td><?= $user->secondName.' '.$user->firstName; ?></td>
                        <td><?= (empty($user->status) ? '' : (($user->status == 1) ? 'Active' : 'Locked')) ?></td>
                        <?php
                        if(!empty($user->side)) {
                            if($user->side == 0) {
                                $position = 'Right';
                            } elseif($user->side == 1) {
                                $position = 'Left';
                            } elseif($user->side == -1) {
                                $position = 'Undefined';
                            }
                        } else {
                            $position = 'Undefined';
                        } ?>
                        <td><?= $position ?></td>
                        <td><?= ($user->country!='') ? $user->country:''; echo ($user->country!='' && $user->city!='')? ', '.$user->city : ''; ?></td>
                        <td><?= $user->email; ?></td>
                        <td><?= $user->phoneNumber; ?></td>
                        <td><?= $user->skype; ?></td>
                        <td><?= (empty($user->sponsor->username) ? 'Undefined' : $user->sponsor->username)?></td>
                        <td><?= (empty($user->sponsor->firstName) ? '' : $user->sponsor->firstName).' '.(empty($user->sponsor->secondName) ? '' : $user->sponsor->secondName) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?= Html::a(THelper::t('add'), ['create'], ['class' => 'btn btn-s-md m-b-md btn-danger pull-right']) ?>
</div>

<?php $this->registerJsFile('js/main/users.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
