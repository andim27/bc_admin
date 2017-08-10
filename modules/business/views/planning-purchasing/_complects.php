<?php
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\LogWarehouse;

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$infoGoods = PartsAccessories::findOne(['_id'=>new \MongoDB\BSON\ObjectID((string)$infoComposite['_id'])]);

$needCount = $infoComposite['number'] * $count;

$changeableList = PartsAccessories::getInterchangeableList((string)$infoComposite['_id']);

if(!empty($changeableList)){
    $temp = array_keys($changeableList);
    $parentChangeableList = $temp['0'];
}

$priceOnePiece = LogWarehouse::getPriceOnePiece((string)$infoComposite['_id']);
$warehouseCount = (!empty($listGoodsFromMyWarehouse[(string)$infoComposite['_id']]) ? $listGoodsFromMyWarehouse[(string)$infoComposite['_id']] : '0');
$needOrder = (($warehouseCount-$needCount)<0 ? $needCount-$warehouseCount : '0');

?>

<?php if(!empty($changeableList)) { ?>
    <span class="blockComposite">
<?php } ?>


<?php if(!empty($infoGoods->composite)){ ?>



    <div class="form-group row headPart">
        <div class="col-md-5 offset-left-<?=$level?>">
            <?php if(!empty($changeableList)){ ?>
                <?=Html::dropDownList('complect[]','',
                    $changeableList,[
                        'class'     =>'form-control partTitle',
                        'required'  =>'required',
                        'data'      => [
                            'parent'    =>  $parentChangeableList,
                            'level'     =>  $level,
                            'count'     =>  $needCount
                        ],
                        'options'   => [
                        ]
                    ])?>

            <?php } else {?>
                <?=Html::hiddenInput('complect[]',(string)$infoComposite['_id'],[]);?>
                <?=Html::input('text','',$listGoods[(string)$infoComposite['_id']],['class'=>'form-control partTitle','disabled'=>'disabled']);?>
            <?php } ?>

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
        
        <?php if(!empty($changeableList)) { ?>
            <?=Html::dropDownList('complect[]','',
                $changeableList,[
                    'class'     =>'form-control partTitle',
                    'required'  =>'required',
                    'data'      => [
                        'parent'    =>  $parentChangeableList,
                        'level'     =>  $level,
                        'count'     =>  $needCount
                    ],
                    'options'   => [
                    ]
                ])?>

        <?php } else {?>
            <?=Html::hiddenInput('complect[]',(string)$infoComposite['_id'],[]);?>
            <?=Html::input('text','',$listGoods[(string)$infoComposite['_id']],['class'=>'form-control partTitle','disabled'=>'disabled']);?>
        <?php } ?>

    </div>
    <div class="col-md-1 needCountForOne"><?=$needCount?></div>
    <div class="col-md-1 warehouseCount"><?=$warehouseCount?></div>
    <div class="col-md-1 needOrdering"><?=$needOrder?></div>
    <div class="col-md-1 onceSumma">
        <?php foreach ($priceOnePiece as $k=>$item){ ?>
            <div>
                <span class="<?=$k?>">
                    <?=round($item,3)?>
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

<?php if(!empty($changeableList)) { ?>
    </span>
<?php } ?>
