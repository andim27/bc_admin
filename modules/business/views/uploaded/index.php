<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\components\THelper;
    $this->title = THelper::t('documents_upload');
    $this->params['breadcrumbs'][] = $this->title;
?>
<?php if ($successText) { ?>
    <div class="alert alert-success">
        <?= $successText ?>
    </div>
<?php } else if ($errorsText) {
    foreach ($errorsText as $key => $e) { ?>
        <div class="alert alert-danger">
            <?= current($e) ?>
        </div>
    <?php } ?>
<?php } ?>
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                <span class="h4"><?= THelper::t('what_documents_are_require_and_how_to_upload') ?></span>
            </header>
            <div class="slimScrollDiv">
                <section class="panel-body slim-scroll">
                    <article class="media">
                        <div class="media-body">
                            <?= THelper::t('upload_documents_info_text') ?>
                        </div>
                    </article>
                </section>
            </div>
        </section>
    </div>
    <div class="col-sm-12">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?php for ($i = 1; $i <= $qtyDocsToLoading; $i++) { ?>
            <div class="col-sm-12" id = "id-<?= $i ?>" >
                <?= $form->field($uploadForm, 'file_' . $i)->fileInput([
                    'id' => 'filestyle-' . $i,
                    'class' => 'filestyle',
                    'data-icon' => 'false',
                    'data-key' => $i,
                    'data-classbutton' => 'btn btn-default but-' . $i . '',
                    'data-classinput' => 'form-control inline input-s inp-' . $i . '',
                ])->label(false); ?>
                <?= Html::button(THelper::t('delete'), ['data-index' => $i, 'class' => 'btn-del', 'style' => 'display: none; border: none; background-color: #f7f7f7; padding: 0; margin: 0 0 10px 0px; color: blue;', 'id' => 'del-' . $i]); ?>
            </div>
        <?php } ?>
        <div id="upload" data-tr="<?= THelper::t('upload') ?>" style="display:none"></div>
        <div id="uploaded" data-tr="<?= THelper::t('uploaded') ?>" style="display:none"></div>
        <div class="col-sm-12 m-b">
            <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-s-md btn-primary']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php $this->registerJsFile('js/main/uploaded_files_business.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/file-input/bootstrap-filestyle.min.js',['depends'=>['app\assets\AppAsset']]); ?>