<?php
    use yii\helpers\Html;
    use app\components\THelper;
    use yii\widgets\ActiveForm;
    $bg = 'bg-light';

?>

    <?php if($items) {?>
    
        <?php $formCom = ActiveForm::begin([
            'action' => '/' . $language . '/business/setting/admin-rules-save',
            'options' => ['name' => 'saveAdminRule'],
        ]); ?>

        <?=Html::input('hidden','id',$model->_id)?>
    
        <?php foreach ($items as $item) { ?>
        <div class="row <?=($bg == 'bg-light' ? $bg = 'bg-light dk' : $bg = 'bg-light')?>">
            <div class="col-md-8">
                <label class="control-label switch-center"><?=$item['label']?></label>
            </div>
            <div class="col-md-2">
                <label class="control-label switch-center"><?= THelper::t('rules_admin_display');?></label>
                <label class="switch">
                    <input value="<?=$item['key']?>" type="checkbox" name="rule[showMenu][]" <?= (in_array($item['key'],(array)$model->rules->showMenu) ? 'checked="checked"' : '')?>/>
                    <span></span>
                </label>
            </div>
            <div class="col-md-2">
                <label class="control-label switch-center"><?= THelper::t('rules_admin_edit');?></label>
                <label class="switch">
                    <input value="<?=$item['key']?>" type="checkbox" name="rule[edit][]" <?= (in_array($item['key'],(array)$model->rules->edit) ? 'checked="checked"' : '')?>/>
                    <span></span>
                </label>
            </div>
        </div>
        <?php if(!empty($item['items'])) {?>
            <?php foreach ($item['items'] as $subitem) { ?>
                <div class="row <?=($bg == 'bg-light' ? $bg = 'bg-light dk' : $bg = 'bg-light')?>">
                    <div class="col-md-1"></div>
                    <?php if($subitem['key'] != 'sidebar_order'){?>
                    <div class="col-md-7">
                        <label class="control-label switch-center"><?=$subitem['label']?></label>
                    </div>
                    <?php } else {?>
                        <div class="col-md-5">
                            <label class="control-label switch-center"><?=$subitem['label']?></label>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label switch-center">Проводка за наличные</label>
                            <label class="switch">
                                <input value="sidebar_order" class="btnRulesShow" type="checkbox" name="rule[transaction_cash][]" <?= ((!empty($model->rules->transaction_cash) && in_array('sidebar_order',(array)$model->rules->transaction_cash)) ? 'checked="checked"' : '')?>/>
                                <span></span>
                            </label>
                        </div>
                    <?php } ?>
                    <div class="col-md-2">
                        <label class="control-label switch-center"><?= THelper::t('rules_admin_display');?></label>
                        <label class="switch">
                            <input value="<?=$subitem['key']?>" class="btnRulesShow" type="checkbox" name="rule[showMenu][]" <?= (in_array($subitem['key'],(array)$model->rules->showMenu) ? 'checked="checked"' : '')?>/>
                            <span></span>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label switch-center"><?= THelper::t('rules_admin_edit');?></label>
                        <label class="switch">
                            <input value="<?=$subitem['key']?>" class="btnRulesEdit" type="checkbox" name="rule[edit][]" <?= (in_array($subitem['key'],(array)$model->rules->edit) ? 'checked="checked"' : '')?>/>
                            <span></span>
                        </label>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <?php } ?>

        <div class="row">
            <div class="col-md-12 text-right">
                <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
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
