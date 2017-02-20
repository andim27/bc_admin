<?php
use app\components\THelper;
use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('setting_admin_rules_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-6">
        <?= Html::dropDownList('admin_list', null, $adminList, ['prompt' => THelper::t('setting_admin_rules_select_admin'), 'id' => 'admin-list', 'class' => 'form-control']) ?>
    </div>
</div>