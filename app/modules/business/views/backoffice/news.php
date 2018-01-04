<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('backoffice_news_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3 m-b-md">
        <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
    </div>
    <div class="col-md-9 text-right">
        <?= Html::a(THelper::t('backoffice_news_add'), ['/business/backoffice/news-add'], ['data-toggle'=>'ajaxModal', 'class'=>'btn btn-danger add-purchase']) ?>
    </div>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-news table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?= THelper::t('backoffice_news_date_publication') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_news_title') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_news_author') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_news_edit') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_news_remove') ?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($news as $n) { ?>
                <tr>
                    <td>
                        <?= gmdate('d.m.Y', $n->dateOfPublication) ?>
                    </td>
                    <td>
                        <?= $n->title ?>
                    </td>
                    <td>
                        <?= $n->author ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/backoffice/news-edit', 'id' => $n->id, 'l' => $n->lang], ['style' => 'display:none;', 'class' => 'pencil', 'data-toggle'=>'ajaxModal']) ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/backoffice/news-remove', 'id' => $n->id, 'l' => $n->lang], ['style' => 'display:none;', 'class' => 'pencil', 'onclick' => 'return confirmRemoving();']) ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<?php $this->registerJsFile('//cdn.tinymce.com/4/tinymce.min.js'); ?>
<script>
    var language = $('#languages-list').val();
    var author = '<?= $user->username ?>';
    $('.pencil').show();
    $('.table-news').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ]
    });
    $('#languages-list').change(function() {
        window.location.replace('/' + LANG + '/business/backoffice/news?l=' + $(this).val());
    });
    function confirmRemoving() {
        if (confirm("<?= THelper::t('backoffice_news_confirm_removing') ?>")) {
            return true;
        } else {
            return false;
        }
    }
</script>