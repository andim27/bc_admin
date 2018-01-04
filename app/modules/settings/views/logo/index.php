<?php
use yii\bootstrap\ActiveForm;
use app\components\THelper;

$this->title = THelper::t('download_logos');

/* @var $this yii\web\View */
/* @var $model app\modules\bekofis\models\PageList */
/* @var $log_auth */
/* @var $log_reg */
/* @var $log_admin */
/* @var $log_business */



?>


    <div class="user-profile-update">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<div class="row">
        <div class="container aside-xxl pull-left" style="width: 50%">

            <?= $form->field($model, 'file')->fileInput() ?>
            <?= $form->field($model, 'width')->textInput() ?>
            <?= $form->field($model, 'height')->textInput() ?>
            <?= (!empty($log_auth->description))?$log_auth->description:'';?>
            <br>

        </div>

        <div class="container aside-xxl pull-right" style="width: 50%">

            <?= $form->field($model, 'file_reg')->fileInput() ?>
            <?= $form->field($model, 'width_reg')->textInput() ?>
            <?= $form->field($model, 'height_reg')->textInput() ?>
            <?= (!empty($log_auth->description))?$log_auth->description:'';?>
            <br>

        </div>
</div>
        <div class="line line-dashed"></div>
        <div class="row">
        <div class="container aside-xxl pull-left" style="width: 50%">

            <?= $form->field($model, 'file_admin')->fileInput() ?>
            <?= $form->field($model, 'width_admin')->textInput() ?>
            <?= $form->field($model, 'height_admin')->textInput() ?>
            <?= (!empty($log_auth->description))?$log_auth->description:'';?>
            <br>

        </div>

        <div class="container aside-xxl pull-right" style="width: 50%">

            <?= $form->field($model, 'file_business')->fileInput() ?>
            <?= $form->field($model, 'width_business')->textInput() ?>
            <?= $form->field($model, 'height_business')->textInput() ?>
            <?= (!empty($log_auth->description))?$log_auth->description:'';?>
            <br>

        </div>


        </div>
        <div class="line line-dashed"></div>

       <div style="text-align: center; margin: 0 auto">
            <button class="btn btn-primary"><?=THelper::t('download')?><!--Загрузить--></button>
       </div>
        <br>
        <br>

            <?php ActiveForm::end() ?>




     </div>
