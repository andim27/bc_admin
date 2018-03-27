<?php
use app\components\THelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use app\components\AlertWidget;

$negative_payment = false;
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
            <?= DatePicker::widget([
                'name' => 'date_repayment',
                'value'=>$request['date_repayment'],
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm',
                    'startView'=>'year',
                    'minViewMode'=>'months',
                ]
            ]); ?>
        </div>
    </div>


    <div class="col-md-2 m-b">
        <?= Html::submitButton(THelper::t('search'), ['class' => 'btn btn-block btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($info)) { ?>
                        <?php foreach($info as $kRepresentative=>$itemRepresentative) { ?>

                            <?php
                                $repayment = $itemRepresentative['amount_repayment']-$itemRepresentative['deduction'];
                                if($repayment<0){
                                    $negative_payment = true;
                                }
                            ?>

                            <tr>
                                <td><?=$itemRepresentative['title']?></td>
                                <td><?=$itemRepresentative['amount_repayment']?></td>
                                <td><?=$itemRepresentative['deduction']?></td>
                                <td><?=$repayment?></td>
                                <td>
                                    <?php if($repayment_paid == true){ ?>
                                    <i class="fa fa-check-square text-success"></i>
                                    <?php } else { ?>
                                    <i class="fa fa-minus-square text-danger"></i>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                    <tfooter>
                        <tr>
                            <th colspan="5">
                                <?=(($repayment_paid === true || $negative_payment== true) ?
                                    '' :
                                    Html::a(THelper::t('make_payment'),['offsets-with-warehouses/make-repayment-representative','dateRepayment'=>$request['date_repayment']],['class'=>'btn btn-default']))?>
                            </th>
                        </tr>
                    </tfooter>
                </table>
            </div>

        </section>
    </div>
</div>

<div class="m-b-md">
    <?=Html::a(THelper::t('table_penalties_from_representative'),['/business/offsets-with-warehouses/recovery-for-repayment','object'=>'representative'],['class'=>'btn btn-default','target'=>'_blank'])?>
</div>

<script type="text/javascript">
    $('.table-translations').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 1, "desc" ]]
    });
</script>
