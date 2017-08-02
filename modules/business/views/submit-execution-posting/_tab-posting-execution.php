<?php
use app\components\THelper;
use yii\helpers\Html;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;

$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();
?>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>Дата добавдения</th>
                <th>Что собираем</th>
                <th><?=THelper::t('count')?></th>
                <th>Дата прихода</th>
                <th>Кто собирает</th>
                <th>Кому переданно</th>
                <th>Статус</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $item) { ?>
                <tr>
                    <td><?= $item->date_create->toDateTime()->format('Y-m-d H:i:s') ?></td>
                    <td><?= $listGoods[(string)$item->parts_accessories_id]?></td>
                    <td><?= $item->number ?></td>
                    <td><?= (!empty($item->date_execution) ? $item->date_execution->toDateTime()->format('Y-m-d H:i:s') : '') ?></td>
                    <td><?= $listSuppliers[(string)$item->suppliers_performers_id] ?></td>
                    <td><?= $item->fullname_whom_transferred ?></td>
                    <td>
                        <?php if($item->posting != 1){?>
                            Осталось <?=($item->number - $item->received)?>
                            <?=Html::a('<i class="fa fa-edit"></i>', ['/business/submit-execution-posting/posting-execution','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal'])?>
                        <?php } else { ?>
                            <?php
                                if($item->number == $item->received) {
                                    $titleL = 'Выполнен';
                                    $classL = 'text-info';
                                } else if ($item->received == 0) {
                                    $titleL = 'Расформировано';
                                    $classL = 'text-danger';
                                } else {
                                    $titleL = 'Выполнен частично';
                                    $classL = 'text-warning';
                                }
                            ?>

                            <?= Html::a($titleL, ['/business/submit-execution-posting/look-posting-execution','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal','class'=>$classL])?>

                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>
