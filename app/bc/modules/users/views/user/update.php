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
/* @var $this yii\web\View */
/* @var $model app\modules\users\models\Users */

$this->title = THelper::t('edit_user').': '.$model->second_name.' '.$model->name.' '.$model->middle_name;
$this->params['breadcrumbs'][] = ['label' => THelper::t('users'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->login, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = THelper::t('edit_user');
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

<div class="m-b-md">
    <h3 class="m-b-none"><?= Html::encode($this->title) ?></h3>
</div>
<section class="scrollable pull-in">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="col-xs-12 col-md-4">
        <?= $form->field($model, 'avatar_img')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showRemove' => true,
                'showUpload' => false,
            ]
        ]); ?>
        <?= $form->field($model, 'login')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>


    </div>

    <div class="col-xs-12 col-md-4">

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
        <input type="hidden" value="<?= $us_ct ?>" id="user_city_id">

        <?= $form->field($model, 'status_id')->dropDownList(
            ArrayHelper::map(UsersStatus::find()->asArray()->all(), 'id', 'title')
        ); ?>
        <?= $form->field($model, 'role_id')->dropDownList(
            ArrayHelper::map(UsersRights::find()->asArray()->all(), 'id', 'title')
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



</section>

<?php $this->registerJsFile('js/select2/select2.min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/users.js',['depends'=>['app\assets\AppAsset']]); ?>
