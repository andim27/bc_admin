<?php
    use app\components\THelper;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_product_set') ?></h3>
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
                    <?=THelper::t('sale_product_set')?>
                </th>
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
                    <td class="text-center infoSet">

                        <?=Html::input('hidden','id',$item->_id->__toString())?>

                        <div class="row">
                            <div class="descrItem col-md-12">
                                <?php if(!empty($item->set)) { ?>
                                    <?php foreach($item->set as $itemSet) { ?>
                                        <div class="input-group m-t-sm m-b-sm blItem">
                                            <span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span>
                                            <input type="text" class="form-control input-sm" name="setName[]" placeholder="Входит в состав" value="<?= $itemSet->setName; ?>">
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                <a href="javascript:void(0);" class="btn btn-dark btn-sm btn-icon addItemSet" data-toggle="tooltip" data-placement="right" title="" data-original-title="Добавить в состав">
                                    <i class="fa fa-plus"></i>
                                </a>

                                <a href="javascript:void(0);" class="btn btn-sm btn-icon saveItemSet" data-toggle="tooltip" data-placement="right" title="" data-original-title="Применить правки">
                                    <i class="fa fa-save"></i>
                                </a>

                            </div>
                        </div>
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
        $(this).closest('.infoSet').find('.descrItem').append(
            '<div class="input-group m-t-sm m-b-sm blItem">'+
                '<span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span>'+
                '<input type="text" class="form-control input-sm" name="setName[]" placeholder="Входит в состав">'+
            '</div>'
        );
    });

    $(document).on('click','.removeItem',function () {
        if (confirm('Вы уверены что хотете удалить позицию?')) {
            $(this).closest('.blItem').remove();
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
