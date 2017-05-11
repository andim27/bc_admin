<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;


    $from = strtotime($request['from']);
    $to = strtotime($request['to']);
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('report_for_sales') ?></h3>
</div>
<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/status-sales/report-sales',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>
    <div class="col-md-2 m-b">
        <?=Html::dropDownList('infoTypeDate', $request['infoTypeDate'],
            ['create'=>'Дата создания','update'=>'Дата изменениня'],[
                'class'=>'form-control infoTypeDate',
                'id'=>'infoTypeDate',
            ])?>
    </div>
    <div class="col-md-2 m-b">
        <?= Html::input('text','from',$request['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd'])?>
    </div>
    <div class="col-md-1 m-b text-center">
       -
    </div>
    <div class="col-md-2 m-b">
        <?= Html::input('text','to',$request['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd'])?>
    </div>
    <div class="col-md-2 m-b">
        <?=Html::dropDownList('infoWarehouse', $request['infoWarehouse'],
            \app\models\Warehouse::getMyWarehouse(),[
            'class'=>'form-control infoUser',
            'id'=>'infoWarehouse',
        ])?>
    </div>
    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-2 m-b text-right">
        <?= Html::a('Export <i class="fa fa-file-text"></i>', 'javascript:void(0);', ['class' => 'btn btn-success exportReport']) ?>
    </div>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
                <tr>
                    <th>
                        <?=THelper::t('date')?>
                    </th>
                    <th>
                        <?=THelper::t('full_name')?>
                    </th>
                    <th>
                        <?=THelper::t('login')?>
                    </th>
                    <th>
                        <?=THelper::t('goods')?>
                    </th>
                    <th>
                        <?=THelper::t('status_sale')?>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($model)) {?>
                    <?php foreach($model as $item) {?>
                        <?php if (!empty($item->statusSale) && count($item->statusSale->set)>0 && $item->statusSale->checkSalesForUserChange($listAdmin)!==false) {?>
                        <tr>
                            <td><?=$item->dateCreate->toDateTime()->format('Y-m-d H:i:s')?></td>
                            <td><?=$item->infoUser->secondName?> <?=$item->infoUser->firstName?></td>
                            <td><?=$item->username?></td>
                            <td><?=$item->productName?></td>
                            <td>
                                <table>
                                    <?php foreach ($item->statusSale->set as $itemSet) {?>
                                        <?php $dateChange = strtotime($itemSet->dateChange->toDateTime()->format('Y-m-d')) ?>
                                        <?php
                                            $show = 0;
                                            if($request['infoTypeDate'] == 'update') {
                                                if($dateChange>=$from && $dateChange<=$to) {
                                                    $show = 1;
                                                }
                                            } else {
                                                $show = 1;
                                            }
                                        ?>

                                        <?php if($show = 1) {?>
                                        <tr data-set="<?= $itemSet->title ?>">
                                            <td>
                                                <?= $itemSet->title ?>
                                            </td>
                                            <td>
                                                <span class="label label-default statusOrder">
                                                    <?= THelper::t($itemSet->status) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= $itemSet->dateChange->toDateTime()->format('Y-m-d H:i:s') ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </table>
                            </td>
                            <td>
                                <?= Html::a('<i class="fa fa-comment"></i>', ['/business/status-sales/look-comment','idSale'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) ?>
                            </td>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

    $('.exportReport').on('click',function () {
        $dateFrom = $('.dateFrom').val();
        $dateTo = $('.dateTo').val();
        $infoUser = $('.infoUser').prop('selected',true).val();
        $infoTypeDate = $('.infoTypeDate').prop('selected',true).val();

        document.location = "/business/status-sales/export-report?from="+$dateFrom+"&to="+$dateTo+"&infoUser="+$infoUser+"&infoTypeDate="+$infoTypeDate;

    });




</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>