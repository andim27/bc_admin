<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 14.12.2015
 * Time: 10:12
 */
use app\components\THelper;
use yii\helpers\Html;

$this->title = THelper::t('users_buy');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-6">
        <div class="col-xs-7">
            <div class="input-group m-b">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control log" placeholder="<?=THelper::t('search_login')?>">
            </div>
        </div>
        <div class="col-xs-5">
            <a href="#" class="btn btn-s-md btn-info search"><?=THelper::t('search')?></a>
        </div>
    </div>
</div>

<section class="panel panel-default">
    <header class="panel-heading bg-light">
        <ul class="nav nav-tabs ">
            <li class="active"><a href="#by_user" data-toggle="tab"><?=THelper::t('by_user')?><!--По пользователю--></a></li>
            <li><a href="#common_list" data-toggle="tab"><?=THelper::t('common_list')?><!--Общий список--></a></li>
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content">

            <div class="tab-pane active conteiner_tab" id="by_user">
                <section class="panel panel-default">
                    <div class="table-responsive ajax">
                        <table id="datatable-t" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                            <thead>
                                <tr>
                                    <th width="14,25%"><?=THelper::t('purchase_date')?><!--Дата покупки--></th>
                                    <th width="14,25%"><?=THelper::t('product_code')?><!--Код товара--></th>
                                    <th width="14,25%"><?=THelper::t('product_name')?><!--Название товара--></th>
                                    <th width="14,25%"><?=THelper::t('price')?><!--Цена--></th>
                                    <th width="14,25%"><?=THelper::t('scoping_cost')?><!--балловая стоимость--></th>
                                    <th width="14,25%"><?=THelper::t('login')?><!--Логин--></th>
                                    <th width="14,25%"><?=THelper::t('full_name')?><!--ФИО--></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </section>
            </div>

            <div class="tab-pane conteiner_tab" data-url="shares-step" id="common_list">
                <section class="panel panel-default">
                    <div class="table-responsive">
                        <table id="datatable-t1" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                            <thead>
                                <tr>
                                    <th width="14,25%"><?=THelper::t('purchase_date')?><!--Дата покупки--></th>
                                    <th width="14,25%"><?=THelper::t('product_code')?><!--Код товара--></th>
                                    <th width="14,25%"><?=THelper::t('product_name')?><!--Название товара--></th>
                                    <th width="14,25%"><?=THelper::t('price')?><!--Цена--></th>
                                    <th width="14,25%"><?=THelper::t('scoping_cost')?><!--балловая стоимость--></th>
                                    <th width="14,25%"><?=THelper::t('login')?><!--Логин--></th>
                                    <th width="14,25%"><?=THelper::t('full_name')?><!--ФИО--></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($models)){
                                foreach ($models as $value){
                                    if(!empty($value['products']['sku'])){
                                    ?>
                                <tr>
                                    <th width="14,25%"><?=date('d-m-y H:i:s',$value['date'])?><!--Дата покупки--></th>
                                    <th width="14,25%"><?=$value['products']['sku']?><!--Код товара--></th>
                                    <th width="14,25%"><?=$value['products']['title']?><!--Название товара--></th>
                                    <th width="14,25%"><?=$value['products']['price']?><!--Цена--></th>
                                    <th width="14,25%"><?=$value['products']['premium']?><!--балловая стоимость--></th>
                                    <th width="14,25%"><?=$value['users']['login']?><!--Логин--></th>
                                    <th width="14,25%"><?=$value['users']['name'].' '.$value['users']['second_name'].' '.$value['users']['middle_name']?><!--ФИО--></th>
                                </tr>
                            <?php }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>


        </div>
    </div>
</section>
<?php $this->registerJsFile('js/main/search_user_buy.js',['depends'=>['app\assets\AppAsset']]); ?>


