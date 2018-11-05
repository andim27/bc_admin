<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.10.18
 * Time: 15:40
 */
use app\components\THelper;
use app\models\Users;
?>
<div class="row">
<h3 style="padding-left: 40%"> <?= THelper::t('checks_details'); ?></h3>
</div>
<section class="panel panel-default">
    <header class="panel-heading font-bold">
        <?= THelper::t('checks_max_table'); ?>
    </header>
    <div class="table-responsive panel-body">
        <table class="table table-translations table-striped datagrid m-b-sm tableMaxCheck">
            <thead>
            <tr>
                <th><?=THelper::t('login')?></th>
                <th><?=THelper::t('user_firstname_secondname')?></th>
                <th>email</th>
                <th><?=THelper::t('phone')?></th>
                <th><?=THelper::t('amount')?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($statisticInfo['tradeTurnover']['forUser'])){?>
                <?php foreach ($statisticInfo['tradeTurnover']['forUser'] as $k=>$item) {?>
                    <?php $infoUser = Users::findOne(['_id'=>new \MongoDB\BSON\ObjectID($k)]);?>
                    <tr>
                        <td><?=(!empty($infoUser->username) ? $infoUser->username : $k)?></td>
                        <td>
                            <?=(!empty($infoUser->secondName) ? $infoUser->secondName : ''); ?>
                            <?=(!empty($infoUser->firstName) ? $infoUser->firstName : ''); ?>
                        </td>
                        <td><?=(!empty($infoUser->email) ? $infoUser->email : '')?></td>
                        <td><?=(!empty($infoUser->phoneNumber) ? $infoUser->phoneNumber : '')?></td>
                        <td><?=$item?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<script type="text/javascript">
    $('.tableMaxCheck').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 4, "desc" ]]
    });
    $('#')
</script>