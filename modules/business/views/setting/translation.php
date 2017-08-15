<?php
    use app\components\THelper;
    use yii\helpers\Html;
use yii\widgets\LinkPager;

?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('settings_translation_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3 m-b">
        <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
    </div>
    <div class="col-md-3 m-b m-t-xs">
        <?= Html::checkbox('empty_values', false, ['label' => THelper::t('empty_values')]) ?>
    </div>
    <div class="col-md-6 m-b text-right">
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
                    <?= THelper::t('settings_translation_value') ?> (ru)
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

            </tbody>
        </table>
    </div>
</section>

<script>
    var emptyOnly = false;
    var table = $('.table-translations');

    $('.pencil').show();

    table = table.dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/' + LANG + '/business/setting/translation',
            "data": function ( d ) {
                d.language = "<?= Yii::$app->request->get('l'); ?>";
                d.empty_only = emptyOnly
            }
        },
        "columns": [
            {"data": "stringId"},
            {"data": "ruStringValue"},
            {"data": "stringValue"},
            {"data": "countryId"},
            {"data": "comment"},
            {"data": "action"},
        ],
        "order": [[ 5, "desc" ]],
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            "render":  function (data, type, full) {
                var actions = "<div  style='min-width: 50px'><a href='/" + LANG + "/business/setting/edit-translation?stringId=" + encodeURI(full['stringId']) + "&countryId=" + full['countryId'] + "' class = 'pencil' data-toggle = 'ajaxModal'><i class='fa fa-pencil'></i></a>";

                if (full['action']) {
                    actions += "&nbsp;(" + full['action'] + ")&nbsp;<a href='/" + LANG + "/business/setting/delete-translation?stringId=" + encodeURI('карта AdvCash') + "&countryId=" + full['countryId'] + "&id=" + full['id'] + "' class = 'remove' data-toggle = 'ajaxModal'><i class='fa fa-times'></i></a>";
                }

                return actions + '</div>';
            }
        } ]
    });

    $('#languages-list').change(function() {
        window.location.replace('/' + LANG + '/business/setting/translation?l=' + $(this).val());
    });

    $('input[name="empty_values"]').on('change',  function(){
        emptyOnly = this.checked;

        table.api().ajax.reload(null, false);
    });

    $(document).on('click', '.save-translation', function(e){
        e.preventDefault();

        var $form = $('form#TranslationForm');

        table.api().ajax.reload(null, false);
        $.post($form.attr("action"), $form.serialize())
            .done(function(result) {
                table.api().ajax.reload(null, false);
            })
            .fail(function() {
                    alert("Error.");
                }
            );

        return false;
    });

    $(document).on('click', '.delete-translation', function(e){
        e.preventDefault();

        var $form = $('form#TranslationDeleteForm');

        table.api().ajax.reload(null, false);
        $.post($form.attr("action"), $form.serialize())
            .done(function(result) {
                table.api().ajax.reload(null, false);
            })
            .fail(function() {
                    alert("Error.");
                }
            );

        return false;
    });
</script>