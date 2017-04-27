<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;
use app\models\PartsAccessories;

$listPartsAccessories = PartsAccessories::getListPartsAccessories();
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_interchangeable_goods') ?></h3>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/manufacturing-suppliers/add-update-interchangeable-goods'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
    </div>
</div>

<?php if(!empty($arrayInterchangeable)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-translations table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th>
                        <?=THelper::t('name_product')?>
                    </th>
                    <th>
                        <?=THelper::t('name_product')?>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($arrayInterchangeable as $item) { ?>
                    <tr>
                        <td><?=$listPartsAccessories[$item['id']]?></td>
                        <td><?=$listPartsAccessories[$item['idInterchangeable']]?></td>
                        <td>
                            <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/manufacturing-suppliers/add-update-interchangeable-goods','id'=>$item['id'],'idInterchangeable'=>$item['idInterchangeable']], ['data-toggle'=>'ajaxModal']) ?>
                        </td>
                        <td>
                            <?= Html::a('<i class="fa fa-trash-o"></i>', ['/business/manufacturing-suppliers/remove-interchangeable-goods','id'=>$item['id'],'idInterchangeable'=>$item['idInterchangeable']],['data' =>['confirm'=>'Вы действительно хотите удалить?','method'=>'post']]) ?>
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

</script>