<?php
use app\components\THelper;
?>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th></th>
                <th>
                    <?=THelper::t('full_name')?>
                </th>
                <th>
                    <?=THelper::t('country')?>
                </th>
                <th>
                    <?=THelper::t('city')?>
                </th>
                <th>
                    <?=THelper::t('address')?>
                </th>
                <th>
                    <?=THelper::t('phone')?>
                </th>
                <th>
                    <?=THelper::t('goods')?>
                </th>
                <th>
                    <?=THelper::t('status_sale')?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($infoSale)) { ?>
                <?php $i = 1; ?>
                <?php foreach($infoSale as $item) { ?>
                    <tr>
                        <td><?=$item['date_create']?></td>
                        <td><?=$item['name']?></td>
                        <td><?=$listCountry[$item['country']]?></td>
                        <td><?=$item['city']?></td>
                        <td><?=$item['address']?></td>
                        <td>
                            <?php if(!empty($item['phone'])) { ?>
                                <?php foreach($item['phone'] as $kPh => $itemPh) { ?>
                                    <?= (in_array($kPh,['0','1']) ? '<i class="fa fa-phone"></i> ' : $kPh.': ') ?>
                                    <?= $itemPh ?><br>
                                <?php } ?>
                            <?php } ?>
                        </td>
                        <td><?=$item['goods']?></td>
                        <td><?=THelper::t($item['status'])?></td>
                    </tr>
                    <?php $i++; ?>
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
        "order": [[ 0, "asc" ]]
    });

</script>
