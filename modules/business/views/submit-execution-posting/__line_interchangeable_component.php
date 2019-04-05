<?php
use yii\bootstrap\Html;

use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\ExecutionPosting;

$listGoods = PartsAccessories::getListPartsAccessories();
$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$interchangeableList = PartsAccessories::getInterchangeableList($k);

$temp = [
    'listGoodsFromMyWarehouse'  =>  0,
    'partContractor'            =>  0,
    'partNeedReserve'           =>  0,
    'number_use'           =>  0,
];
?>

<div class="panel panel-default blInterchangeable">
    <div class="panel-body">
        <div class="infoDangerExecution"></div>
        <div class="form-group row">
            <div class="col-md-12 blTitleInterchangeable">
                <?= implode(" / ",$interchangeableList)?>
            </div>
        </div>
        <?php foreach ($items as $kL => $item){?>
            <?php
                $partContractor = ExecutionPosting::getPresenceInPerformer((string)$item['parts_accessories_id'],$performerId);

                $reserve = (!empty($item['reserve']) ? $item['reserve'] : 0);

                $temp['listGoodsFromMyWarehouse'] += (!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? $listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']] : 0);
                $temp['partContractor'] += $partContractor;
                $temp['partNeedReserve'] += $reserve;
                $temp['number_use'] += $item['number_use'];
            ?>

            <div class="form-group row">
                <div class="col-md-6">
                    <?=Html::hiddenInput('complectInterchangeable['.(string)$item['parent_parts_accessories_id'].'][]',(string)$item['parts_accessories_id'],[]);?>
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
                        (!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? $listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']] : 0 ),
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
                    <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? ($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']] + $item['number_use']) : 0 ),['class'=>'numberWarehouse']);?>
                    <?=Html::input('text','',$item['number_use'],['class'=>'form-control needSend','disabled'=>'disabled']);?>
                </div>
                <div class="col-md-1">
                    <?=Html::input('number','reserve[]',$reserve,[
                        'class'=>'form-control partNeedReserve',
                        'pattern'=>'\d*',
                        'step'=>'1',
                    ]);?>
                </div>
                <div class="col-md-1">
                    <?=Html::input('number','numberNoneComplect['.(string)$item['parts_accessories_id'].']',0,[
                        'class'=>'form-control partNoneComplect',
                        'pattern'=>'\d*',
                        'step'=>'1',
                        'title' => 'Не комплект(_interch-items)'
                    ]);?>
                </div>
            </div>
        <?php } ?>

        <div class="form-group row totalInterchangeable">
            <div class="col-md-6"></div>
            <div class="col-md-1">
                <?=Html::input('text',
                    '',
                    $temp['listGoodsFromMyWarehouse'],
                    ['class'=>'form-control inWarehouse','disabled'=>'disabled']);?>
            </div>
            <div class="col-md-1">
                <?=Html::input('text','',
                    $temp['partContractor'],
                    ['class'=>'form-control partContractor','disabled'=>'disabled']); ?>
            </div>
            <div class="col-md-1">
                <?=Html::input('text','',$item['number'],['class'=>'form-control partNeedForOne','disabled'=>'disabled']);?>
            </div>
            <div class="col-md-1">
                <?=Html::input('text','',$temp['number_use'],['class'=>'form-control needSend','disabled'=>'disabled']);?>
            </div>
            <div class="col-md-1">
                <?=Html::input('text','',$temp['partNeedReserve'],[
                    'class'=>'form-control partNeedReserve',
                    'disabled'=>'disabled'
                ]);?>
            </div>
            <div class="col-md-1">
                <?=Html::input('number','numberNoneComplect['.(string)$item['parts_accessories_id'].']',0,[
                    'class'=>'form-control partNoneComplect',
                    'pattern'=>'\d*',
                    'step'=>'1',
                    'title' => 'Не комплект(_interch-bottom)'
                ]);?>
            </div>
        </div>

    </div>
</div>

