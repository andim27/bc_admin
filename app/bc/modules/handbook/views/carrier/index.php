<?php
use yii\helpers\Html;
use app\components\THelper;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = THelper::t('careers');
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    function confirmDelete() {
        if (confirm("<?=THelper::t('you_confirm_the_removal')?>")) {
            return true;
        } else {
            return false;
        }
    }
</script>

<div class="users-index">

    <div class="m-b-md"><h3 class="m-b-none"><?= Html::encode($this->title) ?></h3></div>

    <section class="panel panel-default">
        <header class="panel-heading">
            <?=THelper::t('careers')?>
            <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
        </header>
        <div class="table-responsive">
            <table id="users_list_table" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                <thead>
                <tr>
                    <th width="10%"><?=THelper::t('ordinal')?> №</th>
                    <th width="10%"><?=THelper::t('status')?><!--Статус--></th>
                    <th width="7%"><?=THelper::t('number_of_steps')?><!--Количество шагов--></th>
                    <th width="10%"><?=THelper::t('available_in_any_team_personally_invited_status')?><!--Наличие в любой команде лично приглашенного статуса--></th>
                    <th width="9%"><?=THelper::t('have_another_team_personally_invited_status')?><!--Наличие в другой команде лично приглашенного статуса--></th>
                    <th width="9%"><?=THelper::t('the_period_of_time_in_days_for_the_prize')?><!--Период времени в днях для премии--></th>
                    <th width="7%"><?=THelper::t('bonus_development')?><!--Бонус развития--></th>
                    <th width="10%"><?=THelper::t('avatar_status')?><!--Аватар статуса--></th>
                    <th width="10%"><?=THelper::t('certification')?><!--Сертификат--></th>
                    <th width="10%"></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($model as $key => $models) : ?>
                    <tr>
                        <td><?= $models->index_number; ?></td>
                        <td><?= $models->status_title.', '.$models->abbr; ?></td>
                        <td><?= $models->step_number; ?></td>
                        <td><?= $models->existence_any; ?></td>
                        <td><?= $models->existence_other; ?></td>
                        <td><?= $models->period; ?></td>
                        <td><?= $models->bonus; ?></td>
                        <td><?= Html::img($dir.$models->avatar, array('width'=>80, 'height'=>80)); ?></td>
                        <td><?= Html::img($dir.$models->certificate, array('width'=>80, 'height'=>80)); ?></td>
                        <td>
                            <?= Html::a('', ['update', 'id'=>$models->id], ['class' => 'fa fa-pencil']); ?>
                            <?= Html::a('', ['remove', 'id'=>$models->id], ['class' => 'fa fa-times fa-fw', 'onclick' => 'return confirmDelete()']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?= Html::a(THelper::t('add'), ['create'], ['class' => 'btn btn-s-md m-b-md btn-danger pull-right']) ?>
</div>

