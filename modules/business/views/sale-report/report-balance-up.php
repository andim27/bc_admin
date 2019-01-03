<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.01.19
 * Time: 12:50
 */

use app\components\THelper;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\bootstrap\Html;


function balanceMesssage($item_str,$item) {
    $res_str = $item_str;
    if (!isset($item)) {
        $res_str += ' ?';
    } else {
        try {
            $parts = explode(';',$item);
            if ($item_str =='kind') {
                $res_str =  str_replace('kind:','',$parts[1]);
            }
            if ($item_str =='comment') {
                $res_str =  str_replace('comment:','',$parts[2]);
            }

        } catch (\Exception $e) {
            $res_str ='??';
        }

    }
    return $res_str;
}
?>

<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_report_balance_up') ?></h3>
</div>
<div class="row">

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/sale-report/report-balance-up',
        'options' => ['name' => 'selectFilters'],
    ]); ?>

    <div class="col-md-5 m-b">
        <?= DatePicker::widget([
            'name' => 'from',
            'value' => $dateFrom,
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'to',
            'value2' =>$dateTo,
            'separator' => '-',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]); ?>
    </div>
    <div class="col-md-2 m-b">

    </div>
    <div class="col-md-2 m-b">

    </div>


    <div class="col-md-3 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-success btn-block']) ?>
    </div>



    <?php ActiveForm::end(); ?>

    <div class="col-md-4 m-b text-right">

    </div>
</div>


<section class="panel panel-default">
    <div class="table-responsive">
        <!--            <pre>-->
        <!--                --><?//=var_dump($infoSale) ?>
        <!--            </pre>-->
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr role="row">
                <th><?=THelper::t('date_create')?></th>
                <th><?=THelper::t('username')?></th>
                <th><?=THelper::t('sum')?></th>
                <th><?=THelper::t('kind')?></th>
                <th width="25%"><?=THelper::t('comments')?></th>
            </tr>
            </thead>

            <?php if(!empty($infoSale)) { ?>
            <tbody>
                <?php
                   $totalSum=0;
                   foreach($infoSale as $item) {
                       $totalSum+=$item['price'];
                 ?>
                    <tr  role="row">
                        <td><?=$item['dateCreate']->toDateTime()->format('Y-m-d H:i:s');?></td>
                        <td><?=$item['username']?></td>
                        <td><?=$item['price']?></td>
                        <td><?=balanceMesssage('kind',$item['whenceSale']);?></td>
                        <td width="25%"><?=balanceMesssage('comment',$item['whenceSale']);?></td>
                    </tr>
                <?php } ?>
            </tbody>
            </tfooter>
                <tr>
                    <th colspan="2" class="text-right">Итого:</th>
                    <th><?=$totalSum;?></th>
                </tr>
            </tfooter>

            <?php } ?>

        </table>
    </div>

</section>



<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
// "order": [[ 0, "asc" ]]
</script>