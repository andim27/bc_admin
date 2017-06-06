<?php
use yii\helpers\Html;
use app\components\THelper;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;
use app\models\LogWarehouse;

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$infoGoods = PartsAccessories::findOne(['_id'=>new \MongoDB\BSON\ObjectID((string)$infoComposite['_id'])]);



?>

<?php if(!empty(PartsAccessories::getInterchangeableList((string)$infoComposite['_id']))) { ?>
<span class="blockComposite">
<?php } ?>


    <?php if(!empty($infoGoods->composite)){ ?>

        <div class="form-group row headPart">
        <div class="col-md-3 offset-left-<?=$level?>">
            
            <?php if(!empty(PartsAccessories::getInterchangeableList((string)$infoComposite['_id']))) { ?>
                <?=Html::dropDownList('complect[]','',
                    PartsAccessories::getInterchangeableList((string)$infoComposite['_id']),[
                        'class'=>'form-control',
                        'required'=>'required',
                        'options' => [
                        ]
                    ])?>

            <?php } else {?>
                <?=Html::hiddenInput('complect[]',(string)$infoComposite['_id'],[]);?>
                <?=Html::input('text','',$listGoods[(string)$infoComposite['_id']],['class'=>'form-control','disabled'=>'disabled']);?>
            <?php } ?>

        </div>
        <div class="col-md-1"><?=($infoComposite['number'] * $count)?></div>
        <div class="col-md-1"></div>
        <div class="col-md-1"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2"></div>
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
        <?php
        $priceOnePiece = LogWarehouse::getPriceOnePiece((string)$infoComposite['_id']);
        $needCount = $infoComposite['number'] * $count;
        $warehouseCount = (!empty($listGoodsFromMyWarehouse[(string)$infoComposite['_id']]) ? $listGoodsFromMyWarehouse[(string)$infoComposite['_id']] : '0');
        $needOrder = (($warehouseCount-$needCount)<0 ? $needCount-$warehouseCount : '0');
        ?>
        <div class="form-group row">
    <div class="col-md-3  offset-left-<?=$level?>">
        
        <?php if(!empty(PartsAccessories::getInterchangeableList((string)$infoComposite['_id']))) { ?>
            <?=Html::dropDownList('complect[]','',
                PartsAccessories::getInterchangeableList((string)$infoComposite['_id']),[
                    'class'=>'form-control',
                    'required'=>'required',
                    'options' => [
                    ]
                ])?>

        <?php } else {?>
            <?=Html::hiddenInput('complect[]',(string)$infoComposite['_id'],[]);?>
            <?=Html::input('text','',$listGoods[(string)$infoComposite['_id']],['class'=>'form-control','disabled'=>'disabled']);?>
        <?php } ?>

    </div>
    <div class="col-md-1"><?=$needCount?></div>
    <div class="col-md-1"><?=$warehouseCount?></div>
    <div class="col-md-1"><?=$needOrder?></div>
    <div class="col-md-2">
        <?php foreach ($priceOnePiece as $k=>$item){ ?>
            <div><?=$item?> <?=THelper::t($k)?></div>
        <?php } ?>
    </div>
    <div class="col-md-2">
        <?=Html::input('number','buy[]',$needOrder,[
            'class'=>'form-control',
            'pattern'=>'\d*',
            'min'=>'1',
            'step'=>'1',
        ])?>
    </div>
    <div class="col-md-2">
        <?php foreach ($priceOnePiece as $k=>$item){ ?>
            <div><?=($item*$needCount)?> <?=THelper::t($k)?></div>
        <?php } ?>
    </div>
</div>
    <?php } ?>

    <?php if(!empty(PartsAccessories::getInterchangeableList((string)$infoComposite['_id']))) { ?>
    </span>
<?php } ?>
