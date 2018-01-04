<?php
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();
?>


<?php if(!empty($modelCancellation)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>Дата</th>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Кто списал</th>
                    <th>Причина</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($modelCancellation as $k=>$item) { ?>
                    <tr>
                        <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=$listGoods[(string)$item->parts_accessories_id]?></td>
                        <td><?=$item->number?></td>
                        <td><?=$item->adminInfo->secondName . ' ' .$item->adminInfo->firstName?></td>
                        <td><?=$item->comment?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>
