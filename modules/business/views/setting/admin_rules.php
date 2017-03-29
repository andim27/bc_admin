<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\web\View;
?>

<?php if ($successText) { ?>
    <div class="alert alert-success  fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= $successText ?>
    </div>
<?php } else if ($errorsText) { ?>
        <div class="alert alert-danger">
            <?= $errorsText ?>
        </div>
<?php } ?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('setting_admin_rules_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-6">
        <?= Html::dropDownList(
            'admin_list',
            null,
            $adminList,
            [
                'prompt' => THelper::t('setting_admin_rules_select_admin'),
                'id' => 'admin-list',
                'class' => 'form-control',
                'onchange'=> 'showRules(this.value)'
            ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="blRuleAdmin"></div>
    </div>
</div>

<?php
$JS = <<< JS
  function showRules(id) {
       $.ajax({
           url: '/business/setting/admin-rules-show',
           type: 'POST',
           data: {
               userId : id,
           },
           success: function (data) {
               $( ".blRuleAdmin" ).html(data);
           }
       });
  }
JS;

$this->registerJs($JS, View::POS_END);

?>
