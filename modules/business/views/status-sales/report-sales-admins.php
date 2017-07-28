<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('report_for_sales') ?></h3>
</div>

<?php $formStatus = ActiveForm::begin([
    'action' => '/' . $language . '/business/status-sales/report-sales-admins',
    'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
]); ?>
    <div class="form-group row">

        <div class="col-md-3 m-b">
            <?=Html::dropDownList('infoTypeDate', $request['infoTypeDate'],
                ['create'=>'Дата создания','update'=>'Дата изменениня'],[
                    'class'=>'form-control infoTypeDate',
                    'id'=>'infoTypeDate',
                ])?>
        </div>

        <div class="col-md-3 m-b">
            <?=Html::dropDownList('infoWarehouse', $request['infoWarehouse'],
                \app\models\Users::getListAdmin(),[
                'class'=>'form-control infoUser',
                'id'=>'infoWarehouse',
            ])?>
        </div>
        <div class="col-md-2 m-b">
            <?=Html::dropDownList('infoCity', (!empty($request['infoCity']) ? $request['infoCity'] : 'all'),
                $listCity,[
                'class'=>'form-control infoCity',
                'id'=>'infoCity',
            ])?>
        </div>

        <div class="col-md-2 m-b">
            <?=Html::dropDownList('infoStatus', $request['infoStatus'],
                ['all'=>THelper::t('all_status'),'status_sale_new'=>THelper::t('status_sale_new'),'status_sale_issued'=>THelper::t('status_sale_issued')],[
                    'class'=>'form-control infoCity',
                    'id'=>'infoCity',
                ])?>
        </div>

        <div class="col-md-2 m-b">
            <?=Html::dropDownList('infoTypePayment', $request['infoTypePayment'],
                ['all'=>THelper::t('all_type_payment'),'paid_in_company'=>THelper::t('paid_in_company'),'paid_in_cash'=>THelper::t('paid_in_cash')],[
                    'class'=>'form-control',
                    'id'=>'infoTypePayment',
                ])?>
        </div>


    </div>
    <div class="form-group row">
        <div class="col-md-3">
            <div class="input-group">
                <?= Html::input('text','from',$request['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd'])?>
                <span class="input-group-addon"> - </span>
                <?= Html::input('text','to',$request['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd'])?>
            </div>
        </div>

        <div class="col-md-offset-8 col-md-1 m-b">
            <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success btn-block']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>


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

</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>