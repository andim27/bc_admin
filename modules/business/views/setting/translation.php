<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('settings_translation_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3 m-b">
        <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
    </div>
    <div class="col-md-9 m-b text-right">
        <?= Html::a(THelper::t('settings_translation_export_to_excel'), ['/business/setting/export-translation/', 'l' => $language], ['class' => 'btn btn-success']) ?>
        <?= Html::a(THelper::t('settings_translation_import_from_excel'), ['/business/setting/import-translation/', 'l' => $language], ['class' => 'btn btn-success', 'data-toggle'=>'ajaxModal']) ?>
    </div>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?= THelper::t('settings_translation_id') ?>
                </th>
                <th>
                    <?= THelper::t('settings_translation_value') ?>
                </th>
                <th>
                    <?= THelper::t('settings_translation_language') ?>
                </th>
                <th>
                    <?= THelper::t('settings_translation_comment') ?>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($translations as $key => $translation) { ?>
                <tr>
                    <td>
                        <?= $translation->stringId ?>
                    </td>
                    <td>
                        <?= $translation->stringValue ?>
                    </td>
                    <td>
                        <?= $translation->countryId ?>
                    </td>
                    <td>
                        <?= $translation->comment ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/setting/edit-translation', 'stringId' => $translation->stringId, 'countryId' => $translation->countryId], ['style' => 'display:none;', 'class' => 'pencil', 'data-toggle'=>'ajaxModal']) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<script>
    $('.pencil').show();
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ]
    });
    $('#languages-list').change(function() {
        window.location.replace('/' + LANG + '/business/setting/translation?l=' + $(this).val());
    });
</script>