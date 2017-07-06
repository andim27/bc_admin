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

    <div class="col-md-3">
        <div class="input-group">
            <?= Html::input('text','from',$request['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd'])?>
            <span class="input-group-addon"> - </span>
            <?= Html::input('text','to',$request['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd'])?>
        </div>
    </div>

    <div class="col-md-2 m-b">
        <?=Html::dropDownList('infoWarehouse', $request['infoWarehouse'],
            \app\models\Warehouse::getMyWarehouse(),[
            'class'=>'form-control infoUser',
            'id'=>'infoWarehouse',
        ])?>
    </div>

    <div class="col-md-2 m-b">
        <?=Html::dropDownList('infoStatus', $request['infoStatus'],
            ['all'=>THelper::t('all_status'),'status_sale_new'=>THelper::t('status_sale_new'),'status_sale_issued'=>THelper::t('status_sale_issued')],[
                'class'=>'form-control infoCity',
                'id'=>'infoCity',
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
        <?php if($request['infoTypeDate'] == 'create') { ?>
            <?= $this->render('_report-sales-admins-datecreate',[
                'model'     => $model,
                'listAdmin' => $listAdmin,
                'request'   => $request
            ]); ?>
        <?php } else { ?>
            <?= $this->render('_report-sales-admins-datechange',[
                'model'     => $model,
                'listAdmin' => $listAdmin,
                'request'   => $request
            ]); ?>
        <?php } ?>

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