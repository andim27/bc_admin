<?php
use app\components\THelper;
use yii\bootstrap\Html;
use app\models\PartsAccessories;

use yii\helpers\ArrayHelper;

$listGoodsRepair = PartsAccessories::getListProductRepair();
$listGoodsExchange = PartsAccessories::getListProductExchange()

?>

<?php if(!empty($userInfo)){ ?>
    <?=Html::hiddenInput('infoUser[id]',(string)$userInfo['_id'])?>
    <?=Html::hiddenInput('infoUser[username]',$userInfo['username'])?>

    <div class="row form-group">
        <div class="col-md-12">
            <?=Html::label(THelper::t('status'))?>
            <?=Html::dropDownList('status','',[
                    'status_sale_repairs_under_warranty'=>THelper::t('status_sale_repairs_under_warranty'),
                    'status_sale_repair_without_warranty'=>THelper::t('status_sale_repair_without_warranty'),
                ],[
                    'class'=>'form-control',
                    'required'=>'required',
                    'promt' => THelper::t('choose_good')
                ])?>
        </div>
    </div>


    <div class="row form-group">
        <div class="col-md-12">
            <?=Html::label(THelper::t('list_goods_for_repair'))?>
            <?=Html::dropDownList('idGoodsRepair','',$listGoodsRepair,[
                'class'=>'form-control',
                'required'=>'required',
                'promt' => THelper::t('choose_good')
            ])?>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-12">
            <?= Html::label(THelper::t('issued_exchange_fund'))?>
            <?=Html::dropDownList('idGoodsExchange','',ArrayHelper::merge([''=>THelper::t('not_used')],$listGoodsExchange),[
                'class'=>'form-control',
                'promt' => THelper::t('choose_good')
            ])?>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-4 pull-right">
            <?= Html::submitButton(THelper::t('send_for_repair'), ['class' => 'btn btn-success btn-block']) ?>
        </div>
    </div>

<?php } else { ?>
    <div class="row form-group">
        <div class="col-md-12">
            <p class="bg-danger attentionMakeOrder"><?=THelper::t('this_user_is_absent_database')?></p>
        </div>
    </div>
<?php } ?>
