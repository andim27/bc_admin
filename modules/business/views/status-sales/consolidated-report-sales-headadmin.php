<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use app\models\Users;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;

$listWarehouse = Warehouse::getListHeadAdminWarehouse();
$myInfoWarehouse = Warehouse::getInfoWarehouse();
if(!in_array((string)$myInfoWarehouse->_id,$listWarehouse)){
    $listWarehouse = ArrayHelper::merge($listWarehouse,[(string)$myInfoWarehouse->_id=>$myInfoWarehouse->title]);
}

$listAdmin = Users::getListHeadAdminAdmin();

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
            'action' => '/' . $language . '/business/status-sales/consolidated-report-sales-headadmin',
            'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
        ]); ?>

        <div class="col-md-2 m-b">
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
            <?=Html::dropDownList('listWarehouse',(!empty($request['listWarehouse']) ? $request['listWarehouse'] : 'all'),$listWarehouse,[
                'class'=>'form-control listWarehouse',
                'id'=>'listWarehouse',
                'promt'=>'Мои склады',
                'disabled' => ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? false : true),
                'style' =>  ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? '' : 'display:none'),
                'options' => [
                    //(!empty($request['listWarehouse']) ? $request['listWarehouse'] : 'all') => ['disabled' => true],
                ]
            ])?>

            <?=Html::dropDownList('listAdmin',(!empty($request['listAdmin']) ? $request['listAdmin'] : 'placeh'),$listAdmin,[
                'class'=>'form-control listAdmin',
                'id'=>'listAdmin',
                'disabled' => ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? true : false),
                'style' =>  ((!empty($request['flWarehouse']) && $request['flWarehouse']==1) ? 'display:none' : ''),
                'options' => [
                    //(!empty($request['listAdmin']) ? $request['listAdmin'] : 'placeh') => ['disabled' => true],
                ]
            ])?>
        </div>
        <div class="col-md-1 m-b">
            <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="col-md-4 m-b text-right">

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
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(!empty($infoGoods)) {?>
                                        <?php foreach($infoGoods as $k=>$item) {?>
                                        <tr>
                                            <td><?=$k?></td>
                                            <td><?=$item['title']?></td>
                                            <td>
                                                <?=$item['count']?>
                                            </td>
                                            <?php } ?>
                                            <?php } ?>
                                        </tbody>
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


        $('.btnflWarehouse').on('change',function () {
            if($(this).is(':checked')) {
                $(this).closest('.row').find('.blChangeWarehouse select[name="listWarehouse"]').prop( "disabled", false ).show();
                $(this).closest('.row').find('.blChangeWarehouse select[name="listAdmin"]').prop( "disabled", true ).hide();
            } else{
                $(this).closest('.row').find('.blChangeWarehouse select[name="listWarehouse"]').prop( "disabled", true ).hide();
                $(this).closest('.row').find('.blChangeWarehouse select[name="listAdmin"]').prop( "disabled", false ).show();
            }
        })
    </script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>