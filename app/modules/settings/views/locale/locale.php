
<?php
use yii\helpers\Html;
use app\components\LocaleWidget;
use app\components\THelper;
$this->title =  THelper::t('language_standarts');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
define("ROOT_T", "http://" . $_SERVER['SERVER_NAME']);
?>
<!-- <link rel="stylesheet" href="<?=ROOT_T?>/js/datatables/datatables.css" type="text/css"> -->
<section class="panel panel-default">
    <header class="panel-heading bg-light">
        <ul class="nav nav-tabs ">
            <li class="active"><a href="#lang_list" data-toggle="tab"><?= THelper::t('language_list')?></a></li>
            <li class=""><a href="#translate_list" data-toggle="tab"><?= THelper::t('translate')?></a></li>
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="lang_list">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        <?=THelper::t('datagrid')?>
                        <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                    </header>
                    <div class="table-responsive">
                        <table id="lang_list_table" class="table table-striped m-b-none unique_table_class">
                            <thead>
                            <tr>
                                <th width="40%"><?=THelper::t('title')?></th>
                                <th width="45%"><?=THelper::t('short_name')?></th>
                                <th width="15%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($data['langlist'] as $key => $value){ ?>
                                <tr>
                                    <th width="40%"><?=$value['title'];?></th>
                                    <th width="45%"><?=$value['tag'];?></th>
                                    <th width="15%">
                                    <?= Html::a('<i class="fa fa-pencil"></i>', ['edit-languages', 'id'=>$value['id']], array('data-toggle'=>'ajaxModal')); ?>
                                   <!--  <a href="/locale/default/edit-languages?id=<?=$value['id'];?>" data-toggle="ajaxModal"><i class="fa fa-pencil"></i></a> --></th>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                 <?= Html::a(THelper::t('add'), ['add-languages'], array('class'=>'btn btn-s-md btn-danger pull-right','data-toggle'=>'ajaxModal')); ?>
            </div>
            <div class="tab-pane lang_table_t" id="translate_list">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        <select id="lang_change" class="btn dropdown-toggle selectpicker btn-default">
                        <?php foreach ($data['langlist'] as $key => $value){?>
                            <option value="<?=$value['prefix'];?>" <?php echo ($data['lang_id']==$value['id'])?'selected':'';?>><?=$value['title'];?></option>
                         <?php } ?>
                        </select>
                        <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                    </header>
                    <div class="table-responsive ajax_dt">
                        <table id="datatable-locale2" class="table table-striped m-b-none unique_table_class" data-ride="datatables2">
                            <thead>
                            <tr>
                                <th width="40%"><?= THelper::t('title')?></th>
                                <th width="45%"><?=THelper::t('translate')?></th>
                                <th width="15%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($data['localelist'] as $key => $value){
                                ?>
                                <tr>
                                    <th width="40%"><?=$value['key'];?></th>
                                    <th width="45%"><?=$value['translate'];?></th>
                                    <th width="15%">
                                    <?= Html::a('<i class="fa fa-pencil"></i>', ['edit-locale', 'id'=>$value['id']], array('data-toggle'=>'ajaxModal')); ?>
                                   <!--  <a href="/locale/default/edit-languages?id=<?=$value['id'];?>" data-toggle="ajaxModal"><i class="fa fa-pencil"></i></a> --></th>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
                <?php /* Html::a(LocaleWidget::widget(['value' => 'add_button']), ['add-locale'], array('class'=>'btn btn-s-md btn-danger pull-right','data-toggle'=>'ajaxModal')); */?>
            </div>
        </div>
    </div>

</section>


<?php $this->registerJsFile('/js/main/change_lang.js',['depends'=>['app\assets\AppAsset']]); ?>