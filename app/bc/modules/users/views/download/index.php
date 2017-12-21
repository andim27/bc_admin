<?php

use yii\helpers\Html;
use app\components\THelper;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = THelper::t('uploaded_documents');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <div class="row">
        <?php $form = ActiveForm::begin([
            'id' => 'uploaded_files'
        ]); ?>
        <div class="col-sm-11">
            <span class="h4"><?= THelper::t('uploaded_documents') ?></span>
            <br>
            <br>
            <section class="panel panel-default">
                <header class="panel-heading">
                    <span class="h4"><?= THelper::t('text_in_the_back_office') ?></span>
                </header>
                   <?php
                   if($model->isNewRecord){
                       $model->text = '';
                       $model->count = 0;
                   } else {
                       $model->text;
                       $model->count = $model->count - 1;
                   } ?>
                   <?= $form->field($model, 'text')->textarea(['class' => 'form-control' ,'style' => 'position: relative; overflow: hidden; height: 150px; margin-bottom: -15px'])->label(false) ?>
            </section>
            <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-s-md btn-danger btn-rounded pull-right', 'style' => 'margin-top: -40px']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php $mas = array();
            for($i = 1; $i <= 10; $i++) { $mas[]= $i;}
            echo $form->field($model, 'count')->dropDownList($mas, ['class'=>'input-sm', 'style'=>'background:#FFF'])
                ->label(THelper::t('the_number_of_allowed_files_for_one_user'), ['class' => 'h4', 'style' => 'margin-right: 10px']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-sm-11">
            <section class="panel panel-default">
                <header class="panel-heading"><span class="h4"> <?=THelper::t('the_table_of_the_uploaded_files')?> </span></header>
                <div class="table-responsive">
                    <table id="MyStretchGridUpload" class="table table-striped datagrid m-b-sm unique_table_class">
                        <thead>
                        <tr>
                            <th data-property="toponymName" class="sortable">
                                <?=THelper::t('date')?>
                            </th>
                            <th data-property="countrycode" class="sortable">
                                <?=THelper::t('login')?>
                            </th>
                            <th data-property="population" class="sortable">
                                <?=THelper::t('full_name')?>
                            </th>
                            <th data-property="fcodeName" class="sortable">
                                <?=THelper::t('file_name')?>
                            </th>
                            <th data-property="fcodeName" class="sort">
                                <?=THelper::t('download')?>
                            </th>
                            <th data-property="geonameId" class="sort">

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($files)){foreach ($files as $value){
                            for( $i = 1; $i <= 10; $i++){
                                if($value['file'.$i] == '') continue; ?>
                               <tr>
                                <td>
                                    <?= date('d-m-Y', $value['updated_at']); ?>
                                </td>
                                <?php $user = User::find()->where(['id' => $value['uid']])->one(); ?>
                                <td>
                                    <?= $user['login']; ?>
                                </td>
                                <td>
                                    <?= $user['second_name'].' '.$user['name'].' '.(empty($user['middle_name']) ? '' : $user['middle_name']); ?>
                                </td>
                                <td>
                                    <?= $value['file'.$i]; ?>
                                </td>

                                <td>
                                    <?= Html::a(THelper::t('download'), ['download/download-file', 'id' => $value['uid'], 'name' => $value['file'.$i]], ['style' => 'color: blue',  'data-method' => 'post']); ?>
                                </td>
                                <td>
                                    <?= Html::a('', ['download/delete-file', 'id' => $value['uid'], 'file' => 'file'.$i, 'name' => $value['file'.$i]], ['data-confirm' => THelper::t('are_you_sure'),'class' => 'fa fa-times text-danger text delete_file',  'data-method' => 'post']); ?>
                                </td>
                            </tr>
                            <?php  } ?>

                        <?php }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>

<?php $this->registerJsFile('js/main/uploaded_files.js',['depends'=>['app\assets\AppAsset']]); ?>