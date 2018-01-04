<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('users_docs_title'); ?></h3>
</div>
<div class="row m-b">
    <div class="col-md-4">
        <label for="number"><?= THelper::t('user_docs_number_documents') ?></label>
    </div>
    <div class="col-md-2">
        <?= Html::dropDownList('number', $number, $numbers, ['id' => 'number', 'class' => 'form-control']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <span class="h4"><?=THelper::t('user_docs_table')?></span>
            </header>
            <div class="table-responsive">
                <table class="table table-docs table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>
                    <?=THelper::t('user_docs_date')?>
                </th>
                <th>
                    <?=THelper::t('user_docs_user_login')?>
                </th>
                <th>
                    <?=THelper::t('user_docs_user_email')?>
                </th>
                <th>
                    <?=THelper::t('user_docs_user_fname_sname')?>
                </th>
                <th>
                    <?=THelper::t('user_docs_download')?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($docs as $doc) { ?>
                <tr>
                    <td>
                        <?= gmdate('d.m.Y', $doc->dateCreate) ?>
                    </td>
                    <td>
                        <?= $doc->user->username ?>
                    </td>
                    <td>
                        <?= $doc->user->email ?>
                    </td>
                    <td>
                        <?= $doc->user->firstName ?> <?= $doc->user->secondName ?>
                    </td>
                    <td>
                        <a href="https://bc.businessprocess.biz/uploads/<?= $doc->user->id ?>/<?= $doc->fileName ?>"><?= $doc->fileName ?></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
            </div>
        </section>
    </div>
</div>
<script>
    var table = $('.table-docs').dataTable({
        language: TRANSLATION,
        lengthMenu: [25, 50, 75, 100],
    });
</script>