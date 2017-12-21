<?php

use yii\helpers\Html;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use app\components\THelper;

$this->title = THelper::t('all_resources');
$this->params['breadcrumbs'][] = $this->title;


?>

<script>


    $(document).ready(function() {
        $("#allresources-image0").fileinput({
            'showUpload':false,
            'showRemove':true,
            <?php if($model->image):?>
            'initialPreview': [
                "<img src='/uploads/<?= $model->image; ?>' class='file-preview-image' alt='' title='<?= $model->image; ?>'>",
            ]
            <?php endif; ?>
        });

        <?php foreach ($data as $d){?>
        $("#allresources-image<?= $d->id ?>").fileinput({
            'showUpload':false,
            'showRemove':true,
            <?php if($d->image):?>
            'initialPreview': [
                "<img src='/uploads/<?= $d->image; ?>' class='file-preview-image' alt='' title='<?= $d->image; ?>'>",
            ]
            <?php endif; ?>
        });
       <?php }?>


    });

</script>

 <div class="container">
    <div class="row new_res">
        <br><br><br><br><br>
        <div class="col-sm-12">
            <div class="col-sm-12">
                <?php $form = ActiveForm::begin(['id' => 'form0',
                    'options' => ['enctype' => 'multipart/form-data'],
                ]); ?>

                <div class="col-sm-4">
                    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
                        'options' => ['accept' => 'image/jpg,gif,png', 'id' => 'allresources-image0'],
                        'pluginOptions' => [
                            'showRemove' => true,
                            'showUpload' => false
                        ]
                    ])->label(false); ?>
                </div>

                <div class="col-sm-8">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="pull-left control-label" style="margin-top: 5px"><?= THelper::t('site_address'); ?> </label>
                            </div>

                            <div class="col-sm-5">
                                <?= $form->field($model, 'address')->textInput(['class' => 'form-control pull-right', 'placeholder' => 'example.com'])->label(false) ?>
                            </div>

                            <div class="col-sm-3">
                                <label class="pull-left control-label" style="margin-top: 5px"><?= THelper::t('view_resource'); ?> </label>
                                <label class="switch pull-right"> <input type="checkbox" checked name="AllResources[view]"> <span></span> </label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="pull-left control-label" style="margin-top: 5px"><?= THelper::t('resources_title'); ?> </label>
                            </div>

                            <div class="col-sm-5">
                                <?= $form->field($model, 'name')->textInput(['class' => 'form-control pull-right'])->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label class="pull-left control-label" style="margin-top: 5px"><?= THelper::t('short_description'); ?> </label>
                            </div>

                            <div class="col-sm-8">
                                <?= $form->field($model, 'description')->textarea(['class' => 'form-control pull-right'])->label(false) ?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-inline">
                            <div class="col-sm-11">
                                <?= Html::submitButton(THelper::t('save'), ['data-method' => 'post','class' => 'btn btn-primary pull-right', 'style' => 'background-color: #4cc159']) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <br>
        <br>
        <br>
    </div>


    <?php
    if(!empty($data)){?>
        <?php  foreach($data as $dat){?>

            <div class="row" id="row-<?= $dat->id?>">
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <?php $form = ActiveForm::begin(['id' => 'form'.$dat->id,
                            'options' => ['enctype' => 'multipart/form-data']
                        ]); ?>

                        <div class="col-sm-4">
                            <?php  ?>
                            <?= $form->field($dat, 'image')->widget(FileInput::classname(), [
                                'options' => ['accept' => 'image/jpg,gif,png', 'id' => 'allresources-image'.$dat->id],
                                'pluginOptions' => [
                                    'showRemove' => true,
                                    'showUpload' => false
                                ]
                            ])->label(false); ?>
                        </div>

                        <div class="col-sm-8">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <label class="pull-left control-label" style="margin-top: 5px"><?= THelper::t('site_address'); ?> </label>
                                    </div>

                                    <div class="col-sm-5">
                                        <?= $form->field($dat, 'address')->textInput(['id' => 'allresources-address'.$dat->id,'class' => 'form-control pull-right', 'placeholder' => 'example.com'])->label(false) ?>
                                    </div>

                                    <div class="col-sm-3">
                                        <label class="pull-left control-label" style="margin-top: 5px"><?= THelper::t('view_resource'); ?> </label>
                                        <?php
                                        $check = '';
                                        $dat->view == 1 ? $check = 'checked' : $check = '';
                                        ?>
                                        <label class="switch pull-right"> <input type="checkbox" <?= $check ?> name="AllResources[view]"> <span></span> </label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <label class="pull-left control-label" style="margin-top: 5px"><?= THelper::t('resources_title'); ?> </label>
                                    </div>

                                    <div class="col-sm-5">
                                        <?= $form->field($dat, 'name')->textInput(['id' => 'allresources-name'.$dat->id,'class' => 'form-control pull-right'])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <label class="pull-left control-label" style="margin-top: 5px"><?= THelper::t('short_description'); ?> </label>
                                    </div>

                                    <div class="col-sm-8">
                                        <?= $form->field($dat, 'description')->textarea(['id' => 'allresources-description'.$dat->id,'class' => 'form-control pull-right'])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-inline">
                                    <div class="col-sm-11">
                                        <?php  $dat->image == '' ? $dat->image = 1 : $dat->image?>
                                        <?= Html::a(THelper::t('save'), ['send', 'id' => $dat->id],['data-method' => 'post','id' => 'id-'.$dat->id,'class' => 'btn btn-primary pull-right', 'style' => 'background-color: #4cc159']) ?>
                                        <?= Html::a(THelper::t('delete'), ['delete', 'id' => $dat->id, 'file' => $dat->image], ['data-confirm' => THelper::t('are_you_sure'),'class' => 'btn btn-danger pull-right', 'style' => 'margin-right: 20px; background-color: #ff0e27']) ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="row">
                                <div class="form-inline">
                                    <div class="col-sm-11">
                                        <input type="hidden" value="<?= $dat->id ?>">
                                        <?= Html::Button(THelper::t('add_resource'), ['class' => 'btn btn-primary add_res', 'style' => 'background-color: #3e44cf']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <br>
            <br>

            <?php  }?>
    <?php  }?>
 </div>

<?php $this->registerJsFile('js/main/all_resources.js',['depends'=>['app\assets\AppAsset']]); ?>

