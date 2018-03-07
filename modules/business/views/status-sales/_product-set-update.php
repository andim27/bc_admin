<?php
use yii\helpers\Html;
use app\components\AlertWidget;
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();
?>

<?= AlertWidget::widget(['typeAlert'=>$error['type'],'message'=>$error['message']]) ?>

<?=Html::input('hidden','id',$infoProduct->_id->__toString())?>

<div class="descrItem">
    <?php if(!empty($infoProduct->set)) { ?>
        <?php foreach($infoProduct->set as $itemSet) { ?>
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
    <div class="col-md-12">
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
</div>