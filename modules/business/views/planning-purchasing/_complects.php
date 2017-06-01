<?php
use yii\helpers\Html;
use app\models\PartsAccessories;
use app\models\PartsAccessoriesInWarehouse;

use app\components\ViewHelper;

$listGoods = PartsAccessories::getListPartsAccessories();

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$infoGoods = PartsAccessories::findOne(['_id'=>new \MongoDB\BSON\ObjectID((string)$infoComposite['_id'])]);



?>

<?php if(!empty($infoGoods->composite)){ ?>
    <div class="form-group row">
        <div class="col-md-3">
            <?=ViewHelper::getIndentation($level);?>
            <b><?=$listGoods[(string)$infoComposite['_id']]?></b>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-1"></div>
        <div class="col-md-1"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2"></div>
        <div class="col-md-2"></div>
    </div>
    <?php foreach($infoGoods->composite as $item){ ?>
        <?= $this->render('_complects',[
            'infoComposite'    => $item,
            'level'             => $level++
        ]); ?>
    <?php } ?>
<?php } else {?>
<div class="form-group row">
    <div class="col-md-3">
        <?=ViewHelper::getIndentation($level);?>
        <?=$listGoods[(string)$infoComposite['_id']]?>
    </div>
    <div class="col-md-1"><?=$infoComposite['number']?></div>
    <div class="col-md-1"><?=(!empty($listGoodsFromMyWarehouse[(string)$infoComposite['_id']]) ? $listGoodsFromMyWarehouse[(string)$infoComposite['_id']] : '0')?></div>
    <div class="col-md-1">***</div>
    <div class="col-md-2">***</div>
    <div class="col-md-2">
        <?=Html::input('number','buy[]',1,[
            'class'=>'form-control',
            'pattern'=>'\d*',
            'min'=>'1',
            'step'=>'1',
        ])?>
    </div>
    <div class="col-md-2">***</div>
</div>
<?php } ?>