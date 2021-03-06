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
        <div class="col-md-4">
            <?= Html::a('Оприходование', ['/business/manufacturing-suppliers/posting-ordering'],['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']) ?>
        </div>
        <div class="col-md-4">
            <?= Html::a('Оприходование предзаказа', ['/business/manufacturing-suppliers/posting-pre-ordering'],['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']) ?>
        </div>
        <div class="col-md-4">
            <?= Html::a('Списание', ['/business/manufacturing-suppliers/cancellation'],['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']) ?>
        </div>
<!--        <div class="col-md-3">-->
            <?php // Html::a('Cборка', ['/business/manufacturing-suppliers/assembly'],['data-toggle'=>'ajaxModal','class'=>'btn btn-default btn-block']); ?>
<!--        </div>-->
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
        <div class="col-md-2 form-group">
           <select id="f-id" name="f" class="form-control">
               <option value="0"  <?=(isset($f)&&$f==0?'selected':'')  ?> >Все</option>
               <option value="1"  <?=(!isset($f)||$f==1?'selected':'')  ?> >Рабочие</option>
               <option value="-1" <?=(isset($f)&&$f==-1?'selected':'') ?> >Архивные</option>
           </select>
        </div>
        <div class="col-md-offset-4 col-md-3 form-group">
            <?=Html::a('<i class="fa fa-file-o"></i>',['/business/manufacturing-suppliers/parts-accessories-excel'],['class'=>'btn btn-default btn-block'])?>
        </div>
        <div class="col-md-3 form-group">
            <?=Html::a('<i class="fa fa-plus"></i>',['/business/manufacturing-suppliers/add-update-parts-accessories'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
        </div>
    </div>

    <?php if(!empty($model)) { ?>
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th><?=THelper::t('article')?></th>
                        <th>
                            <?=THelper::t('name_product')?>
                        </th>
                        <th>
                            <?=THelper::t('count')?>
                        </th>
                        <th>
                            <?=THelper::t('unit')?>
                        </th>
                        <th>
                            <?=THelper::t('price_for_one_pcs')?>
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($model as $k=>$item) { ?>
                        <tr>
                            <td><?=$item->article?></td>
                            <td><?=$item->title?></td>
                            <td><?=(!empty($countGoodsFromMyWarehouse[$item->_id->__toString()]) ? $countGoodsFromMyWarehouse[$item->_id->__toString()] : '0');?></td>
                            <td><?=THelper::t($item->unit)?></td>
                            <td><?=$item->last_price_eur?></td>
                            <td class="status-planning-<?=(!empty($arrayProcurementPlanning[(string)$item->_id]) ? $arrayProcurementPlanning[(string)$item->_id] : '')?>">
                                <?=(!empty($arrayProcurementPlanning[(string)$item->_id]) ? THelper::t('status-planning-'.$arrayProcurementPlanning[(string)$item->_id]) : '')?>
<!--                                <i class="fa fa-dot-circle-o"></i>-->
                            </td>
                            <td>
                                <?php if(!empty($item->delivery_from_chine) && $item->delivery_from_chine==1){ ?>
                                <i class="fa fa-truck" data-toggle="tooltip" data-placement="top" title="Доставляется из Китая"></i>
                                <?php } ?>
                                <?php if(!empty($item->repair_fund) && $item->repair_fund==1){ ?>
                                    <i class="fa fa-wrench" data-toggle="tooltip" data-placement="top" title="Ремонтный фонд"></i>
                                <?php } ?>
                                <?php if(!empty($item->exchange_fund) && $item->exchange_fund==1){ ?>
                                    <i class="fa fa-retweet" data-toggle="tooltip" data-placement="top" title="Обменный фонд"></i>
                                <?php } ?>
                            </td>
                            <td>
                                <?php //if(!in_array($item->_id->__toString(),['5975afe2dca78748ce5e7e02','59620f57dca78747631d3c62','59620f49dca78761ae2d01c1'])) { ?>
                                <?= Html::a('<i class="fa fa-pencil" title="редактировать"></i>', ['/business/manufacturing-suppliers/add-update-parts-accessories','id'=>$item->_id->__toString()], ['data-toggle'=>'ajaxModal']) ?>
                                <?php //} ?>
                            </td>
                            <td>
                                <?=Html::a('<i class="fa fa-clock-o" title="история"></i>',['/business/manufacturing-suppliers/log-parts-accessories','id'=>$item->_id->__toString()]) ?>

                                <?= ($item->checkTransaction() ?
                                    '' :
                                    Html::a('<i class="fa fa-trash-o" title="удалить"></i>', ['/business/manufacturing-suppliers/remove-parts-accessories','id'=>$item->_id->__toString()],['data' =>['confirm'=>'Вы действительно хотите удалить?','method'=>'post']])) ?>

                            </td>
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
    $('#f-id').change(function(){
       window.location.href = '/' + LANG + '/business/manufacturing-suppliers/parts-accessories?f='+$('#f-id').val();
    });
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>

