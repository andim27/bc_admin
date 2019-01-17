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


function balanceMesssage($item_str,$item) { //--for sales?not for pre
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

function statusIcon($status_str) {
    $res_str ='<i class="fa fa-spinner fa-spin" style="font-size:24px" title="wait..."></i>';
    if (isset($status_str)) {
        if ($status_str == 'done') {
            $res_str =' <span class="glyphicon glyphicon-ok" title="done"></span>';
        }
        if ($status_str == 'cancel') {
            $res_str =' <span class="glyphicon glyphicon-remove" title="canceled">';
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
<!--    <pre>-->
<!--           --><?//=($p_key) ?>
<!--    </pre>-->
    <div class="table-responsive">

        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr role="row">
                <th><?=THelper::t('date_create')?></th>
                <th><?=THelper::t('username')?></th>
                <th><?=THelper::t('sum')?></th>
                <th><?=THelper::t('kind')?></th>
                <th width="25%"><?=THelper::t('comments')?></th>
                <th><?=THelper::t('status')?></th>
                <?php
                if (isset($p_key)) {
                ?>
                <th><?=THelper::t('controls')?></th>
                <?php } ?>
            </tr>
            </thead>

            <?php if(!empty($infoSale)) { ?>
            <tbody>
                <?php
                   $totalSum=0;
                   foreach($infoSale as $item) {
                       $totalSum+=$item['amount'];//--amount for pre - price for sale
                 ?>
                    <tr  role="row">
                        <td><?=$item['created_at']->toDateTime()->format('Y-m-d H:i:s');?></td>
                        <td><?=$item['username']?></td>
                        <td><?=$item['amount']?></td>
                        <td><?=$item['kind'] ?? '?'//balanceMesssage('kind',$item['whenceSale']);?></td>
                        <td width="25%"><?= $item['comment'] ?? '...'//balanceMesssage('comment',$item['whenceSale']);?></td>
                        <td id="status-<?=$item['_id'] ?>"><?=statusIcon(($item['status'] ?? ''));?></td>

                        <?php
                             if (isset($p_key)) {
                        ?>
                        <td>
                         <button id="btn-apply-<?=$item['_id'] ?>" type="button" class="btn btn-success btn-sm" onclick="applyBalance('<?=$item['_id'] ?>')">Apply</button>
                         <button id="btn-cancel-<?=$item['_id'] ?>" type="button" class="btn btn-danger  btn-sm" onclick="cancelBalance('<?=$item['_id'] ?>')">Cancel</button>
                        </td>

                        <?php } ?>
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
<?php
if (isset($p_key)) {
?>
<script>
    function applyBalance(id) {
        //alert(id);
        sendActionBalance('done',id);
    }
    function cancelBalance(id) {
        //alert(id);
        sendActionBalance('cancel',id);
    }
    function changeStatusBalance(data) {
        console.log(data);
        if ((data.id !=undefined)&&(data.status_html !=undefined)){
            $('#status-'+data.id).html(data.status_html);
        }
    }
    function sendActionBalance(action,id) {
        var url = "/<?=Yii::$app->language?>/business/user/balance-action";
        var data = {'id':id,'action':action,'type':'adminka'}
        $.post(url,data).done(function (data) {
            if (data.success == true) {
                changeStatusBalance(data);
                if (data.action == 'cancel') {
                    $('#btn-apply-'+data.id).attr('disabled',true);
                }
                if (data.action == 'done') {
                    $('#btn-cancel-'+data.id).attr('disabled',true);
                }
            } else {
                alert('Change balance error:\n'+data.mes);
            }
        });
    }
</script>
<?php } ?>


<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
// "order": [[ 0, "asc" ]]
</script>