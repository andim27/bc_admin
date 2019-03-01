<?php
use app\components\THelper;
use yii\helpers\Html;
use app\components\AlertWidget;

$total = [
    'loan'          =>  0,
    'repayment'     =>  0,
    'difference'    =>  0,
];
?>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>
</div>

<div class="m-b-md">
    <h3 class="m-b-none" title="<?=$loan_date ?>"><?= THelper::t('sidebar_loan') ?></h3>
</div>

<div class="row form-group">
    <div class="col-md-3">
        <select  class="form-control" id="loan-filter-id" name="loan-filter">
            <option <?=$f==0?'selected':'' ?> value="0">Долг равен 0</option>
            <option <?=(!isset($f)||$f==1)?'selected':'' ?> value="1">Долг больше 0</option>
            <option <?=$f==1000?'selected':'' ?> value="1000">Долг больше 0 (пересчет)</option>
        </select>
    </div>
    <div class="col-md-2 col-md-offset-7">
        <?= Html::a('<i class="fa fa-usd"></i> '.THelper::t('sent_loan'), ['/business/loan/sent-repayment'], ['data-toggle'=>'ajaxModal','class'=>'btn btn-block btn-success']) ?>
    </div>
</div>


<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th><?=THelper::t('user')?></th>
                <th><?=THelper::t('loan')?></th>
                <th><?=THelper::t('repayment_for_loan')?></th>
                <th><?=THelper::t('difference')?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($infoLoad)){ ?>
                <?php foreach($infoLoad as $k=>$item){ ?>
                    <?php
                        $difference = $item['amountLoan']-$item['amountRepayment'];
                        $total['loan'] += $item['amountLoan'];
                        $total['repayment'] += $item['amountRepayment'];
                        $total['difference'] += $difference;
                    ?>
                    <tr>
                        <td><?=$item['infoUser']?></td>
                        <td><?=$item['amountLoan']?></td>
                        <td><?=$item['amountRepayment']?></td>
                        <td class="<?=($difference>0 ? 'text-danger' : 'text-success')?>"><?=abs($difference)?></td>
                        <td>
                            <?= Html::a('<i class="fa fa-usd"></i> '.THelper::t('sent_loan'), ['/business/loan/sent-repayment','id'=>$k], ['data-toggle'=>'ajaxModal','class'=>'btn btn-success']) ?>
                            <?= Html::a('<i class="fa fa-eye"></i> '.THelper::t('more'), ['/business/loan/more-look-repayment','id'=>$k],['class'=>'btn btn-primary']) ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
            <tfooter>
                <tr>
                    <th><?=THelper::t('total')?></th>
                    <th><?=$total['loan']?></th>
                    <th><?=$total['repayment']?></th>
                    <th class="<?=($total['difference']>0 ? 'text-danger' : 'text-success')?>"><?=abs($total['difference'])?></th>
                    <th></th>
                </tr>
            </tfooter>
        </table>
    </div>
</section>

<script>
    $('#loan-filter-id').change(function(el){
        console.log(el);
        window.location.href ='/' + LANG + '/business/loan/loans?f='+$('#loan-filter-id').val();
    });
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
</script>

