<?php use app\components\THelper;
    use yii\helpers\Html; ?>

    <div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('career'); ?></h3>
    </div>
    <div class="row">
        <?php if (isset($language) && isset($translationList)) { ?>
            <div class="col-md-3 m-b-md">
                <?= Html::dropDownList('languages', $language, $translationList, ['id' => 'languages-list', 'class' => 'form-control']) ?>
            </div>
        <?php } ?>

        <div class="col-md-9 text-right">
            <?= Html::a(THelper::t('add'), ['/business/reference/career-add', 'l' => $language], ['data-toggle' => 'ajaxModal', 'class' => 'btn btn-danger']) ?>
        </div>
    </div>
<?php if (!empty($careers)) { ?>
    <section class="panel">
        <div class="table-responsive" style="overflow-y:scroll;">
            <table class="table table-striped table-results">
                <thead>
                <tr>
                    <th><?= THelper::t('serial_number') ?></th>
                    <th><?= THelper::t('status') ?></th>
                    <th><?= THelper::t('short_name') ?></th>
                    <th><?= THelper::t('steps') ?></th>
                    <th><?= THelper::t('self_invited_status_in_one_branch') ?></th>
                    <th><?= THelper::t('self_invited_status_in_another_branch') ?></th>
                    <th><?= THelper::t('self_invited_status_in_spillover') ?></th>
                    <th><?= THelper::t('self_invited_number_in_spillover') ?></th>
                    <th><?= THelper::t('time_for_award') ?></th>
                    <th><?= THelper::t('bonus_development') ?></th>
                    <th><?= THelper::t('status_avatar') ?></th>
                    <th><?= THelper::t('status_certificate') ?></th>
                    <th><?= THelper::t('edit') ?></th>
                    <th><?= THelper::t('remove') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($careers)) { ?>
                    <?php foreach ($careers as $career) { ?>
                        <tr>
                            <td><?=$career['rank']?></td>
                            <td><?=$career['rank_name']?></td>
                            <td><?=$career['short_name']?></td>
                            <td><?=$career['steps']?></td>
                            <td><?=$career['self_invited_status_in_one_branch']?></td>
                            <td><?=$career['self_invited_status_in_another_branch']?></td>
                            <td><?=$career['self_invited_status_in_spillover']?></td>
                            <td><?=$career['self_invited_number_in_spillover']?></td>
                            <td><?=$career['time']?></td>
                            <td><?=$career['bonus']?></td>
                            <td>
                                <img class="thumb pull-left m-r" style="max-width: 100px;max-height: 100px;" src="<?=$career['rank_image']?>">
                            </td>
                            <td>
                                <img class="thumb pull-left m-r" src="<?=$career['certificate_image']?>">
                            </td>
                            <td>
                                <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/reference/career-edit', 'id' => $career['id'], 'l' => $career['lang']], ['class' => 'pencil', 'data-toggle' => 'ajaxModal']) ?>
                            </td>
                            <td>
                                <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/reference/career-remove', 'id' => $career['id'], 'l' => $career['lang']], ['class' => 'pencil', 'onclick' => 'return confirmRemoving();']) ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <script>
        function confirmRemoving() {
            return !!confirm("<?= THelper::t('confirm_removing') ?>");
        }
        $('#languages-list').change(function() {
            window.location.replace('/' + LANG + '/business/reference/career?l=' + $(this).val());
        });
    </script>
<?php } else { ?>
    <?= THelper::t('career_no_results') ?>
<?php } ?>