<?php
use yii\bootstrap\Html;
use app\components\THelper;
use app\models\Users;

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
                    <?=THelper::t('warehouse')?>
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
                        <td>
                            <?=(!empty($listCountry[$item['countryWarehouse']]) ? $listCountry[$item['countryWarehouse']] . ', ': 'none') ?>
                        </td>
                        <td>
                            <?=(!empty($item['nameWarehouse']) ? $item['nameWarehouse'] . ', ': 'none') ?>

                            <?= (Users::checkRule('edit','sidebar_sale_wait') === true ?
                                Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/sale-report/change-warehouse','_id'=>$item['_id'],'k'=>$item['key']], ['data-toggle'=>'ajaxModal']) :
                                ''); ?>

                        </td>
                        <td>
                            <?=(!empty($listCountry[$item['country']]) ? $listCountry[$item['country']] .', ' : '') ?>
                            <?=(!empty($item['city']) ? $item['city'] .', ' : '') ?>
                            <?=(!empty($item['address']) ? $item['address'] : '') ?>
                        </td>
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
