<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
    <div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('consolidated_report_for_sales') ?></h3>
    </div>
    <div class="row">

        <?php $formStatus = ActiveForm::begin([
            'action' => '/' . $language . '/business/status-sales/consolidated-report-sales',
            'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
        ]); ?>

        <div class="col-md-3 m-b">
            <?= Html::input('text','from',$dateInterval['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd'])?>
        </div>
        <div class="col-md-1 m-b text-center">
            -
        </div>
        <div class="col-md-3 m-b">
            <?= Html::input('text','to',$dateInterval['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd'])?>
        </div>
        <div class="col-md-1 m-b">
            <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="col-md-4 m-b text-right">
            <?= Html::a('Export <i class="fa fa-file-text"></i>', 'javascript:void(0);', ['class' => 'btn btn-success exportReport']) ?>
        </div>
    </div>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        №
                    </th>
                    <th>
                        <?=THelper::t('goods')?>
                    </th>
                    <th>
                        <?=THelper::t('count_goods')?>
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

    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?=THelper::t('sale_product_set')?>
                    </th>
                    <th>
                        <?=THelper::t('count_goods')?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($infoSetGoods)) {?>
                <?php foreach($infoSetGoods as $k=>$item) {?>
                <tr>
                    <td><?=$k?></td>
                    <td>
                        <?=$item?>
                    </td>
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

            document.location = "/business/status-sales/export-consolidated-report?from="+$dateFrom+"&to="+$dateTo;

        });




    </script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>