<?php
use app\components\THelper;

$total = [
    'loan'          =>  0,
    'repayment'     =>  0,
    'difference'    =>  0,
];
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_loan') ?> (<?=$infoUser->username?>)</h3>
</div>


<section class="panel panel-default">
    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th><?=THelper::t('date_create');?></th>
                <th><?=THelper::t('user')?></th>
                <th><?=THelper::t('loan')?></th>
                <th><?=THelper::t('repayment_for_loan')?></th>
                <th><?=THelper::t('settings_translation_edit_comment')?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($info)){ ?>
                <?php foreach($info as $k=>$item){ ?>
                    <?php
                    $total['loan'] += $item['amountLoan'];
                    $total['repayment'] += $item['amountRepayment'];
                    $total['difference'] += $item['amountLoan']-$item['amountRepayment'];
                    ?>
                    <tr>
                        <td><?=$k?></td>
                        <td><?=$item['userSentTransaction']?></td>
                        <td><?=$item['amountLoan']?></td>
                        <td><?=$item['amountRepayment']?></td>
                        <td><?=$item['comment']?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
            <tfooter>
                <tr>
                    <th><?=THelper::t('total')?></th>
                    <th></th>
                    <th><?=$total['loan']?></th>
                    <th><?=$total['repayment']?></th>
                    <th></th>
                </tr>
            </tfooter>
        </table>
    </div>
</section>

<script>
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });
</script>

