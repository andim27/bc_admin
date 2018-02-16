<?php
use app\components\THelper;
use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('backoffice_promotion_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3 m-b-md">
        <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
    </div>
    <div class="col-md-9 text-right">
        <?= Html::a(THelper::t('backoffice_promotion_add'), ['/business/backoffice/promotion-add'], ['data-toggle'=>'ajaxModal', 'class'=>'btn btn-danger add-purchase']) ?>
    </div>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-promotion table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?= THelper::t('backoffice_promotion_date_start') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_promotion_date_finish') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_promotion_title') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_promotion_author') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_promotion_edit') ?>
                </th>
                <th>
                    <?= THelper::t('backoffice_promotion_remove') ?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($promotions as $p) { ?>
                <tr>
                    <td>
                        <?= gmdate('d.m.Y', $p->dateStart) ?>
                    </td>
                    <td>
                        <?= gmdate('d.m.Y', $p->dateFinish) ?>
                    </td>
                    <td>
                        <?= $p->title ?>
                    </td>
                    <td>
                        <?= $p->author ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/backoffice/promotion-edit', 'id' => $p->id, 'l' => $p->lang], ['style' => 'display:none;', 'class' => 'pencil', 'data-toggle'=>'ajaxModal']) ?>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/backoffice/promotion-remove', 'id' => $p->id, 'l' => $p->lang], ['style' => 'display:none;', 'class' => 'pencil', 'data-toggle'=>'ajaxModal']) ?>
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
    $('.table-promotion').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ]
    });
    $('#languages-list').change(function() {
        window.location.replace('/' + LANG + '/business/backoffice/promotion?l=' + $(this).val());
    });
</script>