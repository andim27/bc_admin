<?php

/* @var $this yii\web\View
 * @var $i
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use app\components\THelper;

$this->title = THelper::t('promotions');
$this->params['breadcrumbs'][] = $this->title;

?>
<section class="panel panel-default">
    <header class="panel-heading bg-light">
        <ul class="nav nav-tabs nav-justified">
            <li class="active">
                <a href="#home" data-toggle="tab"><?=THelper::t('promotions')?><!--Рекламные акции--></a>
            </li>
            <li class="">
                <a href="#profile" data-toggle="tab"><?=THelper::t('settings_promotions')?><!--Настройки рекламных акций--></a>
            </li>
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="home">

                <?php $form = ActiveForm::begin(); ?>
                <div class="form-inline">
                    <?= Html::label(THelper::t('date'), 'post_at', ['class' => 'control-label dateday']) ?>
                    <?= $form->field($model, 'post_at')->textInput([
                        'maxlength'=>16, 'class'=>'input-sm input-s datepicker-input form-control',
                        'data-date-format'=>'dd-mm-yyyy'
                    ])->label(false) ?>


                    <?= $form->field($model, 'rememberMe')->checkbox(['class' =>'normal remme'])
                        ->label(THelper::t('time'), ['class' => 'control-label remmelable'])?>

                    <?php $mas = array();
                    for($i = 0; $i <= 23; $i++) { $mas[]= $i;}
                    echo $form->field($model, 'hours')->dropDownList($mas, ['class'=>'input-sm', 'style'=>'background:#FFF'])
                        ->label(THelper::t('h'), ['class' => 'control-label timelable']); ?>

                    <?php $mas = array();
                    for($i = 0; $i <= 59; $i++) { $mas[]= $i;}
                    echo $form->field($model, 'minutes')->dropDownList($mas, ['class'=>'input-sm', 'style'=>'background:#FFF'])
                        ->label(THelper::t('min'), ['class' => 'control-label timelable']); ?>

                </div>

                <?= $form->field($model, 'promotion_begin')->textInput([
                    'maxlength'=>16, 'class'=>'input-sm input-s datepicker-input form-control',
                    'data-date-format'=>'dd-mm-yyyy'
                ]) ?>

                <?= $form->field($model, 'promotion_end')->textInput([
                    'maxlength'=>16, 'class'=>'input-sm input-s datepicker-input form-control',
                    'data-date-format'=>'dd-mm-yyyy'
                ]) ?>

                <?= Html::label(THelper::t('topic_stocks').'*', 'title', ['class' => 'control-label themenews']) ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'style' => 'width:40%'])->label(false) ?>

                <?= $form->field($model, 'description')->widget(CKEditor::className(),[
                    'editorOptions' => [
                        'preset' => 'full',
                        'inline' => false,
                    ],
                ]);
                ?>

                <?= Html::a(THelper::t('preview﻿'), ['promotions/prommodal'], ['data-toggle'=>'ajaxModal', 'class' => 'btn btn-primary pull-left', 'name' => 'preview', 'id' => 'preview']); ?>
                <?= Html::submitButton(THelper::t('save_changes'), ['class' => 'btn btn-info pull-right', 'name' => 'save_news']) ?>
                <?php ActiveForm::end(); ?>
                <br><br><br><br>

            </div>
            <div class="tab-pane" id="profile">

                <section class="panel panel-default">
                    <header class="panel-heading bg-light">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a href="#buy" data-toggle="tab">
                                    <?=THelper::t('for_the_purchase_of_the_product')?><!--За покупку продукта (товара)-->
                                </a>
                            </li>
                            <li>
                                <a href="#steps" data-toggle="tab">
                                    <?=THelper::t('for_steps')?><!--За шаги-->
                                </a>
                            </li>
                            <li>
                                <a href="#statuses" data-toggle="tab">
                                    <?=THelper::t('for_status')?><!--За статусы-->
                                </a>
                            </li>
                        </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="buy">
                                <section class="panel panel-default">
                                    <div class="table-responsive">
                                        <table id="MyStretchGrid" class="table table-striped datagrid m-b-sm unique_table_class">
                                            <thead>
                                            <tr>
                                                <th data-property="toponymName" class="sortable">
                                                    <?=THelper::t('product_code')?><!--Код товара-->
                                                </th>
                                                <th data-property="countrycode" class="sortable">
                                                    <?=THelper::t('product_name')?><!--Название товара-->
                                                </th>
                                                <th data-property="population" class="sortable">
                                                    <?=THelper::t('number_of_usd_for_the_purchase_of_personal_user')?><!--Количество USD при личной покупке пользователю-->
                                                </th>
                                                <th data-property="fcodeName" class="sortable">
                                                    <?=THelper::t('number_of_issued_usd_sponsor')?><!--Количество USD даваемых спонсору-->
                                                </th>
                                                <th data-property="fcodeName" class="sortable">
                                                    <?=THelper::t('date_of_commencement_of_the_action')?><!--Дата начала акции-->
                                                </th>
                                                <th data-property="fcodeName" class="sortable">
                                                    <?=THelper::t('end_date_of_the_action')?><!--Дата окончания акции-->
                                                </th>
                                                <th data-property="geonameId" class="sort">

                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($data['model_buy'])){foreach ($data['model_buy'] as $key => $value){ ?>
                                            <tr>
                                                <td>
                                                    <?=$value['sku_id'];?>
                                                </td>
                                                <td>
                                                    <?=$value['product_title'];?>
                                                </td>
                                                <td>
                                                    <?=$value['usd_lpp'];?>
                                                </td>
                                                <td>
                                                    <?=$value['usd_ds'];?>
                                                </td>
                                                <td>
                                                    <?= date('d-m-Y', $value['promotion_begin']);?>
                                                </td>
                                                <td>
                                                    <?= date('d-m-Y', $value['promotion_end']);?>
                                                </td>
                                                <td>
                                                    <?= Html::a('', ['promotions/correct', 'id'=>$value['id']], ['data-toggle'=>'ajaxModal', 'class' => 'fa fa-pencil']); ?>
                                                    <?= Html::a('', ['promotions/delete', 'id'=>$value['id']], ['data-confirm' => THelper::t('delete_entry'),'class' => 'fa fa-times fa-fw',  'data-method' => 'post',]); ?>
                                                </td>
                                            </tr>
                                            <?php }
                                            }
                                            ?>
                                            </tbody>

                                        </table>
                                        <?= Html::a(THelper::t('add_a_note')/*'Добавить запись'*/, ['promotions/prommodalbuy'], ['data-toggle'=>'ajaxModal', 'class' => 'btn btn-primary pull-right', 'name' => 'add_note', 'id' => 'add_note']); ?>
                                    </div>
                                </section>
                            </div>

                            <div class="tab-pane" id="steps">
                                <section class="panel panel-default">
                                    <div class="table-responsive">
                                        <table id="MyStretchGrid2" class="table table-striped datagrid m-b-sm unique_table_class">
                                            <thead>
                                            <tr>
                                                <th data-property="toponymName" class="sortable">
                                                    <?=THelper::t('product_code_which_should_own_partner')?><!--Код товара, которым должен владеть партнер-->
                                                </th>
                                                <th data-property="countrycode" class="sortable">
                                                    <?=THelper::t('product_name')?><!--Название товара-->
                                                </th>
                                                <th data-property="population" class="sortable">
                                                    <?='1 '.THelper::t('step_s_usd').' 100'?><!--За 1шаг(ов)100 USD-->
                                                </th>
                                                <th data-property="fcodeName" class="sortable">
                                                    <?=THelper::t('date_of_commencement_of_the_action')?><!--Дата начала акции-->
                                                </th>
                                                <th data-property="fcodeName" class="sortable">
                                                    <?=THelper::t('end_date_of_the_action')?><!--Дата окончания акции-->
                                                </th>
                                                <th data-property="geonameId" class="sort">

                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($data['model_step'])){foreach ($data['model_step'] as $key => $value){ ?>
                                                <tr>
                                                    <td>
                                                        <?=$value['sku_id'];?>
                                                    </td>
                                                    <td>
                                                        <?=$value['product_title'];?>
                                                    </td>
                                                    <td>
                                                        <?=$value['sum'];?>
                                                    </td>
                                                    <td>
                                                        <?= date('d-m-Y', $value['promotion_begin']);?>
                                                    </td>
                                                    <td>
                                                        <?= date('d-m-Y', $value['promotion_end']);?>
                                                    </td>
                                                    <td>
                                                        <?= Html::a('', ['promotions/correctstep', 'id'=>$value['id']], ['data-toggle'=>'ajaxModal', 'class' => 'fa fa-pencil']); ?>
                                                        <?= Html::a('', ['promotions/deletestep', 'id'=>$value['id']], ['data-confirm' => THelper::t('delete_entry'),'class' => 'fa fa-times fa-fw',  'data-method' => 'post',]); ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                            }
                                            ?>
                                            </tbody>

                                        </table>
                                        <?= Html::a(THelper::t('add_a_note'), ['promotions/prommodalstep'], ['data-toggle'=>'ajaxModal', 'class' => 'btn btn-primary pull-right', 'name' => 'add_note_to_step', 'id' => 'add_note_to_step']); ?>
                                    </div>
                                </section>
                            </div>

                            <div class="tab-pane" id="statuses">
                                <section class="panel panel-default">
                                    <div class="table-responsive">
                                        <table id="MyStretchGrid3" class="table table-striped datagrid m-b-sm unique_table_class">
                                            <thead>
                                            <tr>
                                                <th data-property="toponymName" class="sortable">
                                                    <?=THelper::t('status_code')?><!--Код статуса-->
                                                </th>
                                                <th data-property="countrycode" class="sortable">
                                                    <?=THelper::t('status_name')?><!--Название статуса-->
                                                </th>
                                                <th data-property="population" class="sortable">
                                                    <?=THelper::t('amount_usd_user')?><!--Сумма USD пользователю-->
                                                </th>
                                                <th data-property="fcodeName" class="sortable">
                                                    <?=THelper::t('date_of_commencement_of_the_action')?><!--Дата начала акции-->
                                                </th>
                                                <th data-property="fcodeName" class="sortable">
                                                    <?=THelper::t('end_date_of_the_action')?><!--Дата окончания акции-->
                                                </th>
                                                <th data-property="geonameId" class="sort">

                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($data['model_status'])){foreach ($data['model_status'] as $key => $value){ ?>
                                                <tr>
                                                    <td>
                                                        <?=$value['status_id'];?>
                                                    </td>
                                                    <td>
                                                        <?=$value['status_title'];?>
                                                    </td>
                                                    <td>
                                                        <?=$value['sum'];?>
                                                    </td>
                                                    <td>
                                                        <?= date('d-m-Y', $value['promotion_begin']);?>
                                                    </td>
                                                    <td>
                                                        <?= date('d-m-Y', $value['promotion_end']);?>
                                                    </td>
                                                    <td>
                                                        <?= Html::a('', ['promotions/correctstatus', 'id'=>$value['id']], ['data-toggle'=>'ajaxModal', 'class' => 'fa fa-pencil']); ?>
                                                        <?= Html::a('', ['promotions/deletestatus', 'id'=>$value['id']], ['data-confirm' => THelper::t('delete_entry'),'class' => 'fa fa-times fa-fw',  'data-method' => 'post',]); ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                            }
                                            ?>
                                            </tbody>

                                        </table>
                                        <?= Html::a(THelper::t('add_a_note'), ['promotions/prommodalstatus'], ['data-toggle'=>'ajaxModal', 'class' => 'btn btn-primary pull-right', 'name' => 'add_note_to_status', 'id' => 'add_note_to_status']); ?>
                                    </div>
                                </section>
                            </div>

                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</section>

<?php $this->registerJsFile('js/datepicker/bootstrap-datepicker.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/promotions.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/libs/underscore-min.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/fuelux/fuelux.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/app.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/app.plugin.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/change_prombuy.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>
