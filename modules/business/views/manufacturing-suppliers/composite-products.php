<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;
use app\models\PartsAccessories;

$listPartsAccessories = PartsAccessories::getListPartsAccessories();
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_composite_products') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
    <div class="col-md-offset-6 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-file-o"></i>',['/business/manufacturing-suppliers/composite-products-excel'],['class'=>'btn btn-default btn-block','title'=>'Экспорт в excel'])?>
    </div>
    <div class="col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/manufacturing-suppliers/add-update-composite-products'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal','title'=>'Добавить комплектацию'])?>
    </div>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?=THelper::t('name_product')?>
                    </th>
                    <th>
                        <?=THelper::t('composition')?>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $item) { ?>
                    <?php if(!empty($item->composite) && count($item->composite)>0){ ?>

                        <tr>
                            <td><?=$listPartsAccessories[(string)$item->_id]?></td>
                            <td>
                                <div class="shortItem150">
                                    <?php foreach ($item->composite as $itemComposite) { ?>

                                        <?=(!empty($listPartsAccessories[(string)$itemComposite['_id']]) ?
                                            $listPartsAccessories[(string)$itemComposite['_id']] . ' - '.$itemComposite['number'].' '.THelper::t($itemComposite['unit']).'<br>' :
                                            '????<br>')
                                        ?>

                                    <?php } ?>                                    
                                </div>
                                <span class="showMore"><?=THelper::t('more')?></span>
                            </td>
                            <td>
                                <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/manufacturing-suppliers/add-update-composite-products','id'=>(string)$item->_id], ['data-toggle'=>'ajaxModal']) ?>
                            </td>
                        </tr>
                    <?php } ?>
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

    $('.table-translations').on('click','.showMore',function () {
        $(this).closest("td").find(".shortItem150").css({'height':'100%'});
        $(this).hide();
    })

</script>