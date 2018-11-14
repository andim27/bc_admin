<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use app\models\Users;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;

$listWarehouse = Warehouse::getArrayWarehouse();
$listAdmin = Users::getListAdmin();

$amountPack = [
    'count'     =>  0,
    'amount'    =>  0,
];
$amountRestPack = [
    'count'     =>  0,
    'amount'    =>  0,
];
$layoutDate = <<< HTML
    {input1}
    {separator}
    {input2}
HTML;

?>
    <div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('consolidated_report_for_sales') ?></h3>
    </div>
    <div class="row">

        <?php $formStatus = ActiveForm::begin([
            'action' => '/' . $language . '/business/status-sales/consolidated-report-sales',
            'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
        ]); ?>

        <div class="col-md-6 m-b">
            <?= DatePicker::widget([
                'name' => 'from',
                'value' => $dateInterval['from'],
                'type' => DatePicker::TYPE_RANGE,
                'name2' => 'to',
                'value2' => $dateInterval['to'],
                'separator' => '-',
                'layout' => $layoutDate,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ]
            ]); ?>
        </div>

        <div class="col-md-1">
            <label class="control-label switch-center"></label>
            <label class="switch">
                <input value="1" class="btnflWarehouse" type="checkbox" name="flWarehouse" <?= ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? 'checked="checked"' : '')?>/>
                <span></span>
            </label>
        </div>

        <div class="col-md-2 m-b blChangeWarehouse">
            <div style="<?=((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? '' : 'display:none')?>">
                <?= Select2::widget([
                    'name' => 'listWarehouse',
                    'data' => $listWarehouse,
                    'value' => (!empty($request['listWarehouse']) ? $request['listWarehouse'] : 'all'),
                    'options' => [
                        'class'=>'listWarehouse',
                        'placeholder' => 'Выберите действия',
                        'disabled' => ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? false : true)
                    ]
                ]);
                ?>
            </div>

            <div style="<?=((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? 'display:none' : '');?>">
                <?= Select2::widget([
                    'name' => 'listAdmin',
                    'data' => $listAdmin,
                    'value' => (!empty($request['listAdmin']) ? $request['listAdmin'] : 'placeh'),
                    'options' => [
                        'class'=>'listAdmin',
                        'placeholder' => 'Выберите действия',
                        'disabled' => ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? true : false)
                    ]
                ]);
                ?>
            </div>



<!--            --><?php //=Html::dropDownList('listWarehouse',(!empty($request['listWarehouse']) ? $request['listWarehouse'] : 'all'),$listWarehouse,[
//                'class'=>'form-control listWarehouse',
//                'id'=>'listWarehouse',
//                'disabled' => ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? false : true),
//                'style' =>  ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? '' : 'display:none'),
//                'options' => [
//                    //(!empty($request['listWarehouse']) ? $request['listWarehouse'] : 'all') => ['disabled' => true],
//                ]
//            ]);?>

<!--            --><?php //=Html::dropDownList('listAdmin',(!empty($request['listAdmin']) ? $request['listAdmin'] : 'placeh'),$listAdmin,[
//                'class'=>'form-control listAdmin',
//                'id'=>'listAdmin',
//                'disabled' => ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? true : false),
//                'style' =>  ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? 'display:none' : ''),
//                'options' => [
//                    //(!empty($request['listAdmin']) ? $request['listAdmin'] : 'placeh') => ['disabled' => true],
//                ]
//            ]);?>
        </div>
        <div class="col-md-1 m-b">
            <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="col-md-1 m-b text-right">
            <?= Html::a('Export <i class="fa fa-file-text"></i>', 'javascript:void(0);', ['class' => 'btn btn-success exportReport']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <section class="panel panel-default">
                <header class="panel-heading bg-light">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active">
                            <a href="#by-set" class="tab-by-set" data-toggle="tab"><?= THelper::t('goods') ?></a>
                        </li>
                        <li class="">
                            <a href="#by-goods" class="tab-by-goods" data-toggle="tab"><?= THelper::t('business_product') ?></a>
                        </li>
                        <li class="">
                            <a href="#by-rest" class="tab-by-goods" data-toggle="tab"><?= THelper::t('rest') ?></a>
                        </li>
                    </ul>
                </header>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="by-set">
                            <section class="panel panel-default">
                                <div class="table-responsive">
                                    <table class="table table-translations table-striped datagrid m-b-sm">
                                        <thead>
                                        <tr>
                                            <th>
                                                <?=THelper::t('goods')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('number_booked')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('number_issue')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('number_difference')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('current_balance')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('in_way')?>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($infoSetGoods)) {?>
                                            <?php foreach($infoSetGoods as $k=>$item) {?>
                                                <tr>
                                                    <td><?=$k?></td>
                                                    <td>
                                                        <?=$item['books']?>
                                                    </td>
                                                    <td>
                                                        <?=$item['issue']?>
                                                    </td>
                                                    <td>
                                                        <?=($item['books'] - $item['issue'])?>
                                                    </td>
                                                    <td><?=$item['current_balance']?></td>
                                                    <td><?=$item['in_way']?></td>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                        <div class="tab-pane" id="by-goods">
                            <section class="panel panel-default">
                                <div class="table-responsive">
                                    <table class="table table-translations table-striped datagrid m-b-sm">
                                        <thead>
                                        <tr>
                                            <th>
                                                №
                                            </th>
                                            <th>
                                                <?=THelper::t('business_product')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('number_booked')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('amount')?>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($infoGoods)) {?>
                                            <?php foreach($infoGoods as $k=>$item) {?>
                                            <?php
                                                $amountPack['count'] += $item['count'];
                                                $amountPack['amount'] += $item['amount'];
                                            ?>
                                            <tr>
                                                <td><?=$k?></td>
                                                <td><?=$item['title']?></td>
                                                <td>
                                                    <?=$item['count']?>
                                                <td>
                                                    <?=$item['amount']?>
                                            </td>
                                            <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                        <tfooter>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th><?=$amountPack['count']?></th>
                                                <th><?=$amountPack['amount']?></th>
                                            </tr>
                                        </tfooter>
                                    </table>
                                </div>
                            </section>
                        </div>
                        <div class="tab-pane" id="by-rest">
                            <section class="panel panel-default">
                                <div class="table-responsive">
                                    <table class="table table-translations table-striped datagrid m-b-sm">
                                        <thead>
                                        <tr>
                                            <th>
                                                №
                                            </th>
                                            <th>
                                                <?=THelper::t('sale_product_name')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('user_title')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('number_booked')?>
                                            </th>
                                            <th>
                                                <?=THelper::t('amount')?>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($infoRestGoods)) {?>
                                        <?php foreach($infoRestGoods as $k=>$item) {?>
                                                <?php
                                                $amountRestPack['count'] += $item['count'];
                                                $amountRestPack['amount'] += $item['amount'];
                                                ?>
                                        <tr>
                                            <td><?=$k?></td>
                                            <td><?=$item['title'] ?? '??'?></td>
                                            <td><?=$item['username'] ?? '??'?></td>
                                            <td>
                                                <?=$item['count']?>
                                            <td>
                                                <?=$item['amount']?>
                                            </td>
                                            <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                        <tfooter>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th><?=$amountRestPack['count']?></th>
                                                <th><?=$amountRestPack['amount']?></th>
                                            </tr>
                                        </tfooter>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <script>
        $('.table-translations').dataTable({
            language: TRANSLATION,
            lengthMenu: [ 25, 50, 75, 100 ],
            "order": [[ 0, "desc" ]]
        });

        $('.exportReport').on('click',function () {
            $dateFrom = $('input[name="from"]').val();
            $dateTo = $('input[name="to"]').val();


            if($('.btnflWarehouse').is(':checked')){
                $flWarehouse = 1;
                $listWarehouse = $('.listWarehouse').val();
                $listAdmin = '';
            } else {
                $flWarehouse = 0;
                $listWarehouse = '';
                $listAdmin = $('.listAdmin').val();
            }

            document.location = "/business/status-sales/export-consolidated-report?from="+$dateFrom+"&to="+$dateTo+"&flWarehouse="+$flWarehouse+"&listWarehouse="+$listWarehouse+"&listAdmin="+$listAdmin;

        });

        $('.btnflWarehouse').on('change',function () {
            if($(this).is(':checked')) {
                $(this).closest('.row').find('.blChangeWarehouse select[name="listWarehouse"]').prop( "disabled", false ).closest('div').show();
                $(this).closest('.row').find('.blChangeWarehouse select[name="listAdmin"]').prop( "disabled", true ).closest('div').hide();
            } else{
                $(this).closest('.row').find('.blChangeWarehouse select[name="listWarehouse"]').prop( "disabled", true ).closest('div').hide();
                $(this).closest('.row').find('.blChangeWarehouse select[name="listAdmin"]').prop( "disabled", false ).closest('div').show();
            }
        })
    </script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>