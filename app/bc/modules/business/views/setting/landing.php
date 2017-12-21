<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
    $this->title = THelper::t('setting_landing_title');
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <section class="panel-body">
                <?= THelper::t('setting_landing_info_text') ?>
            </section>
        </section>
    </div>
    <div class="col-md-12 m-b">
        <img src="/images/analytics.png" class="img-responsive" />
    </div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">
        <?= $form->field($model, 'analytics')->textInput(['class' => 'form-control pull-right m-b'])->label(THelper::t('setting_landing_analytics')) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'analytics2')->textInput(['class' => 'form-control pull-right m-b'])->label(THelper::t('setting_landing_analytics_2')) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'analyticsVipVip')->textInput(['class' => 'form-control pull-right m-b'])->label(THelper::t('setting_landing_analytics_vipvip')) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'analyticsWebwellnessRu')->textInput(['class' => 'form-control pull-right m-b'])->label(THelper::t('setting_landing_analytics_webwellness_ru')) ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'analyticsWebwellnessNet')->textInput(['class' => 'form-control pull-right m-b'])->label(THelper::t('setting_landing_analytics_webwellness_net')) ?>
    </div>

    <div class="col-md-12">
        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-s-md btn-primary']); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>