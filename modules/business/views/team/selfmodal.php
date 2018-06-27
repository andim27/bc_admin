<?php

/* @var $this yii\web\View */
/* @var $user */
/* @var $status */
/* @var $city */
/* @var $country */
/* @var $recom */
use app\components\THelper;
use app\modules\business\models\CountryList;

?>
            <div class="users-create">
                <h5><?=THelper::t('login')?>: <?= $user->username ?></h5>
                <h5><?=THelper::t('the_account_number_gn')?>: BPT-<?= $user->accountId ?></h5>
                <?php $country = CountryList::find()->where(['iso_code' => $user->country])->one(); ?>
                <h5 class="country"><?=THelper::t('country')?>: <?= $country->title ?></h5>
                <h5 class="city"><?=THelper::t('city')?>: Undefined</h5>
                <h5 class="name"><?=THelper::t('name')?>: <?= $user->firstName ?> <?= $user->secondName ?></h5>
                <h5 class="email"><?=THelper::t('email')?>: <?= $user->email ?></h5>
                <h5 class="mobile"><?=THelper::t('mobile_phone')?>: <?= $user->phoneNumber ?></h5>
                <h5 class="activity"><?=THelper::t('activity')?>: <?= ($user->status == 1) ? 'Active' : 'Not active' ?></h5>
                <h5 class="bs"><?=THelper::t('bs')?>: <?= gmdate('d.m.Y', date('U', strtotime($user->expirationDateBS))) ?></h5>
                <?php if($user->rank == 0){
                    $rank = THelper::t('undefined');
                } else {
                    $rank = THelper::t('finish');
                } ?>
                <h5 class="status"><?=THelper::t('status')?>: <?= $rank ?></h5>
                <h5 class="recom"><?=THelper::t('recommender')?>: <?= $user->sponsor->firstName ?> <?= $user->sponsor->secondName ?></h5>
            </div>
