<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('backoffice_resource_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3 m-b-md">
        <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
    </div>
    <div class="col-md-9 text-right m-b">
        <?= Html::a(THelper::t('backoffice_resource_add'), ['/business/backoffice/add-resource'], ['data-toggle'=>'ajaxModal', 'class'=>'btn btn-info']) ?>
    </div>
</div>
<div id="resources">
    <?= $this->render('_resource', [
        'langauge' => $language,
        'resourceForms' => $resourceForms
    ]); ?>
</div>
<script>
    $('#languages-list').change(function() {
        window.location.replace('/' + LANG + '/business/backoffice/resource?l=' + $(this).val());
    });
</script>