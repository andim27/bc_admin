<?php
use yii\helpers\ArrayHelper;

use app\components\THelper;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$listWarehouse = \app\models\Warehouse::getArrayWarehouse();
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('log') ?></h3>
</div>


<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/status-sales/report-sales-admins',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-3">
        <div class="input-group">
            <?= Html::input('text','from',$request['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm-dd'])?>
            <span class="input-group-addon"> - </span>
            <?= Html::input('text','to',$request['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm-dd'])?>
        </div>
    </div>

    <div class="col-md-2 m-b">
        <?=Html::dropDownList('infoWarehouse', $request['infoWarehouse'],
            ArrayHelper::merge([''=>THelper::t('all_warehouse')],$listWarehouse),[
                'class'=>'form-control infoUser',
                'id'=>'infoWarehouse',
            ])?>
    </div>

    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right"></div>
</div>


<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Действие</th>
                    <th>Кто проводил</th>
                    <th>Склад</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Коментарий</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $k=>$item) { ?>
                    <tr>
                        <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=$item->action?></td>
                        <td><?=$item->adminInfo->secondName . ' ' .$item->adminInfo->firstName?></td>
                        <td>????</td>
                        <td><?=$item->number?></td>
                        <td><?=(!empty($item->money) ? $item->money . ' EUR' : '')?></td>
                        <td><?=$item->comment?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>



    <script>
        $('.table-translations').dataTable({
            language: TRANSLATION,
            lengthMenu: [ 25, 50, 75, 100 ],
            "order": [[ 0, "desc" ]]
        });
    </script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>