<?php
use yii\helpers\Html;
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();
if(empty($item['parent_parts_accessories_id'])){
    $useNumber = $item['number']*$want_number;
} else {
    $useNumber = $item['number_use'];
}

?>

<div class="form-group row">
    <div class="col-md-8">
        <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],['class'=>'form-control partTitle','disabled'=>true]);?>
    </div>

    <div class="col-md-2">
        <?=Html::input('text','',$useNumber,['class'=>'form-control needSend','disabled'=>true]);?>
    </div>
    <div class="col-md-2">
        <?=Html::input('number','reserve[]',(!empty($item['reserve']) ? $item['reserve'] : 0),[
            'class'=>'form-control partNeedReserve',
            'pattern'=>'\d*',
            'min' => '0',
            'step'=>'1',
            'disabled'=>true
        ]);?>
    </div>
</div>
