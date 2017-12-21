<?php

use yii\helpers\Html;
use app\components\THelper;
use yii\bootstrap\ActiveForm;
use app\modules\settings\models\LinksForGroups;

$this->title = THelper::t('back_office_groups_links');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"> <?= THelper::t('back_office_groups_links'); ?> </header>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['options' => [
                        'class' => 'form-horizontal'
                    ]]); ?>

                    <?php
                    $checked_vk = 'checked';
                    $checked_fb = 'checked';
                    $checked_yt = 'checked';
                    $models = LinksForGroups::find()->where(['id' => 1])->one();
                    if(!empty($models)){
                        if($models->allow_vk == 0) $checked_vk = '';
                        if($models->allow_facebook == 0) $checked_fb = '';
                        if($models->allow_youtube == 0) $checked_yt = '';
                    }

                    if($model->isNewRecord){
                    $model->vk = '';
                    $model->facebook = '';
                    $model->youtube = '';
                    } else {
                        $model->vk;
                        $model->facebook;
                        $model->youtube;
                    }
                    ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?= THelper::t('vkontakte'); ?> </label>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'vk')->textInput(['class' => 'form-control', 'placeholder' => 'vk.com/your_group'])->label(false) ?>
                        </div>
                        <div class="col-sm-2">
                            <label class="switch pull-right"> <input type="checkbox"  <?= $checked_vk ?> name="LinksForGroups[allow_vk]"> <span></span> </label>
                        </div>
                    </div>

                    <div class="line line-dashed line-lg pull-in"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?= THelper::t('facebook'); ?> </label>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'facebook')->textInput(['class' => 'form-control', 'placeholder' => 'facebook.com/your_group'])->label(false) ?>
                        </div>
                        <div class="col-sm-2">
                            <label class="switch pull-right"> <input type="checkbox" <?= $checked_fb ?> name="LinksForGroups[allow_facebook]"> <span></span> </label>
                        </div>
                    </div>

                    <div class="line line-dashed line-lg pull-in"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?= THelper::t('youtube'); ?> </label>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'youtube')->textInput(['class' => 'form-control', 'placeholder' => 'youtube.com/your_group'])->label(false) ?>
                        </div>
                        <div class="col-sm-2">
                            <label class="switch pull-right"> <input type="checkbox" <?= $checked_yt ?> name="LinksForGroups[allow_youtube]"> <span></span> </label>
                        </div>
                    </div>

                    <div class="line line-dashed line-lg pull-in"></div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success pull-right']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </section>
        </div>
    </div>
</div>