<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;
use app\models\PartsAccessoriesInWarehouse;
use app\models\Warehouse;

$countGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$idMyWarehouse = Warehouse::getIdMyWarehouse();

?>

<?php if(!empty($idMyWarehouse)){?>
    <div class="row">
        <div class="col-md-3">
            <?= Html::a('Оприходование', ['/business/manufacturing-suppliers/posting-ordering'],['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']) ?>
        </div>
        <div class="col-md-3">
            <?= Html::a('Оприходование пред заказа', ['/business/manufacturing-suppliers/posting-pre-ordering'],['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']) ?>
        </div>
        <div class="col-md-3">
            <?= Html::a('Списание', ['/business/manufacturing-suppliers/cancellation'],['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']) ?>
        </div>
        <div class="col-md-3">
            <?= Html::a('Cборка', ['/business/manufacturing-suppliers/assembly'],['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']) ?>
        </div>
    </div>
<?php } else {?>
    <div class="alert alert-danger fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Раздел не доступен так как вы не закрепленны ни за одним складом!
    </div>
<?php } ?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_parts_accessories') ?></h3>
</div>


<?php if(!empty($idMyWarehouse)){?>

    <div class="row">
        <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

        <div class="col-md-offset-9 col-md-3 form-group">
            <?=Html::a('<i class="fa fa-plus"></i>',['/business/manufacturing-suppliers/add-update-parts-accessories'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
        </div>
    </div>

    <?php if(!empty($model)) { ?>
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>
                            <?=THelper::t('name_product')?>
                        </th>
                        <th>
                            <?=THelper::t('count')?>
                        </th>
                        <th>
                            <?=THelper::t('unit')?>
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($model as $k=>$item) { ?>
                        <tr>
                            <td><?=($k+1)?></td>
                            <td><?=$item->title?></td>
                            <td><?=(!empty($countGoodsFromMyWarehouse[$item->_id->__toString()]) ? $countGoodsFromMyWarehouse[$item->_id->__toString()] : '0');?></td>
                            <td><?=THelper::t($item->unit)?></td>
                            <td>
                                <?= Html::a('<i class="fa fa-pencil"></i>', ['/business/manufacturing-suppliers/add-update-parts-accessories','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) ?>
                            </td>
                            <td>
                                <i class="fa fa-clock-o" title="in process"></i>
    <!--                            --><?php
    //                                if(empty($item->log)){
    //                                   echo Html::a('<i class="fa fa-trash-o"></i>', ['/business/manufacturing-suppliers/remove-parts-accessories','id'=>$item->_id->__toString()],['data' =>['confirm'=>'Вы действительно хотите удалить?','method'=>'post']]);
    //                                } else {
    //                                   echo Html::a('<i class="fa fa-comment"></i>', ['/business/manufacturing-suppliers/log-parts-accessories','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']);
    //                                }
    //                            ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php } ?>

<?php } ?>
<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>

