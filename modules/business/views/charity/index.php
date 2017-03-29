<?php
    use yii\helpers\Html;
    use app\components\THelper;
    use yii\bootstrap\ActiveForm;
    $this->title = THelper::t('charity');
    $this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($successText) { ?>
    <div class="alert alert-success">
        <?= $successText ?>
    </div>
<?php } else if ($errorsText) {
    foreach ($errorsText as $key => $e) { ?>
        <div class="alert alert-danger">
            <?= $charityForm->attributeLabels()[$key] . ': ' . current($e) ?>
        </div>
    <?php } ?>
<?php } ?>

<div class="row" style="max-width: 640px">
    <div class="col-sm-12">
        <h4><?= THelper::t('deduction_to_charity_help_the_whole_world') ?></h4>
    </div>
    <div class="col-sm-12 m-b">
        <img class="img-responsive" src="/images/charity.jpg" alt="charity">
    </div>
    <div class="col-sm-12 m-b">
        <div style="height: 41px; border: 1px solid #e8e8e8; background-color: white;">
            <div style="margin-left: 65px; margin-top: 6px; font-size: large;  display: block"><?=THelper::t('balance')?></div>
            <div class="pull-right" style="margin-top: -30px; margin-right: 2px; width: 110px; height: 35px; border: 1px solid #e8e8e8; background-color: white; display: inline-block">
                <div class="sum">
                    <div style="margin: 6px auto">
                        <p id="balance" style="text-align: center"><?= $user->moneys ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-sm-9 m-b-lg">
            <div class="input-group input_invalid">
                <span class="input-group-addon">$</span>
                <?= $form->field($charityForm, 'amount')->textInput(['class' => 'form-control'])->label(false); ?>
                <span class="input-group-addon">.00</span>
            </div>
        </div>
        <div class="col-sm-3 m-b-lg text-center">
            <?= Html::submitButton(THelper::t('donate'), ['class' => 'btn btn-info']); ?>
        </div>
        <div class="col-sm-5 m-b-lg">
            <div class="input-group input_invalid">
                <span class="input-group-addon"><?= THelper::t('autodeduction'); ?></span>
                <?= $form->field($charityForm, 'percent')->textInput(['id' => 'percent', 'value' => $user->charityPercent, 'class' => 'form-control'])->label(false); ?>
                <span class="input-group-addon">%</span>
            </div>
        </div>
        <div class="col-sm-3 m-b text-center">
            <?= Html::submitButton(THelper::t('save'), ['id' => 'save-percent', 'class' => 'btn btn-s-md btn-success']); ?>
            <i id="spinner" class="fa fa-3x fa-spinner fa-spin" style="display: none;"></i>
        </div>
        <div class="col-sm-4 m-b text-center">
            <?= Html::a(THelper::t('report_about_the_charity'), ['charity/reports'], []); ?>
        </div>
    <?php ActiveForm::end(); ?>
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                <?=THelper::t('your_charities')?>
                <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
            </header>
            <div class="table-responsive">
                <table id="personal_invitation_list_table_vd" class="table table-striped m-b-none unique_table_class asasas" data-ride="datatables">
                    <thead>
                    <tr>
                        <th width="18%"><?= THelper::t('date') ?></th>
                        <th width="18%"><?= THelper::t('amount') ?></th>
                        <th width="20%"><?= THelper::t('comment') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($charities as $c){ ?>
                        <tr>
                            <td><?= date('d-m-Y, H:i:s', $c->dateCreate) ?></td>
                            <td><?= $c->amount ?></td>
                            <td><?= $c->forWhat ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php $this->registerJsFile('/js/main/charity.js'); ?>