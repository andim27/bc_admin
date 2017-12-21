<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 14.12.2015
 * Time: 15:35
 */
use app\components\THelper;
?>
<table id="datatable-t" class="table table-striped m-b-none unique_table_class tt" data-ride="datatables">
    <thead>
    <tr>
        <th width="14,25%"><?=THelper::t('purchase_date')?><!--Дата покупки--></th>
        <th width="14,25%"><?=THelper::t('product_code')?><!--Код товара--></th>
        <th width="14,25%"><?=THelper::t('product_name')?><!--Название товара--></th>
        <th width="14,25%"><?=THelper::t('price')?><!--Цена--></th>
        <th width="14,25%"><?=THelper::t('scoping_cost')?><!--балловая стоимость--></th>
        <th width="14,25%"><?=THelper::t('login')?><!--Логин--></th>
        <th width="14,25%"><?=THelper::t('full_name')?><!--ФИО--></th>
    </tr>
    </thead>
    <tbody>
    <?php if(!empty($models)){
        foreach ($models as $value){
            if(!empty($value['products']['sku'])){
                ?>
                <tr>
                    <th width="14,25%"><?=date('d-m-y H:i:s',$value['date'])?><!--Дата покупки--></th>
                    <th width="14,25%"><?=$value['products']['sku']?><!--Код товара--></th>
                    <th width="14,25%"><?=$value['products']['title']?><!--Название товара--></th>
                    <th width="14,25%"><?=$value['products']['price']?><!--Цена--></th>
                    <th width="14,25%"><?=$value['products']['premium']?><!--балловая стоимость--></th>
                    <th width="14,25%"><?=$value['users']['login']?><!--Логин--></th>
                    <th width="14,25%"><?=$value['users']['name'].' '.$value['users']['second_name'].' '.$value['users']['middle_name']?><!--ФИО--></th>
                </tr>
            <?php }
        }
    }
    ?>
    </tbody>
</table>
<script>
    $('.tt').dataTable({
        language: TRANSLATION,
        sDom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    })
</script>