<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Products;
use app\components\AlertWidget;

$listGoods = Products::getListGoods()
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('report_not_issued_sales') ?></h3>
</div>
<div class="row">

    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/sale-report/info-wait-sale-by-user',
        'options' => ['name' => 'selectCountry'],
    ]); ?>

    <div class="col-md-5 m-b">
        <label><?=THelper::t('country')?></label>
        <?=Html::dropDownList('countryReport',$request['countryReport'],$listCountry,[
            'class'=>'form-control',
            'id'=>'countryReport',
            'options' => [
            ]
        ])?>
    </div>
    
    <div class="col-md-5 m-b">
        <label><?=THelper::t('goods')?></label>
        <?=Html::dropDownList('goodsReport',$request['goodsReport'],$listGoods,[
            'class'=>'form-control',
            'id'=>'goodsReport',
            'options' => [
            ]
        ])?>
    </div>

    <div class="col-md-2 m-b">
        <label>&nbsp;</label>
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right">

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#by-info-user" class="tab-info-user" data-toggle="tab">По пользователям</a>
                    </li>
                    <li class="">
                        <a href="#by-info-goods" class="tab-info-goods" data-toggle="tab">По товарам</a>
                    </li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="by-info-user">
                        <?= $this->render('_tabs-wait-sale-by-users',[
                            'language'      => $language,
                            'infoSale'      => $infoSale,
                            'listCountry' => $listCountry,
                        ]); ?>
                    </div>
                    <div class="tab-pane" id="by-info-goods">
                        <?= $this->render('_tabs-wait-sale-by-goods',[
                            'language'      => $language,
                            'infoGoods'     => $infoGoods
                        ]); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

