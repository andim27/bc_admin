<?php
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\LogWarehouse;

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$infoGoods = PartsAccessories::findOne(['_id'=>new \MongoDB\BSON\ObjectID($selectedGoodsId)]);
$needCount = $infoComposite['number'] * $count;

$changeableList = PartsAccessories::getInterchangeableList((string)$infoComposite['_id']);

if(!empty($changeableList)){
    $temp = array_keys($changeableList);
    $parentChangeableList = $temp['0'];
}

$priceOnePiece = LogWarehouse::getPriceOnePiece((string)$infoComposite['_id']);
$warehouseCount = (!empty($listGoodsFromMyWarehouse[$selectedGoodsId]) ? $listGoodsFromMyWarehouse[$selectedGoodsId] : '0');
$needOrder = (($warehouseCount-($needCount*$wantMake))<0 ? ($needCount*$wantMake)-$warehouseCount : '0');

?>

<span class="blockComposite">

<?php if(!empty($infoGoods->composite)){ ?>
    <div class="form-group row headPart">
        <div class="col-md-5 offset-left-<?=$level?>">
            <?=Html::dropDownList('complect[]',$selectedGoodsId,
                $changeableList,[
                    'class'     =>'form-control',
                    'required'  =>'required',
                    'data'      => [
                        'parent'    =>  $parentChangeableList,
                        'level'     =>  $level,
                        'count'     =>  $needCount
                    ],
                    'options'   => [
                    ]
                ])?>
        </div>
        <div class="col-md-1"><?=($infoComposite['number'] * $count)?></div>
        <div class="col-md-1"></div>
        <div class="col-md-1"></div>
        <div class="col-md-1"></div>
        <div class="col-md-1"></div>
        <div class="col-md-2"></div>
    </div>
    <?php $level++; ?>
    <?php foreach($infoGoods->composite as $item){ ?>
        <?= $this->render('_complects',[
            'infoComposite'     => $item,
            'level'             => $level,
            'count'             => ($infoComposite['number'] * $count)
        ]); ?>
    <?php } ?>

<?php } else { ?>
    <div class="form-group row">
    <div class="col-md-5  offset-left-<?=$level?>">

            <?=Html::dropDownList('complect[]',$selectedGoodsId,
                $changeableList,[
                    'class'     =>'form-control',
                    'required'  =>'required',
                    'data'      => [
                        'parent'    =>  $parentChangeableList,
                        'level'     =>  $level,
                        'count'     =>  $needCount
                    ],
                    'options'   => [

                    ]
                ])?>
    </div>
    <div class="col-md-1 needCountForOne"><?=$needCount?></div>
    <div class="col-md-1 warehouseCount"><?=$warehouseCount?></div>
    <div class="col-md-1 needOrdering"><?=$needOrder?></div>
    <div class="col-md-1 onceSumma">
        <?php foreach ($priceOnePiece as $k=>$item){ ?>
            <div>
                <span class="<?=$k?>">
                    <?=$item?>
                </span>
                <?=THelper::t($k)?>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-1">
        <?=Html::input('number','buy[]',$needOrder,[
            'class'=>'form-control needBuy',
            'pattern'=>'\d*',
            'min'=>'1',
            'step'=>'1',
        ])?>
    </div>
    <div class="col-md-2 allSumma">
        <?php foreach ($priceOnePiece as $k=>$item){ ?>
            <div>
                <span class="<?=$k?>">
                    <?=($item*$needOrder)?>
                </span>
                <?=THelper::t($k)?>
            </div>
        <?php } ?>
    </div>
</div>
<?php } ?>

</span>