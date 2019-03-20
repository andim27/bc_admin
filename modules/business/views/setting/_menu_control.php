<?php
use yii\helpers\Html;
use app\components\THelper;
use yii\widgets\ActiveForm;
$bg = 'bg-light';
?>
<h3><?=THelper::t('sidebar_settings_menu') ?></h3>
<?php if ($user_name != 'main') {?>
    <h3 class="text-color-red"><?=THelper::t('access_deny') ?></h3>
<?php return; } ?>

<?php if($items) {?>
<pre>
    <?=var_dump($model['adminMainMenu']["hideMenu"]) ?>
</pre>
    <?php $formCom = ActiveForm::begin([
        'action' => '/' . $language . '/business/setting/menu-control-save',
        'options' => ['name' => 'menuControlSave'],
    ]); ?>

    <?=Html::input('hidden','id',$model->_id)?>

    <?php foreach ($items as $item) { ?>
        <div class="row <?=($bg == 'bg-light' ? $bg = 'bg-light dk' : $bg = 'bg-light')?>">
            <?php if($item['key'] != 'sidebar_home'){?>
                <div class="col-md-8">
                    <label class="control-label switch-center"><strong><?=$item['label']?></strong></label>
                </div>
            <?php } else {?>
                <div class="col-md-8">
                    <label class="control-label switch-center"><?=$item['label']?></label>
                </div>

            <?php } ?>


        </div>
        <?php if(!empty($item['items'])) {?>
            <?php foreach ($item['items'] as $subitem) { ?>
                <div class="row <?=($bg == 'bg-light' ? $bg = 'bg-light dk' : $bg = 'bg-light')?>">
                    <div class="col-md-1"></div>
                    <?php if($subitem['key'] != 'sidebar_order'){?>
                        <div class="col-md-5">
                            <label class="control-label switch-center"><?=$subitem['label']?></label>
                        </div>
                    <?php } else {?>
                        <div class="col-md-5">
                            <label class="control-label switch-center"><?=$subitem['label']?></label>
                        </div>

                    <?php } ?>
                    <div class="col-md-2">
                        <label class="control-label switch-center"><?= THelper::t('rules_admin_display');?></label>
                        <label class="switch">
                            <?php  if (isset($model['adminMainMenu']["hideMenu"])) { ?>
                                <input value="<?=$subitem['key']?>" class="btnRulesShow" type="checkbox" name="rule[hideMenu][]"   <?= ($subitem['key'] == 'sidebar_settings_menu') ? 'disabled' :'' ?>   <?= (in_array($subitem['key'],(array)$model['adminMainMenu']["hideMenu"]) ? 'checked="checked"' : '')?>/>
                            <?php } else { ?>
                                <input value="<?=$subitem['key']?>" class="btnRulesShow" type="checkbox" name="rule[hideMenu][]"  />
                            <?php } ?>
                            <span></span>
                        </label>

                    </div>
                    <div class="col-md-2">
                        <label class="control-label switch-center"><?= THelper::t('rules_admin_hide');?></label>
                    </div>

                </div>
            <?php } ?>
        <?php } ?>

    <?php } ?>
    <br>
    <div class="row">
        <div class="col-md-12 text-center">
            <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<?php } ?>

<script>
    $(document).on('change','.btnRulesEdit',function () {
        if($(this).is(':checked')) {
            $(this).closest('.row').find('.btnRulesShow').prop( "checked", true );
        }
    })
</script>

