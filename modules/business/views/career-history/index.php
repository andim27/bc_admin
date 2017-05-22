<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('career_history_title') ?></h3>
</div>
<div class="row">
    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . Yii::$app->language . '/business/career-history',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1']
    ]); ?>
    <div class="col-md-2 m-b">
        <?= Html::input('text', 'from', gmdate('Y-m-d', $from), ['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd']) ?>
    </div>
    <div class="col-md-1 m-b text-center">
        &ndash;
    </div>
    <div class="col-md-2 m-b">
        <?= Html::input('text', 'to', gmdate('Y-m-d', $to), ['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd']) ?>
    </div>
    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="row">
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-career-history table-striped datagrid m-b-sm">
                <thead>
                    <tr>
                        <th>
                            <?= THelper::t('career_history_avatar') ?>
                        </th>
                        <th>
                            <?= THelper::t('career_history_username') ?>
                        </th>
                        <th>
                            <?= THelper::t('career_history_firstname') ?>
                        </th>
                        <th>
                            <?= THelper::t('career_history_secondname') ?>
                        </th>
                        <th>
                            <?= THelper::t('career_history_country') ?>
                        </th>
                        <th>
                            <?= THelper::t('career_history_city') ?>
                        </th>
                        <th>
                            <?= THelper::t('career_history_career_rank') ?>
                        </th>
                        <th>
                            <?= THelper::t('career_history_career_date') ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($careerHistory as $ch) { ?>
                        <tr>
                            <td><img src="<?= $ch->avatar ? $ch->avatar : '/images/avatar_default.png'; ?>" width="32" height="32"></td>
                            <td><?= $ch->username ?></td>
                            <td><?= $ch->firstName ?></td>
                            <td><?= $ch->secondName ?></td>
                            <td><?= $ch->country ?></td>
                            <td><?= $ch->city ?></td>
                            <td><?= THelper::t('rank_' . $ch->careerRank) ?></td>
                            <td><?= date('d.m.Y H:i:s', $ch->careerDate) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<script>
    $('.table-career-history').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ]
    });
</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>