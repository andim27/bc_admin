<?php
use yii\bootstrap\Html;

use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\ExecutionPosting;

$listGoods = PartsAccessories::getListPartsAccessories();
$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$partContractor = ExecutionPosting::getPresenceInPerformer((string)$item['parts_accessories_id'],$performerId);
?>


<div class="form-group row blUnique">
    <div class="col-md-7">
        <?=Html::hiddenInput('complect[]',(string)$item['parts_accessories_id'],[]);?>
        <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],[
            'class'             => 'form-control partTitle',
            'disabled'          => true,
            'data-placement'    => 'left',
            'title'             => $listGoods[(string)$item['parts_accessories_id']]
        ]);?>
    </div>
    <div class="col-md-1">
        <?=Html::input('text',
            '',
            (!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? ($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) : 0 ),
            ['class'=>'form-control inWarehouse','disabled'=>'disabled']);?>
    </div>

    <div class="col-md-1">
        <?=Html::input('text','',
            ($partContractor+$item['cancellation_performer']),
            ['class'=>'form-control partContractor','disabled'=>'disabled']); ?>
    </div>

    <div class="col-md-1">
        <?=Html::hiddenInput('number[]',$item['number'],[]);?>
        <?=Html::input('text','',$item['number'],['class'=>'form-control partNeedForOne','disabled'=>'disabled']);?>
    </div>
    <div class="col-md-1">
        <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? ($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']] + ($item['number']*$want_number)) : 0 ),['class'=>'numberWarehouse']);?>
        <?=Html::input('text','',($item['number']*$want_number),['class'=>'form-control needSend','disabled'=>'disabled']);?>
    </div>
    <div class="col-md-1">
        <?=Html::input('number','reserve[]',(!empty($item['reserve']) ? $item['reserve'] : 0),[
            'class'=>'form-control partNeedReserve',
            'pattern'=>'\d*',
            'step'=>'1',
        ]);?>
    </div>
</div>
