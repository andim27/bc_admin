<?php
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;

$listGoodsWithComposite = PartsAccessories::getListPartsAccessoriesWithComposite()
?>

<div class="row">
    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/planning-purchasing/make-planning'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
    </div>
</div>

<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th><?=THelper::t('date_create');?></th>
                <th><?=THelper::t('product');?></th>
                <th><?=THelper::t('count')?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($model)) { ?>
                <?php foreach($model as $item) { ?>
                    <tr>
                        <td><?=$item->date_create->toDateTime()->format('Y-m-d H:i:s')?></td>
                        <td><?=(!empty($listGoodsWithComposite[(string)$item->parts_accessories_id]) ? $listGoodsWithComposite[(string)$item->parts_accessories_id] : '???');?></td>
                        <td><?=$item->need_collect;?></td>

                        <td>
                            <?=  Html::a('<i class="fa fa-eye text-info"></i>', ['/business/planning-purchasing/look-planning','id'=>(string)$item->_id], ['class'=>'btn btn-default','data-toggle'=>'ajaxModal']); ?>
                        </td>

                    </tr>
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
        "order": [[ 0, "desc" ]]
    });
</script>


<?php $this->registerJsFile('js/jQuery.print.js', ['depends'=>['app\assets\AppAsset']]); ?>