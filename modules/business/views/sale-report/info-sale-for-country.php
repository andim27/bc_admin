<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Settings;

use app\models\Products;

$listCountry = Settings::getListCountry();


$listPack = Products::getListPack();
$listGoods = Products::getListGoods();
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('report_sale_for_country') ?></h3>
</div>
<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/sale-report/info-sale-for-country',
        'options' => ['name' => 'selectGoods',],
    ]); ?>

    <div class="col-md-1">
        <label class="control-label switch-center"></label>
        <label class="switch">
            <input value="1" class="btnflGoods" type="checkbox" name="flGoods" <?= ((!empty($request['flGoods']) && $request['flGoods']==1) ? 'checked="checked"' : '')?>/>
            <span></span>
        </label>
    </div>

    <div class="col-md-2 m-b blChangeGoods">
        <?=Html::dropDownList('listPack',(!empty($request['listPack']) ? $request['listPack'] : 'all'),$listPack,[
            'class'=>'form-control listPack',
            'id'=>'listPack',
            'disabled' => ((!empty($request['flGoods']) && $request['flGoods']==1) ? false : true),
            'style' =>  ((!empty($request['flGoods']) && $request['flGoods']==1) ? '' : 'display:none'),
            'options' => []
        ])?>

        <?=Html::dropDownList('listGoods',(!empty($request['listGoods']) ? $request['listGoods'] : 'all'),$listGoods,[
            'class'=>'form-control listGoods',
            'id'=>'listGoods',
            'disabled' => ((!empty($request['flGoods']) && $request['flGoods']==1) ? true : false),
            'style' =>  ((!empty($request['flGoods']) && $request['flGoods']==1) ? 'display:none' : ''),
            'options' => []
        ])?>
    </div>


    <div class="col-md-1 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success']) ?>
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
                        <th>
                            <?=THelper::t('country')?>
                        </th>
                        <th>
                            <?=((!empty($request['flGoods']) && $request['flGoods']==1) ? THelper::t('business_product') : THelper::t('goods'))?>
                        </th>
                        <th>
                            <?=THelper::t('number_all')?>
                        </th>
                        <th>
                            <?=THelper::t('number_issue')?>
                        </th>
                        <th>
                            <?=THelper::t('number_wait')?>
                        </th>
                        <th>
                            <?=THelper::t('number_repair')?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($infoSale)) { ?>
                            <?php foreach($infoSale as $k=>$itemGoods) { ?>
                                <?php foreach($itemGoods as $kGoods=>$item) { ?>
                                    <tr>
                                        <td><?=$listCountry[$k]?></>
                                        <td><?=$kGoods?></td>
                                        <td><?=$item['all']?></td>
                                        <td><?=$item['issued']?></td>
                                        <td><?=$item['wait']?></td>
                                        <td><?=$item['repair']?></td>
                                    </tr>
                                <?php } ?>
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
        "order": [[ 0, "desc" ]]
    });


    $('.btnflGoods').on('change',function () {
        if($(this).is(':checked')) {
            $(this).closest('.row').find('.blChangeGoods select[name="listPack"]').prop( "disabled", false ).show();
            $(this).closest('.row').find('.blChangeGoods select[name="listGoods"]').prop( "disabled", true ).hide();
        } else{
            $(this).closest('.row').find('.blChangeGoods select[name="listPack"]').prop( "disabled", true ).hide();
            $(this).closest('.row').find('.blChangeGoods select[name="listGoods"]').prop( "disabled", false ).show();
        }
    })
</script>
