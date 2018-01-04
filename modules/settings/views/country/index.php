<?php

use yii\helpers\Html;
use app\components\THelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = THelper::t('countries');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="country-index">

    <div class="m-b-md"><h3 class="m-b-none"><?= Html::encode($this->title) ?></h3></div>

    <section class="panel panel-default">
        <header class="panel-heading">
            Страны
            <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
        </header>
        <div class="table-responsive">
            <table id="country_list_table" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                <thead>
                <tr>
                    <th width="45%"><?=THelper::t('title')?><!--Название--></th>
                    <th width="28%"><?=THelper::t('code')?><!--Код--></th>
                    <th width="25%"><?=THelper::t('status')?><!--Статус--></th>
                    <th width="5"></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach($country as $nation) : ?>
                    <tr data-id="<?= $nation->id ?>">
                        <td><?= Html::a( $nation->title, ['city', 'id'=>$nation->id] ) ?></td>
                        <td><?= $nation->iso_code ?></td>
                        <td><?= $nation->condition->title ?></td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['#'], ['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown']); ?>
                            <ul class="dropdown-menu pull-right">
                                <li><?= Html::a(THelper::t('view_city').' '.$nation->title, ['city', 'id'=>$nation->id] ) ?></li>
                                <li><?= Html::a(THelper::t('refresh_country').' '.$nation->title, ['update', 'id'=>$nation->id], ['data-toggle'=>'ajaxModal'] ) ?></li>
                                <li><?= Html::a(THelper::t('remove_country').' '.$nation->title, '#', ['class'=>'ajaxDeleteCountry', 'data-id-country'=>$nation->id]) ?></li>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?= Html::a(THelper::t('add'), ['create'], array('class'=>'btn m-b-md btn-danger pull-right','data-toggle'=>'ajaxModal')); ?>

</div>
<?php $this->registerJsFile('/js/main/regions.js',['depends'=>['app\assets\AppAsset']]); ?>