<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    $this->title = THelper::t('finances');
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="row m-b">
    <div class="col-md-4">
        <h3><?=THelper::t('client_account')?></h3>
        <section class="panel">
            <div class="text-center wrapper bg-light lt">
                <div class="sparkline inline" data-type="pie" data-height="165" data-slice-colors="['#77c587','#41586e']">
                    <?= $user->statistics->personalIncome ?>, <?= $user->statistics->structIncome ?>
                </div>
            </div>
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->personalIncome ?></span>
                    <span class="label bg-primary">1</span>
                    <?=THelper::t('personal_award')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->structIncome ?></span>
                    <span class="label bg-dark">2</span>
                    <?=THelper::t('team_award')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->mentorBonus ?></span>
                    <span class="label bg-77382E">3</span>
                    <?=THelper::t('mentor_bonus')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->careerBonus ?></span>
                    <span class="label bg-009A8C">4</span>
                    <?=THelper::t('career_bonus')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->executiveBonus ?></span>
                    <span class="label bg-AAA100">5</span>
                    <?=THelper::t('executive_bonus')?>
                </li>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->worldBonus ?></span>
                    <span class="label bg-AA0900">6</span>
                    <?=THelper::t('world_bonus')?>
                </li>
            </ul>
        </section>

        <ul class="list-group">
            <li class="list-group-item">
                <i class="fa fa-bookmark m-r-xs" aria-hidden="true"></i>
                <span class="label label-danger label-pill pull-right"><?= $user->statistics->stocks ?></span>
                <?= THelper::t('shares_vipvip') ?>
            </li>
            <li class="list-group-item">
                <i class="fa fa-bookmark m-r-xs" aria-hidden="true"></i>
                <span class="label label-success label-pill pull-right"><?= $user->statistics->dividendsVIPVIP ?></span>
                <?= THelper::t('dividends_vipvip') ?>
            </li>
        </ul>
    </div>
    <div class="col-md-8">
        <h3><?=THelper::t('current_savings_balance_on_the_personal_account')?>:</h3>
        <div class="col-md-7 col-lg-5">
            <div class="row ">
                <div class="panel-footer bg-info text-center">
                    <div class="row pull-out">
                        <div class="col-xs-6">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= $user->pointsLeft ?></span>
                                <small class="text-muted"><?=THelper::t('left_team')?></small>
                            </div>
                        </div>
                        <div class="col-xs-6 dk">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= $user->pointsRight ?></span>
                                <small class="text-muted"><?=THelper::t('right_team')?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?= Html::a(THelper::t('scoring_history'), 'finance/points', ['class'=>'col-xs-12 btn btn-s-md btn-warning', 'style' => 'margin: 10px 0px;']); ?>
                <br><br><br><br><br><br>
                <div class="panel-footer text-center">
                    <div class="row pull-out">
                        <div class="col-xs-6 bg-664CC1">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= $user->statistics->autoBonus ?></span>
                                <small class="text-white"><?= strip_tags($autoBonus) ?></small>
                            </div>
                        </div>
                        <div class="col-xs-6 bg-593FB5">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= $user->statistics->propertyBonus ?></span>
                                <small class="text-white"><?= strip_tags($propertyBonus) ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br><br><br>
                <div class="form-group col-xs-12">
                    <label class="col-sm-9 control-label switch-center"><?=THelper::t('activity_business_support')?></label>
                    <div class="col-sm-3"> <label class="switch">
                            <input type="checkbox" class="autoExtensionBS" <?= $user->autoExtensionBS ? 'checked' : '' ?>> <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="max-width: 992px">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                        <?=THelper::t('withdrawal_rules')?>
                    </a>
                </div>
                <div id="collapseOne" class="panel-collapse in">
                    <div class="panel-body text-sm">
                        <?=THelper::t('finance_rules_body')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-md-9">
            <div class="m-b-md form-inline pull-left" style="width: 100%; height: 39px; border: 1px solid #e8e8e8; background-color: white;">
                <div style="margin-left: 65px; margin-top: 6px; font-size: large; display: block"><?=THelper::t('balance')?></div>
                <div class="pull-right" style="margin-top: -30px; margin-right: 2px; width: 110px; height: 35px; border: 1px solid #e8e8e8; background-color: white; display: inline-block">
                    <div class="sum">
                        <div style="margin: 6px auto">
                            <p id="balance" style="text-align: center"><?= $user->moneys ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-md-3 text-center m-b">
            <?= Html::a(THelper::t('operations_history'), 'finance/operations', ['class'=>'btn btn-facebook', 'style'=>'width: 180px;']); ?>
        </div>
    </div>
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'withdrawal']); ?>
        <div class="col-sm-8 col-md-9">
            <input type="hidden" value="withdrawal" name="finance">
            <div class="input-group m-b-md input_invalid">
                <span class="input-group-addon" style="height: 34px">$</span>
                <?= $form->field($model, 'withdrawal')->textInput(['id' => 'withdrawal-amount', 'class' => 'form-control', 'style' => 'margin-top: 5px'])->label(false) ?>
                <span class="input-group-addon">.00</span>
            </div>
        </div>
        <div class="col-sm-4 col-md-3 text-center m-b">
            <?= Html::a(THelper::t('order_for_withdrawal'), ['/business/finance/withdrawal'], ['id' => 'btn-withdrawal', 'class'=>'btn btn-success', 'style'=>'width: 180px;', 'data-toggle'=>'ajaxModal']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php $form = ActiveForm::begin(['id' => 'voucher', 'enableAjaxValidation' => true]); ?>
    <div class="row">
        <input type="hidden" value="voucher" name="finance">
        <div class="col-sm-6 col-md-3">
            <?= $form->field($model, 'product')->dropDownList($productsSelect, ['prompt' => THelper::t('select_product')])->label(false); ?>
            <?= $form->field($model, 'balance')->hiddenInput()->label(false); ?>
        </div>
        <div class="col-sm-6 col-md-3">
            <?= $form->field($model, 'productPrice')->textInput(['disabled' => 'disabled'])->label(false); ?>
        </div>
        <div class="col-sm-6 col-md-3 text-center">
            <button type="submit" class="btn btn-warning m-b" style="width: 180px"><?=THelper::t('create_a_voucher')?></button>
        </div>
        <div class="col-sm-6 col-md-3 text-center m-b">
            <div><?= Html::a(THelper::t('history'), ['/business/finance/vouchers'], ['data-toggle'=>'ajaxModal']) ?></div>
        </div>
    </div>
    <div class="row f-pass" style="display: none;">
        <div class="col-sm-6 col-md-6">
            <?= $form->field($model, 'financePassword')->passwordInput()->label(THelper::t('password_on_financial_transactions')) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'pincode']); ?>
        <div class="col-sm-6 col-md-6">
            <input type="hidden" value="pincode" name="finance">
            <div class="input-group m-b-md input_invalid">
                <span class="input-group-addon" style="height: 34px">P</span>
                <?= $form->field($model, 'pincode')->textInput(['class' => 'form-control', 'style' => 'margin-top: 5px'])->label(false) ?>
                <span class="input-group-addon">.00</span>
            </div>
        </div>
        <div class="col-sm-4 col-md-3 text-center m-b">
            <button type="submit" style="width: 180px" class="btn btn-twitter"><?=THelper::t('buy_product_with_pincode')?></button>
        </div>
        <div class="col-sm-2 col-md-3 text-center m-b">
            <a class="a-color-red" href="http://vipsite.biz/index.php?route=product/product&path=59&product_id=81" target="_blank"><?= THelper::t('add_funds') ?></a>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <?=THelper::t('list_of_resources_for_withdrawal')?>
                <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
            </header>
            <div class="table-responsive">
                <table id="for_withdrawal" class="table table-striped m-b-none unique_table_class">
                    <thead>
                    <tr>
                        <th width="33%"><?=THelper::t('date')?></th>
                        <th width="33%"><?=THelper::t('sum')?></th>
                        <th width="33%"><?=THelper::t('application_status')?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $value) { ?>
                            <tr>
                                <th width="33%"><?= gmdate('d.m.Y', $value->dateCreate) ?></th>
                                <th width="33%"><?= $value->amount ?></th>
                                <th width="33%"><?= $value->getStatusAsString() ?></th>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<script>
    var productPrices = <?= $productPrices ?>;
    $('#financeform-product').change(function() {
        if ($(this).val()) {
            $('#financeform-productprice').val(productPrices[$(this).val()]);
            $('.f-pass').show();
        } else {
            $('#financeform-productprice').val('');
            $('.f-pass').hide();
        }
    });
    var withdrawalAmount;
    $('#btn-withdrawal').click(function() {
        withdrawalAmount = $('#withdrawal-amount').val();
    });
</script>

<?php $this->registerJsFile('/js/main/business_finance.js'); ?>
