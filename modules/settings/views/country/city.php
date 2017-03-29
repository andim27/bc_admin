<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\components\THelper;

$this->title = Html::encode($country_title->title).' города';
$this->params['breadcrumbs'][] = ['label' => THelper::t('countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="city-index">

        <?php if(Yii::$app->session->getFlash('error')) : ?>
            <div class="alert alert-danger alert-error">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong><?= Yii::$app->session->getFlash('error') ?></strong>
            </div>
        <?php elseif(Yii::$app->session->getFlash('success')) : ?>
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong><?= Yii::$app->session->getFlash('success') ?></strong>
            </div>
        <?php endif; ?>

         <section class="panel panel-default">
            <header class="panel-heading">
                <div class="m-b-md"><h3 class="m-b-none"><?=THelper::t('cities_of_country')?><!--Города страни--> <?= Html::encode($country_title->title) ?></h3></div>
            </header>
            <div class="row wrapper">
                <div class="col-sm-4">
                    <small class="text-muted inline m-t-sm m-b-sm">
                        <?php $count_page_next = ($pages->defaultPageSize * $pages->page) + COUNT($cities); ?>
                        <?=THelper::t('displaying')?><!--Показано--> <?= $pages->defaultPageSize * $pages->page; ?> - <?= $count_page_next; ?> <?=THelper::t('from')?><!--из-->  <?= $pages->totalCount; ?> <?=THelper::t('town')?><!--городов-->
                    </small>
                </div>
                <div class="col-sm-3 pull-right">
                    <?= Html::beginForm(['city', 'id'=>$country_title->id], 'get', ['id'=>'search_form_city']); ?>
                    <div class="input-group">
                        <?= Html::input('text', 'search_city', (isset($_GET['search_city']))? $_GET['search_city']:'', ['class'=>'input-sm form-control', 'placeholder'=>'Поиск'] ) ?>
                        <span class="input-group-btn">
                            <?= Html::submitButton(THelper::t('search'), ['class'=>'btn btn-sm btn-default']) ?>
                        </span>
                    </div>
                    <?= Html::endForm(); ?>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped b-t b-light">
                    <thead>
                    <tr>
                        <th width="31%"><?= $sort->link('title') ?></th>
                        <th width="34%"><?= $sort->link('state') ?></th>
                        <th width="34%"><?= $sort->link('region') ?></th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($cities as $city) : ?>
                        <tr data-id="<?= $city->id ?>">
                            <td><?= $city->title ?></td>
                            <td><?= $city->state ?></td>
                            <td><?= $city->region ?></td>
                            <td>
                                <?= Html::a('<i class="fa fa-pencil"></i>', ['#'], ['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown']); ?>
                                <ul class="dropdown-menu pull-right">
                                    <li><?= Html::a(THelper::t('refresh_city'), ['city/update', 'id'=>$city->id, 'id_country'=>$country_title->id], ['data-toggle'=>'ajaxModal']); ?></li>
                                    <li><?= Html::a(THelper::t('remove_city'), ['city/delete', 'id'=>$city->id], ['class'=>'ajaxDeleteCity', 'data-id-city'=>$city->id]); ?></li>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <small class="text-muted inline m-t-sm m-b-sm">

                            <?=THelper::t('displaying')?><!--Показано--> <?= $pages->defaultPageSize * $pages->page; ?> - <?= $count_page_next ?> <?=THelper::t('from')?><!--из--> <?= $pages->totalCount ?> <?=THelper::t('town')?><!--городов-->
                        </small>
                    </div>
                    <div class="col-sm-8 text-right text-center-xs">

                        <?php echo LinkPager::widget([
                                'pagination' => $pages,
                                'firstPageLabel' => THelper::t('to_the_begining'),
                                'lastPageLabel' => THelper::t('to_end')
                            ]);
                        ?>
                    </div>
                </div>
            </footer>
        </section>

        <?= Html::a(THelper::t('add'), ['city/create'], array('class'=>'btn m-b-md btn-danger pull-right','data-toggle'=>'ajaxModal')); ?>
    </div>
<?php $this->registerJsFile('/js/main/regions.js',['depends'=>['app\assets\AppAsset']]); ?>