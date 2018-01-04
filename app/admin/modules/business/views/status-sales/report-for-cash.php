<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Warehouse;
use yii\helpers\ArrayHelper;

$listWarehouse = Warehouse::getArrayWarehouse();


?>
    <div class="m-b-md">
        <h3 class="m-b-none"><?= THelper::t('report_for_cash') ?></h3>
    </div>

<?php $formStatus = ActiveForm::begin([
    'action' => '/' . $language . '/business/status-sales/report-for-cash',
    'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
]); ?>
    <div class="form-group row">

        <div class="col-md-3 m-b">
            <?=Html::dropDownList('infoTypeDate', $request['infoTypeDate'],
                [
                    'create'=>'Дата создания',
                    //'update'=>'Дата изменениня'
                ],
                [
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

        <div class="col-md-3 m-b">
            <?=Html::dropDownList('infoWarehouse', $request['infoWarehouse'],
                ArrayHelper::merge(['all'=>THelper::t('all_warehouse')],$listWarehouse),[
                    'class'=>'form-control infoUser',
                    'id'=>'infoWarehouse',
                ])?>
        </div>


        <div class="col-md-1 m-b col-md-offset-2">
            <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success btn-block']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

    <div class="row">
        <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#by-goods" class="tab-by-goods" data-toggle="tab"><?= THelper::t('goods') ?></a>
                    </li>
                    <li class="">
                        <a href="#by-warehouse" class="tab-by-warehouse" data-toggle="tab"><?= THelper::t('warehouse') ?></a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">

                        <?= $this->render('_report-for-cash-goods',[
                            'infoGoods'        => $infoGoods,
                        ]); ?>
                        <?= $this->render('_report-for-cash-warehouse',[
                            'infoWarehouse'     => $infoWarehouse
                        ]); ?>
                </div>
            </div>
        </section>
    </div>

    <script>
        $('.table-translations').dataTable({
            language: TRANSLATION,
            lengthMenu: [ 25, 50, 75, 100 ],
            "order": [[ 0, "desc" ]]
        });

    </script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>