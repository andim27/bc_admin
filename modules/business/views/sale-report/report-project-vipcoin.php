<?php
use app\components\THelper;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use yii\bootstrap\Html;

?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_report_project_vipcoin') ?></h3>
</div>
<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/sale-report/report-project-vipcoin',
        'options' => ['name' => 'selectCountry'],
    ]); ?>

    <div class="col-md-5 m-b">
        <?= DatePicker::widget([
            'name' => 'from',
            'value' => $request['from'],
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'to',
            'value2' => $request['to'],
            'separator' => '-',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm',
                'startView'=>'year',
                'minViewMode'=>'months',
            ]
        ]); ?>    
    </div>
    <div class="col-md-2 m-b">
        <?=Select2::widget([
            'name' => 'countryReport',
            'value' => (!empty($request['countryReport']) ? $request['countryReport'] : ''),
            'data' => $listCountry,
            'options' => [
                'class'=>'form-control',
                'placeholder' => 'Выберите страну',
                'multiple' => false
            ]
        ]);?>        
    </div>
    <div class="col-md-2 m-b">
        <?=Select2::widget([
            'name' => 'cityReport',
            'value' => (!empty($request['cityReport']) ? $request['cityReport'] : ''),
            'data' => $listCity,
            'options' => [
                'class'=>'form-control',
                'placeholder' => 'Выберите город',
                'multiple' => true,
                'disabled' => (!empty($request['countryReport']) ? false : true)
            ]
        ]);?>
    </div>


    <div class="col-md-3 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right">

    </div>
</div>


<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th><?=THelper::t('date_create')?></th>
                <th><?=THelper::t('country')?></th>
                <th><?=THelper::t('city')?></th>
                <th><?=THelper::t('address')?></th>
                <th><?=THelper::t('full_name')?></th>
                <th><?=THelper::t('phone')?></th>
                <th><?=THelper::t('goods')?></th>
                <th><?=THelper::t('price')?></th>
            </tr>
            </thead>

            <?php if(!empty($infoSale)) { ?>
            <tbody>
                <?php foreach($infoSale as $item) { ?>
                    <tr>
                        <td><?=$item['dateCreate'];?></td>
                        <td><?=$item['userCountry']?></td>
                        <td><?=$item['userCity']?></td>
                        <td><?=$item['userAddress']?></td>
                        <td><?=$item['userName']?></td>
                        <td><?=$item['userPhone']?></td>
                        <td><?=$item['productName']?></td>
                        <td><?=$item['productPrice']?></td>
                    </tr>
                <?php } ?>
            </tbody>
            </tfooter>
                <tr>
                    <th colspan="7" class="text-right">Итого:</th>
                    <th><?=$totatPrice?></th>
                </tr>
            </tfooter>

            <?php } ?>

        </table>
    </div>

</section>



<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "asc" ]]
    });

</script>