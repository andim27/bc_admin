<?php
use app\components\THelper;
use yii\helpers\Html;
use app\models\PartsAccessories;
use yii\helpers\ArrayHelper;

$listGoods = PartsAccessories::getListPartsAccessories();

?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_product_set') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? \app\components\AlertWidget::widget($alert) : '') ?>
</div>

<?php if(!empty($infoProduct)) { ?>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>№</th>
                <th>
                    <?=THelper::t('sale_product_name')?>
                </th>
                <th>
                    <?=THelper::t('price')?>
                </th>
                <th>
                    <?=THelper::t('sale_product_set')?>
                </th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($infoProduct as $item) { ?>

                <tr>
                    <td>
                        <?=$item->product?>
                    </td>
                    <td>
                        <?=$item->productName?>
                    </td>
                    <td>
                        <?=$item->price?>
                    </td>
                    <td class="text-center infoSet">

                        <?=Html::input('hidden','id',$item->_id->__toString())?>

                        <div class="descrItem">
                        <?php if(!empty($item->set)) { ?>
                            <?php foreach($item->set as $itemSet) { ?>
                                <div class="row">
                                    <div class="m-t-sm m-b-sm col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span>
                                            <input type="text" class="form-control input-sm" disabled="disabled" value="<?= $itemSet->setName; ?>">
                                            <input type="hidden" name="setName[]"  value="<?= $itemSet->setName ?>">
                                            <input type="hidden" name="setId[]"  value="<?= (!empty($itemSet->setId) ? $itemSet->setId : array_search($itemSet->setName,$listGoods)); ?>">
                                        </div>
                                    </div>
                                    <div class="m-t-sm m-b-sm col-md-6">
                                        <input type="number" class="form-control" name="setPrice[]" value="<?= (!empty($itemSet->setPrice) ? $itemSet->setPrice : 0); ?>"  pattern="\d*" min="0" step="0.01">
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <?=Html::dropDownList('parts_accessories_id','',
                                    $listGoods,
                                    [
                                        'class'=>'form-control listGoods',
                                        'required'=>'required',
                                        'prompt'=>'Выберите товар',
                                        'options' => [
                                            '' => ['disabled' => true]
                                        ]
                                        ])?>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0);" class="btn btn-dark btn-sm btn-icon addItemSet" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить в состав">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0);" class="btn btn-sm btn-icon saveItemSet" data-toggle="tooltip" data-placement="right" title="" data-original-title="Применить правки">
                                    <i class="fa fa-save"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <i class="fa fa-<?=((!empty($item->statusHide) && $item->statusHide==1) ? 'eye-slash' : 'eye')?>"></i>
                        <i class="fa fa-"></i>
                    </td>
                    <td>
                        <?= Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/status-sales/add-update-product-set','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) ?>
                    </td>
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

    $(document).on('click','.addItemSet',function () {
        bl = $(this).closest('.infoSet');

        goodsName = bl.find('.listGoods :selected').text();
        goodsId = bl.find('.listGoods :selected').val();

        if(goodsId == ''){
            alert('Нельзя добавить! Товар не выбран!');
            return;
        }


        bl.find('.descrItem').append(
            '<div class="row">' +
                '<div class="m-t-sm m-b-sm col-md-6">' +
                    '<div class="input-group">' +
                        '<span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span>' +
                        '<input type="text" class="form-control input-sm" value="' + goodsName + '">' +
                        '<input type="hidden" name="setName[]"  value="' + goodsName + '">' +
                        '<input type="hidden" name="setId[]" value="' + goodsId + '">' +
                    '</div>' +
                '</div>' +
                '<div class="m-t-sm m-b-sm col-md-6">' +
                    '<input type="number" class="form-control" name="setPrice[]" value="0"  pattern="\d*" min="0" step="0.01">' +
                '</div>' +
            '</div>'
        );
    });

    $(document).on('click','.removeItem',function () {
        if (confirm('Вы уверены что хотете удалить позицию?')) {
            $(this).closest('.row').remove();
        }
    });


    $(document).on('click','.saveItemSet',function () {
        if (confirm('Вы уверены что хотете применить правки?')) {

            var changeBl = $(this).closest('.infoSet');

            $.ajax({
                url: '<?=\yii\helpers\Url::to(['status-sales/product-set-save'])?>',
                type: 'POST',
                data: {
                    id : changeBl.find('input[name="id"]').val(),
                    setName : changeBl.find('input[name="setName[]"]').map(function(){
                        return this.value;
                    }).get(),
                    setId : changeBl.find('input[name="setId[]"]').map(function(){
                        return this.value;
                    }).get(),
                    setPrice : changeBl.find('input[name="setPrice[]"]').map(function(){
                        return this.value;
                    }).get(),
                },
                beforeSend: function( xhr){
                    changeBl.append('<div class="loader"><div></div></div>')
                },
                success: function (data) {
                    changeBl.html(data);
                }
            });
        }
    });

</script>
