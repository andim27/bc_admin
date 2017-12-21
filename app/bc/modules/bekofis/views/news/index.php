<?php

/* @var $this yii\web\View
 * @var $i
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use app\components\THelper;
//use kartik\date\DatePicker;
//use app\assets\AppAsset;

//AppAsset::register($this);

$this->title = THelper::t('news');
Yii::$app->session->getFlash('error');
?>

<div class="email-list-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-inline">
        <?= Html::label(THelper::t('date'), 'post_at', ['class' => 'control-label', 'style' => 'float:left; margin: 8px 69px 0px 0px;']) ?>
        <?= $form->field($model, 'post_at')->textInput([
                'maxlength'=>16, 'class'=>'input-sm input-s datepicker-input form-control',
                'data-date-format'=>'dd-mm-yyyy'
            ])->label(false) ?>

        <?= $form->field($model, 'rememberMe')->checkbox(['class' =>'normal', 'style' => 'margin-top: 10px'])
            ->label(THelper::t('time'), ['class' => 'control-label', 'style' => 'float:left; margin: 6px 5px 0px 0px;'])?>

        <?php $mas = array();
            for($i = 0; $i <= 23; $i++) { $mas[]= $i;}
            echo $form->field($model, 'hours')->dropDownList($mas, ['class'=>'input-sm', 'style'=>'background:#FFF'])
                ->label(THelper::t('h')/*'ч.'*/, ['style' => 'float: right; margin: 8px 0px 0px 3px;']); ?>

        <?php $mas = array();
            for($i = 0; $i <= 59; $i++) { $mas[]= $i;}
            echo $form->field($model, 'minutes')->dropDownList($mas, ['class'=>'input-sm', 'style'=>'background:#FFF'])
                ->label(THelper::t('min'), ['style' => 'float: right; margin: 8px 0px 0px 3px;']); ?>

    </div>

    <?= Html::label(THelper::t('topic_news'), 'title', ['class' => 'control-label', 'style' => 'float:left; margin: 8px 10px 0px 0px;']) ?>
        <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'style' => 'width:40%'])->label(false) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'full',
            'inline' => false,
        ],
    ]);
    ?>

    <?= Html::a(THelper::t('preview'), ['news/news'], ['data-toggle'=>'ajaxModal', 'class' => 'btn btn-primary pull-left', 'name' => 'preview', 'id' => 'preview']); ?>
    <?= Html::submitButton(THelper::t('save_changes'), ['class' => 'btn btn-info pull-right', 'name' => 'save_news']) ?>
    <?php ActiveForm::end(); ?>
    <br><br><br><br>

</div>

<?php $this->registerJsFile('js/datepicker/bootstrap-datepicker.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/news.js',['depends'=>['app\assets\AppAsset']]); ?>
