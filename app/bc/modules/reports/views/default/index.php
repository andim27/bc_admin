<?php
use app\components\THelper;

$this->title = THelper::t('deleted_cell');
$this->params['breadcrumbs'][] = $this->title;

/* @var $data */

use app\models\User;
use app\modules\settings\models\UsersStatus;
use app\modules\business\models\UsersReferrals;
use yii\helpers\Html;

?>

<section class="panel panel-default">
    <header class="panel-heading">
        <?=THelper::t('deleted_cell');?><!--Удаленные ячейки-->
    </header>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="buy">
                <section class="panel panel-default">
                    <div class="table-responsive">
                        <table id="MyStretchGridDel" class="table table-striped datagrid m-b-sm unique_table_class">
                            <thead>
                            <tr>
                                <th class="sortable">
                                    <?=THelper::t('date')?>
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('login')?>
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('full_name')?>
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('email')?>
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('phone')?>
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('skype')?>
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('login_sponsor')?>
                                </th>
                                <th class="sortable">
                                    <?=THelper::t('login_parent')?>
                                </th>
                                <th class="sort">

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data)){foreach ($data as $value){ ?>
                               <?php $referral = UsersReferrals::find()->where(['uid' => $value->uid])->one();
                                if(!empty($referral)) {
                                    $user = User::find()->where(['id' => $referral->uid])->one();
                                    $sponsor = User::find()->where(['id' => $referral->sponsor_id])->one();
                                    $parent = User::find()->where(['id' => $referral->parent_id])->one();
                                    $a = $sponsor['login'];
                                    $b = $sponsor['login'];
                                } else {$a = ''; $b = '';}
                                ?>
                                <tr>
                                    <td>
                                        <?php $date  = date('d-m-Y', $user->created_at)?>
                                        <?= $date ?>
                                    </td>
                                    <td>
                                        <?= $user['login']?>
                                    </td>
                                    <td>
                                        <?= $user['name']?> <?= $user['second_name']?> <?= $user['middle_name']?>
                                    </td>
                                    <td>
                                        <?= $user['email']?>
                                    </td>
                                    <td>
                                        <?= $user['mobile']?>
                                    </td>
                                    <td>
                                        <?php if($user['skype'] == ''){
                                            $skype = 'Не указан';
                                        } else $skype = $user['skype']; ?>
                                        <?= $skype ?>
                                    </td>
                                    <td>
                                        <?= $a ?>
                                    </td>
                                    <td>
                                        <?= $b ?>
                                    </td>
                                    <td>
                                        <?php if($value->status_id == 1){
                                            $s = Html::a('', ['default/delete-cell', 'id'=>$user['id']], ['class' => 'fa fa-times text-danger', 'title' => 'Восстановить пользователя в структуре']);
                                        } else $s = '<i class="fa fa-check text-success"></i>'?>

                                        <?= $s ?>
                                    </td>
                                </tr>

                            <?php }
                            }
                            ?>
                            </tbody>

                        </table>
                    </div>
                </section>
            </div>

        </div>

    </div>
</section>


<?php $this->registerJsFile('js/libs/underscore-min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/fuelux/fuelux.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/app.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/app.plugin.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>