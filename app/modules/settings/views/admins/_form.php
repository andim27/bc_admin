<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\settings\models\UsersStatus;
use app\modules\settings\models\UsersRights;
use app\modules\settings\models\Localisation;
use app\modules\settings\models\CountryList;
use app\modules\settings\models\CityList;
use kartik\file\FileInput;
use app\assets\AppAsset;
use app\components\THelper;
AppAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\RegistrationForm */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    $(document).ready(function() {
        $("#users-avatar_img").fileinput({
            'showUpload':false,
            'showRemove':false,
            <?php if($model->avatar_img):?>
            'initialPreview': [
                "<img src='/uploads/<?= $model->avatar_img; ?>' class='file-preview-image' alt='' title='<?= $model->login; ?>'>",
            ]
            <?php endif; ?>
        });

    });
</script>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="col-xs-12 col-md-4">
    <?= $form->field($model, 'avatar_img')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showRemove' => true,
            'showUpload' => false,
            /*'initialPreview' => (!empty($model->avatar_img)) ? [
                Html::img("/uploads/".$model->avatar_img, ['class'=>'file-preview-image', 'title'=>'$model->login'])
            ]:false,
            'overwriteInitial'=>false*/
        ]
    ]); ?>


    <?= $form->field($model, 'pass')->passwordInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'finance_pass')->passwordInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'password_repeat_finance')->passwordInput(['maxlength' => 255]) ?>

</div>

<div class="col-xs-12 col-md-4">
    <?= $form->field($model, 'login')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'second_name')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'middle_name')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'mobile')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'skype')->textInput(['maxlength' => 255]) ?>
</div>

<div class="col-xs-12 col-md-4">
    <?= $form->field($model, 'country_id')->dropDownList(
        ArrayHelper::map(CountryList::find()->asArray()->all(), 'id', 'title'), ['id'=>'select2-option', 'class'=>'block']
    ); ?>

    <div id="get_city"></div>

    <?= $form->field($model, 'status_id')->dropDownList(
        ArrayHelper::map(UsersStatus::find()->asArray()->all(), 'id', 'title')
    ); ?>

    <?= $form->field($model, 'lang_id')->dropDownList(
        ArrayHelper::map(Localisation::find()->asArray()->all(), 'id', 'title')
    ); ?>
</div>

<div class="col-xs-12">
    <div class="form-group pull-right">
        <?= Html::submitButton(THelper::t('save'), ['class' =>  'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>


<?php $this->registerJsFile('js/select2/select2.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/users_city.js',['depends'=>['app\assets\AppAsset']]); ?>
