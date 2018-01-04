<?php
use yii\helpers\ArrayHelper;

use app\components\THelper;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use app\models\Warehouse;
use app\models\LogWarehouse;

$listWarehouse = Warehouse::getArrayWarehouse();

?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('log') ?></h3>
</div>


<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/log-warehouse/move-on-warehouse-for-month',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-3">
        <div class="input-group">
            <?= Html::input('text','from',$request['from'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm'])?>
            <span class="input-group-addon"> - </span>
            <?= Html::input('text','to',$request['to'],['class' => 'form-control datepicker-input dateTo', 'data-date-format'=>'yyyy-mm'])?>
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

<?php if($infoProduct){ ?>
    <table class="table">
        <thead>
        <tr>
            <th>Товар</th>
            <th>Выдан</th>
            <th>Оприходован</th>
            <th>Отправлен</th>
            <th>Списан</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($infoProduct as $k=>$itemProduct) { ?>
            <tr>
                <td colspan="5" class="bg-warning text-center"><?=$k?></td>
            </tr>
            <?php foreach ($itemProduct as $item) { ?>
                <tr>
                    <td><?=$item['title']?></td>
                    <td><?=$item['issued']?></td>
                    <td><?=$item['posting']?></td>
                    <td><?=$item['send']?></td>
                    <td><?=$item['cancellation']?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>

        <?php if(!empty($infoProductAmount)){ ?>
        <tfooter>
            <tr>
                <th colspan="5" class="bg-success">Итого</th>
            </tr>
            <?php foreach ($infoProductAmount as $item) { ?>
                <tr>
                    <td><?=$item['title']?></td>
                    <td><?=$item['issued']?></td>
                    <td><?=$item['posting']?></td>
                    <td><?=$item['send']?></td>
                    <td><?=$item['cancellation']?></td>
                </tr>
            <?php } ?>
        </tfooter>
        <?php } ?>
    </table>

<?php } ?>

<?php if($actionDontKnow){ ?>
    <div class="bg-danger">
        <h3>Не учтенные статусы:</h3>
        <ul>
        <?php foreach ( $actionDontKnow  as $k=>$item) { ?>
            <li><?=$item?> (<?=$k?>)</li>
        <?php } ?>
        </ul>
    </div>
<?php } ?>


    <script>
        $('.table-translations').dataTable({
            language: TRANSLATION,
            lengthMenu: [ 25, 50, 75, 100 ],
            "order": [[ 0, "desc" ]]
        });


    </script>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>