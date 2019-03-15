<?php
use app\models\PartsAccessories;
use app\components\THelper;

$listGoods = PartsAccessories::getListPartsAccessories();
?>


<?php if(!empty($modelPosting)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Кто оприходовал</th>
                    <th>Метод</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($modelPosting as $k=>$item) { ?>
                    <tr>
                        <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td title="<?=$item->comment ?>"><?=(isset($item->part_virt)&&($item->part_virt ==1) ? '(вирт)':'').$listGoods[(string)$item->parts_accessories_id]?></td>
                        <td><?=$item->number?></td>
                        <td><?=$item->adminInfo->secondName . ' ' .$item->adminInfo->firstName?></td>
                        <td><?=THelper::t($item->action)?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>
