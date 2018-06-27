<?php
use app\components\THelper;
use yii\bootstrap\Html;
use app\components\AlertWidget;
use app\models\Users;

/** @var $item \app\models\RecoveryForRepaymentAmounts */
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_table_recovery_for_repayment') ?></h3>
    <?php if(!empty($representative_id)){?>
    <h4 class="m-b-none"><?= THelper::t('representative') ?>: <?=Users::findOne(['_id'=>$representative_id])->username?></h4>
    <?php } ?>
</div>

<div class="row">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <div class="col-md-offset-9 col-md-3 form-group">
        <?=Html::a('<i class="fa fa-plus"></i>',['/business/offsets-with-warehouses/add-recovery-for-repayment','object'=>$object,'representative_id'=>$representative_id],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
    </div>
</div>

<?php if(!empty($model)) { ?>
    <section class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-recovery-repayment table-striped datagrid m-b-sm">
                <thead>
                <tr>
                    <th><?=THelper::t('date')?></th>
                    <th><?=THelper::t('representative')?></th>
                    <th><?=THelper::t('recovery')?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($model as $item) { ?>
                    <tr>
                        <td><?=$item->month_recovery;?></td>
                        <td><?=$item->representative->username;?></td>
                        <td><?=$item->recovery;?></td>
                        <td>
                            <?=($object=='representative' ?
                                Html::a('Удержания со складов',
                                [
                                    'offsets-with-warehouses/recovery-for-repayment',
                                    'object'=>'warehouse',
                                    'representative_id'=>(string)$item->representative_id
                                ]) : '')?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
<?php } ?>

<script>
    $('.table-recovery-repayment').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

</script>