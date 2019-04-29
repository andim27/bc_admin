<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Warehouse;

$countGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$idMyWarehouse = Warehouse::getIdMyWarehouse();

?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_returns_performers') ?></h3>
</div>

<?php if(!empty($idMyWarehouse)){?>

    <div class="row">
        <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
        <div class="col-md-4 form-group">
            <select id="p-id" name="p-id" class="form-control">

                <option value="0"  <?=(isset($p_id)&&$p_id==0?'selected':'')  ?> >Все исполнители</option>
                <?php foreach ($performer_items as $item) { ?>
                    <?php if ($item['p_id'] == $p_id) { ?>
                            <option value="<?=$item['p_id'] ?>"  selected   ><?=$item['name'] ?></option>
                        <?php } else { ?>
                            <option value="<?=$item['p_id'] ?>"     ><?=$item['name'] ?></option>
                <?php  }} ?>

            </select>
        </div>

        <div class="col-md-offset-6 col-md-2 form-group">
            <?=Html::a('<i class="fa fa-plus"></i>',['/business/manufacturing-suppliers/add-return-from-performer'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
        </div>
    </div>

    <?php if(!empty($model)) { ?>
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th>
                            <?=THelper::t('date_create')?>
                        </th>
                        <th><?=THelper::t('sidebar_suppliers_performers')?></th>
                        <th>
                            <?=THelper::t('name_product')?>
                        </th>
                        <th>
                            <?=THelper::t('count')?>
                        </th>
                        <th>
                            <?=THelper::t('comments')?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($model as $k=>$item) { ?>
                        <tr>
                            <td><?=@$item['date_create']?></td>
                            <td><?=@$item['performer_info']->title?></td>
                            <td><?=@$item['part_info']->title?></td>
                            <td><?=@$item['number']?></td>
                            <td><?=@$item['comment']?></td>

                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </section>
    <?php } else {?>
        <section class="panel panel-default">
            <h4 class="text-center text-danger">Ничего нет</h4>
        </section>
    <?php } ?>
<?php } ?>


<script>
$('#p-id').click(function(){
    window.location.href = '/' + LANG + '/business/manufacturing-suppliers/returns-performers?p_id='+$('#p-id').val();
});
$('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>