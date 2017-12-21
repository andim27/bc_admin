<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\THelper;

/* @var $this yii\web\View */
/* @var $model app\modules\handbook\models\ProductList */
/* @var $form ActiveForm */
?>
<div class="header b-b wrapper panel panel-default"><?=THelper::t('card_product').' '.$model->title?></div>
<div class="product-card panel-body">

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'sku') ?>
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'price') ?>
        <?= $form->field($model, 'premium') ?>
        <?= $form->field($model, 'ht_premium')->checkboxList($check_list,['class' => 'scr scroll-y'])?>

        <?= $form->field($model, 'bs_first_month')->checkbox() ?>
        <?= $form->field($model, 'bs_after_second_month')->checkbox() ?>

        <?= $form->field($model, 'purchase_date')?>
        <?= $form->field($model, 'points_premium') ?>
        <?= $form->field($model, 'ht_p_premium')->checkboxList($check_list,['class' => 'scr scroll-y'])?>
        <?= $form->field($model, 'change_actives')->dropDownList(['0'=>'Не заменять','1'=>'Заменять']) ?>
        <?= $form->field($model, 'points_carrier') ?>
        <?= $form->field($model, 'multiple_purchase')->checkbox()?>

        <div class="form-group">
            <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- product-card -->
<script>
    jQuery('#productlist-change_actives').on('change',function(){
        for_change($(this).val());
    });
</script>