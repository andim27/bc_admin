<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;

$this->title = THelper::t('binar_settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
    <?=$form->field($model,'gn')?>
    <div class="form-group">
        <label class="control-label switch-center"><?=THelper::t('removal_of_passive_cells')?><!--�������� �������� �����--></label>
        <label class="switch">
            <input type="checkbox"
                <?php echo(isset($model->rpc) && $model->rpc == 1)?'checked':'';?>
                   name="BinarSettings[rpc]">
            <span></span>
        </label>
    </div>
    <?=$form->field($model,'months')?>
    <div class="form-group">
        <label class="control-label switch-center"><?=THelper::t('combustion_points')?><!--�������� ������--></label>
        <label class="switch">
            <input type="checkbox"
                <?php
                echo (isset($model->combustion_points) && $model->combustion_points == 1)?'checked':'';?>
                   name="BinarSettings[combustion_points]">
            <span></span>
        </label>
    </div>
    <?=$form->field($model,'period_life')?>
    <div class="form-group">
        <label class="control-label switch-center"><?=THelper::t('closing_steps')?><!--�������� �����--></label>
        <label class="switch">
            <input type="checkbox"
                <?php
                echo(isset($model->closing_steps) && $model->closing_steps == 1)?'checked':'';?>
                   name="BinarSettings[closing_steps]">
            <span></span>
        </label>
    </div>

    <?=$form->field($model,'recalculation')->dropDownList([THelper::t('in_real_time'),THelper::t('everyday'),THelper::t('every_month')])?>
    <div class="none_h"><?=$form->field($model,'recalculation_hours')?></div>
    <div class="none_d"><?=$form->field($model,'recalculation_day')?></div>

    <?=$form->field($model,'sos')?>
    <?=$form->field($model,'pro_step')->dropDownList([THelper::t('450_by_450'),THelper::t('600_by_300'),THelper::t('300_by_600')])?>

    <?=$form->field($model,'limit')?>

    <?=$form->field($model,'qualification_left')?>
    <?=$form->field($model,'qualification_right')?>
    <div class="form-group">
        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>

<script>
    $(document).ready(function(){
        $('#binarsettings-recalculation').change();
    });
    $(document).on('change','#binarsettings-recalculation',function(){
        if($(this).val()==1){
            $('.none_h').css('display','block');
            $('.none_d').css('display','none');
        }
        else{
            if($(this).val()==2){
                $('.none_d').css('display','block');
                $('.none_h').css('display','none');
            }
            else{
                $('.none_h').css('display','none');
                $('.none_d').css('display','none');
            }
        }
    });

</script>
