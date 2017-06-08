<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Products;

$listGoods = Products::getListGoods()
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('report_not_issued_sales') ?></h3>
</div>
<div class="row">

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
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th></th>
                        <th>
                            <?=THelper::t('full_name')?>
                        </th>
                        <th>
                            <?=THelper::t('country')?>
                        </th>
                        <th>
                            <?=THelper::t('city')?>
                        </th>
                        <th>
                            <?=THelper::t('address')?>
                        </th>
                        <th>
                            <?=THelper::t('phone')?>
                        </th>
                        <th>
                            <?=THelper::t('goods')?>
                        </th>
                        <th>
                            <?=THelper::t('status_sale')?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($infoSale)) { ?>
                            <?php $i = 1; ?>
                            <?php foreach($infoSale as $item) { ?>
                                <tr>
                                    <td><?=$item['date_create']?></td>
                                    <td><?=$item['name']?></td>
                                    <td><?=$listCountry[$item['country']]?></td>
                                    <td><?=$item['city']?></td>
                                    <td><?=$item['address']?></td>
                                    <td>
                                        <?php if(!empty($item['phone'])) { ?>
                                            <?php foreach($item['phone'] as $kPh => $itemPh) { ?>
                                                <?= (in_array($kPh,['0','1']) ? '<i class="fa fa-phone"></i> ' : $kPh.': ') ?>
                                                <?= $itemPh ?><br>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td><?=$item['goods']?></td>
                                    <td><?=THelper::t($item['status'])?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </section>
    </div>
</div>


<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "asc" ]]
    });

</script>
