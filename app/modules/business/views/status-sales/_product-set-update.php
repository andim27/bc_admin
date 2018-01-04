<?php
use yii\helpers\Html;
use app\components\AlertWidget;
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();
?>

<?= AlertWidget::widget(['typeAlert'=>$error['type'],'message'=>$error['message']]) ?>

<?=Html::input('hidden','id',$infoProduct->_id->__toString())?>

<div class="row">
    <div class="descrItem col-md-12">
        <?php if(!empty($infoProduct->set)) { ?>
            <?php foreach($infoProduct->set as $itemSet) { ?>
                <div class="input-group m-t-sm m-b-sm blItem">
                    <span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span>
                    <input type="text" class="form-control input-sm" disabled="disabled" value="<?= $itemSet->setName; ?>">
                    <input type="hidden" name="setName[]"  value="<?= $itemSet->setName ?>">
                    <input type="hidden" name="setId[]"  value="<?= (!empty($itemSet->setId) ? $itemSet->setId : array_search($itemSet->setName,$listGoods)); ?>">
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