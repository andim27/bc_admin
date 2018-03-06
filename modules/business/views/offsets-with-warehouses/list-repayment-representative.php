<?php
use app\components\THelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Repayment;
use app\components\AlertWidget;
?>


<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_offsets_with_representative') ?></h3>
</div>


<div class="row blQuery">
    <?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

    <?php $formStatus = ActiveForm::begin([
        'action' => '/' . $language . '/business/offsets-with-warehouses/list-repayment-representative',
        'options' => ['name' => 'saveStatus', 'data-pjax' => '1'],
    ]); ?>

    <div class="col-md-10">
        <div class="input-group">
            <?= Html::input('text','date_repayment',$request['date_repayment'],['class' => 'form-control datepicker-input dateFrom', 'data-date-format'=>'yyyy-mm'])?>
        </div>
    </div>


    <div class="col-md-2 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-block btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<!--    <div class="col-md-4 m-b text-right">-->
<!--        --><?php//=Html::a(THelper::t('sidebar_offsets_with_warehouses'),'offsets-with-warehouses')?>
<!--    </div>-->
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-translations table-striped datagrid m-b-sm">
                    <thead>
                    <tr>
                        <th><?=THelper::t('representative')?></th>
                        <th><?=THelper::t('amount_repayment')?></th>
                        <th><?=THelper::t('deduction')?></th>
                        <th><?=THelper::t('total')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($info)) { ?>
                        <?php foreach($info as $kRepresentative=>$itemRepresentative) { ?>
                            <tr>
                                <td><?=$itemRepresentative['title']?></td>
                                <td><?=$itemRepresentative['amount_repayment']?></td>
                                <td><?=$itemRepresentative['deduction']?></td>
                                <td><?=($itemRepresentative['amount_repayment']-$itemRepresentative['deduction'])?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <th colspan="4">
                                <?=($repayment_paid === true ? '' : Html::a('Провести выплату',['offsets-with-warehouses/make-repayment-representative','dateRepayment'=>$request['date_repayment']],['class'=>'btn btn-default']))?>
                            </th>
                        </tr>
                    </tfooter>
                </table>
            </div>

        </section>
    </div>
</div>


<script type="text/javascript">
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 1, "desc" ]]
    });
</script>

<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js'); ?>