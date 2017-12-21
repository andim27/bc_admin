<?php
use yii\helpers\Html;
use app\components\THelper;
?>

<section class="panel panel-default">
    <header class="panel-heading bg-light">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#shares_buy" data-toggle="tab"><?=THelper::t('The shares for the purchase of the product (goods)')?><!--Акции за покупку продукта (товара)--></a></li>
            <li><a href="#shares_step" data-toggle="tab"><?=THelper::t('shares_of_steps')?><!--Акции за шаги--></a></li>
            <li><a href="#shares_status" data-toggle="tab"><?=THelper::t('shares_of_status')?></a></li>
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content">

            <div class="tab-pane active conteiner_tab" id="shares_buy" data-url="shares-buy">
                <section class="panel panel-default">
                    <div class="table-responsive">
                        <table id="datatable-t" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                            <thead>
                            <tr>
                                <th width="12%"><?=THelper::t('product_code')?><!--Код товара--></th>
                                <th width="12%"><?=THelper::t('product_name')?><!--Название товара--></th>
                                <th width="12%"><?=THelper::t('number_of_preferred_shares_in_a_personal_purchase')?><!--Количество привилегированных акций при личной покупке--></th>
                                <th width="12%"><?=THelper::t('number_of_ordinary_shares_at_personal_purchase')?><!--Количество обычных акций при личной покупке--></th>
                                <th width="12%"><?=THelper::t('potential_ ordinary_shares_price')?><!--Потенциальная цена обычной акции--></th>
                                <th width="12%"><?=THelper::t('the_potential_price_of_preference_shares')?><!--Потенциальная цена привилегированной акции--></th>
                                <th width="12%"><?=THelper::t('the_number_of_preferred_shares_of_the_sponsor')?><!--Количество привилегированных акции спонсору--></th>
                                <th width="12%"><?=THelper::t('number_of_ordinary_shares_of_the_sponsor')?><!--Количество обычных акций спонсору--></th>
                                <th width="4%" class="sort"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['model_buy'])){foreach ($data['model_buy'] as $key => $value){ ?>
                                <tr>
                                    <th width="12%"><?=$value['product_id'];?></th>
                                    <th width="12%"><?=$value['product_title'];?></th>
                                    <th width="12%"><?=$value['NP_buying'];?></th>
                                    <th width="12%"><?=$value['NO_buying'];?></th>
                                    <th width="12%"><?=$value['PO_price'];?></th>
                                    <th width="12%"><?=$value['PP_shares'];?></th>
                                    <th width="12%"><?=$value['NPS_sponsor'];?></th>
                                    <th width="12%"><?=$value['NOS_sponsor'];?></th>
                                    <th width="4%">
                                        <?= Html::a('<i class="fa fa-pencil"></i>', ['shares-buy', 'id'=>$value['id']], array('data-toggle'=>'ajaxModal')); ?>
                                    </th>
                                </tr>
                            <?php }
                            }
                            ?>
                            </tbody>
                        </table>
                        <?= Html::a(THelper::t('add'), ['add-buy'], array('class'=>'btn btn-s-md btn-danger pull-right r','data-toggle'=>'ajaxModal')); ?>
                    </div>
                </section>
            </div>

            <div class="tab-pane conteiner_tab" data-url="shares-step" id="shares_step">
                <section class="panel panel-default">
                    <div class="table-responsive">
                        <table id="datatable-t1" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                            <thead>
                            <tr>
                                <th width="33%"><?=THelper::t('product_code')?><!--Код товара--></th>
                                <th width="33%"><?=THelper::t('product_name')?><!--Название товара--></th>
                                <th width="33%" class="sort"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['model_step'])){foreach ($data['model_step'] as $key => $value){ ?>
                                <tr>
                                    <th width="33%"><?=$value['product_id'];?></th>
                                    <th width="33%"><?=$value['product_title'];?></th>
                                    <th width="33%">
                                        <?= Html::a('<i class="fa fa-pencil"></i>', ['shares-step', 'id'=>$value['id']], array('data-toggle'=>'ajaxModal')); ?>
                                    </th>
                                </tr>
                            <?php }
                            }
                            ?>
                            </tbody>
                        </table>
                        <?= Html::a(THelper::t('add'), ['add-step'], array('class'=>'btn btn-s-md btn-danger pull-right r','data-toggle'=>'ajaxModal')); ?>
                    </div>
                </section>
            </div>

            <div class="tab-pane conteiner_tab" data-url="shares-status" id="shares_status">
                <section class="panel panel-default">
                    <div class="table-responsive">
                        <table id="datatable-t2" class="table table-striped m-b-none unique_table_class" data-ride="datatables">
                            <thead>
                            <tr>
                                <th width="20%"><?=THelper::t('status_name')?><!--Название статуса--></th>
                                <th width="20%"><?=THelper::t('status_code')?><!--Код статуса--></th>
                                <th width="20%"><?=THelper::t('the_number_of_preferred_shares_when_the_status_of')?><!--Количество привилегированных акций при достижении статуса--></th>
                                <th width="20%"><?=THelper::t('the_number_of_ordinary_shares_in_achieving_the_status_of')?><!--Количество обычных акций при достижении статуса--></th>
                                <th width="20%" class="sort"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($data['model_status'])){
                                foreach ($data['model_status'] as $key => $value){ ?>
                                <tr>
                                    <th width="20%"><?=$value['carrier_title'];?></th>
                                    <th width="20%"><?=$value['carrier_id'];?></th>
                                    <th width="20%"><?=$value['NP_status'];?></th>
                                    <th width="20%"><?=$value['NO_status'];?></th>
                                    <th width="20%">
                                        <?= Html::a('<i class="fa fa-pencil"></i>', ['shares-status', 'id'=>$value['id']], array('data-toggle'=>'ajaxModal')); ?>
                                    </th>
                                </tr>
                            <?php }
                            }
                            ?>
                            </tbody>
                        </table>
                        <?= Html::a(THelper::t('add'), ['add-status'], array('class'=>'btn btn-s-md btn-danger pull-right','data-toggle'=>'ajaxModal')); ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<?php $this->registerJsFile('js/main/change_shares.js',['depends'=>['app\assets\AppAsset']]); ?>


