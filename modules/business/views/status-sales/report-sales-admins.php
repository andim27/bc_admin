<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('report_for_sales') ?></h3>
</div>
<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/status-sales/report-sales-admins',
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
    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right"></div>
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

</script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>