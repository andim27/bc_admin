<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use yii\helpers\ArrayHelper;
    use yii\web\View;


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
                <?php if (isset($user->statistics->careerBonus)) { ?>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->careerBonus ?></span>
                    <span class="label bg-009A8C">4</span>
                    <?=THelper::t('career_bonus')?>
                </li>
                <?php } ?>
                <?php if (isset($user->statistics->careerBonus)) { ?>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->executiveBonus ?></span>
                    <span class="label bg-AAA100">5</span>
                    <?=THelper::t('executive_bonus')?>
                </li>
                <?php } ?>
                <?php if (isset($user->statistics->careerBonus)) { ?>
                <li class="list-group-item">
                    <span class="pull-right"><?= $user->statistics->worldBonus ?></span>
                    <span class="label bg-AA0900">6</span>
                    <?=THelper::t('world_bonus')?>
                </li>
                <?php } ?>
            </ul>
        </section>
        <ul class="list-group">
            <?php if (isset($user->statistics->stock->vipvip->total)) { ?>
            <li class="list-group-item">
                <i class="fa fa-bookmark m-r-xs" aria-hidden="true"></i>
                <span class="label label-danger label-pill pull-right"><?= $user->statistics->stock->vipvip->total ?></span>
                <?= THelper::t('stock_vipvip') ?>
            </li>
            <?php } ?>
            <?php if (isset($user->statistics->stock->wellness->total)) { ?>
            <li class="list-group-item">
                <i class="fa fa-bookmark m-r-xs" aria-hidden="true"></i>
                <span class="label label-danger label-pill pull-right"><?= $user->statistics->stock->wellness->total ?></span>
                <?= THelper::t('stock_wellness') ?>
            </li>
            <?php } ?>
            <li class="list-group-item">
                <i class="fa fa-bookmark m-r-xs" aria-hidden="true"></i>
                <span class="label label-danger label-pill pull-right"><?= isset($user->statistics->stock->vipcoin) ? $user->statistics->stock->vipcoin : 0 ?></span>
                <?= THelper::t('stock_vipcoin') ?>
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
                                <span class="m-b-xs h3 block text-white"><?= isset($user->statistics->autoBonus) ? $user->statistics->autoBonus : 0 ?></span>
                                <small class="text-white"><?= strip_tags($autoBonus) ?></small>
                            </div>
                        </div>
                        <div class="col-xs-6 bg-593FB5">
                            <div class="padder-v">
                                <span class="m-b-xs h3 block text-white"><?= isset($user->statistics->propertyBonus) ? $user->statistics->propertyBonus : 0 ?></span>
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
<div>
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
        <div class="col-sm-7 col-md-8">
            <div class="m-b-md form-inline pull-left" style="width: 100%; height: 39px; border: 1px solid #e8e8e8; background-color: white;">
                <div style="margin-left: 65px; margin-top: 6px; font-size: large; display: block"><?=THelper::t('balance')?></div>
                <div class="pull-right" style="margin-top: -30px; margin-right: 2px; width: 110px; height: 35px; border: 1px solid #e8e8e8; background-color: white; display: inline-block">
                    <div class="sum">
                        <div style="margin: 6px auto">
                            <p id="balance" style="text-align: center"><?= round($user->moneys, 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3 col-md-2 text-center m-b">
            <?= Html::a(THelper::t('operations_history'), 'finance/operations', ['class'=>'btn btn-facebook', 'style'=>'width: 180px;']); ?>
        </div>
    </div>

    <div class="row">
        <div  class="col-sm-12" id="withdrawalAmountError"></div>
        <?php $form = ActiveForm::begin(['id' => 'withdrawal']); ?>
        <div class="col-sm-7 col-md-8">
            <input type="hidden" value="withdrawal" name="finance">
            <div class="input-group m-b-md input_invalid">
                <span class="input-group-addon" style="height: 34px">€</span>
                <?= $form->field($model, 'withdrawal')->textInput(['id' => 'withdrawal-amount', 'class' => 'form-control', 'style' => 'margin-top: 5px'])->label(false) ?>
                <span class="input-group-addon">.00</span>
            </div>
        </div>
        <div class="col-sm-3 col-md-2 text-center m-b">
            <?= Html::a(THelper::t('order_for_withdrawal'), ['/business/finance/withdrawal'], [
                'id' => 'btn-withdrawal',
                'class'=>'btn btn-success',
                'style'=>'width: 180px;',
                'data-toggle'=>'ajaxModal'
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'voucher', 'enableAjaxValidation' => true]); ?>
    <div class="row">
        <input type="hidden" value="pin" name="finance">
        <div class="col-sm-2 col-md-2">
            <?= $form->field($model, 'productGroup')->dropDownList($productGroupsSelect, ['prompt' => THelper::t('select_product_group')])->label(false); ?>
        </div>
        <div class="col-sm-2 col-md-2">
            <?= $form->field($model, 'productSubGroup')->dropDownList([], ['prompt' => THelper::t('select_product_sub_group'), 'disabled' => 'disabled'])->label(false); ?>
        </div>
        <div class="col-sm-6 col-md-3">
            <?= $form->field($model, 'product')->dropDownList($productsSelect, ['disabled' => 'disabled', 'prompt' => THelper::t('select_product')])->label(false); ?>
            <?= $form->field($model, 'balance')->hiddenInput()->label(false); ?>
        </div>
        <div class="col-sm-5 col-md-2 text-center m-b">
            <div><?= Html::a(THelper::t('pincode_history'), ['/business/finance/pincode-history'], ['data-toggle'=>'ajaxModal']) ?></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-1 col-md-1">
            <?= $form->field($model, 'productPrice')->textInput(['disabled' => 'disabled', 'style' => 'margin-top: 23px;'])->label(false); ?>
        </div>
        <div class="col-sm-5 col-md-3 text-center">
            <?= $form->field($model, 'pinMode')->dropDownList($pinModeSelect, ['prompt' => THelper::t('select_pin_mode'), 'style' => 'margin-top: 23px;'])->label(false); ?>
        </div>
        <div class="col-sm-6 col-md-3 partner-login" style="display: none;">
            <?= $form->field($model, 'partnerLogin')->textInput(['required' => true])->label(THelper::t('partner_login_or_email_or_phone')) ?>
        </div>
    </div>

    <div class="row f-pass" style="display: none;">
        <div class="col-sm-5 col-md-5">
            <?= $form->field($model, 'financePassword')->passwordInput()->label(THelper::t('password_on_financial_transactions')) ?>
        </div>
        <div class="col-sm-3 col-md-2 text-center m-b">
            <?= Html::button(THelper::t('accept'), ['id' => 'btnPartnerPaymentAccept', 'class' => 'btn btn-block btn-warning', 'type' => 'submit', 'style'=>'width: 180px;margin-top: 23px;display:inline-block;'])?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'pincode']); ?>
        <div class="infoPincode"></div>
        <div class="col-sm-5 col-md-5 ">
            <input type="hidden" value="pincode" name="finance">
            <?= $form->field($model, 'pincode')->textInput()->label(false) ?>
        </div>
        <div class="col-sm-4 col-md-3 text-center m-b">
            <?php echo Html::button(THelper::t('buy_product_with_pincode'),['class'=>'btn btn-block btn-twitter','type'=>'button','id'=>'btnBuyProductWithPincode']) ?>
        </div>
        <div class="col-sm-2 col-md-2 text-center m-b">
            <a class="btn btn-block btn-danger" style="width: 180px;display:inline-block;" href="http://vipsite.biz/index.php?route=product/product&path=59&product_id=81" target="_blank"><?= THelper::t('add_funds') ?></a>
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

<div class="modal fade" id="listWarehouse">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?=THelper::t('select_warehouse')?></h4>
            </div>
            <div class="modal-body">
                <div class="blError"></div>

                <div class="form-group">
                    <?=Html::dropDownList('warehouse','',ArrayHelper::merge([''=>THelper::t('select_warehouse')],$listWarehouse),[
                        'class'=>'form-control',
                        'id'=>'selectWarehouse',
                        'options' => [
                            '' => ['disabled' => true],
                        ]
                    ])?>
                </div>
                <div class="form-group text-right">
                    <?=Html::button(THelper::t('confirm'),['type'=>'button','class'=>'btn btn-success','id'=>'btnSelectWarehouse', 'data-partner' => false, 'data-dismiss' => 'modal'])?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$attentionLittleMoney = '"' . THelper::t('you_can_withdraw_more_50_eur') . '"';
$attentionNotSelectWarehouse = '"' . THelper::t('not_select_warehouse') . '"';
$linkBuyProductWithPincode = '"' . yii\helpers\Url::to(['finance/check-pincode-wellness']) . '"';
$getProductGroupUrl = '"' . yii\helpers\Url::to(['finance/get-product-group']) . '"';
$getProductSubGroupUrl = '"' . yii\helpers\Url::to(['finance/get-product-sub-group']) . '"';
$linkPincode = '"' . yii\helpers\Url::to(['finance/index']) . '"';


$JS = <<< JS
    var productPrices = $productPrices;
    var productsSelect = $('#financeform-product');
    var productsGroupSelect = $('#financeform-productgroup');
    var productsSubGroupSelect = $('#financeform-productsubgroup');
    
    productsSelect.change(function() {
        if ($(this).val()) {
            $('#financeform-productprice').val(productPrices[$(this).val()]);
            $('.f-pass').show();
        } else {
            $('#financeform-productprice').val('');
            $('.f-pass').hide();
        }
    }); 
    
    productsGroupSelect.change(function() {
        var groupId = $(this).val();       
        productsSubGroupSelect.prop('disabled', true);   
        productsSelect.prop('disabled', true); 
         
        $.ajax({
            url: $getProductSubGroupUrl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                group_id : groupId
            },
            success: function (response) {               
                if (groupId && response && !Array.isArray(response)) {                  
                    productsSubGroupSelect.find('option').not(':first').remove().end();
                    
                    $.each(response, function(id, name) {                       
                        productsSubGroupSelect.append('<option value="' + id + '">' + name + '</option>');
                    });
                  
                    productsSubGroupSelect.prop('disabled', false);  
                } else if(groupId) {
                    productsSubGroupSelect.prop('disabled', true);   
                    getProductsByGroup(groupId, '');
                    
                    productsSelect.prop('disabled', false); 
                } else {
                    productsSubGroupSelect.prop('disabled', true);   
                }
            }
        });
    });    
    
    productsSubGroupSelect.change(function() {
        var groupId = productsGroupSelect.val();       
        var subGroupId = $(this).val();  
      
        productsSelect.prop('disabled', true); 
         
        getProductsByGroup(groupId, subGroupId);
    });  
    
    function getProductsByGroup(groupId, subGroupId) {
        $.ajax({
            url: $getProductGroupUrl,
            type: 'POST',
            dataType: 'JSON',
            data: {
                group_id : groupId,
                sub_group_id : subGroupId
            },
            success: function (response) {
                if (response) {
                    productsSelect.find('option').not(':first').remove().end();
                    
                    $.each(response, function(id, name) {
                        productsSelect.append('<option value="' + id + '">' + name + '</option>');
                    });
                }     
                
                productsSelect.prop('disabled', !groupId);  
            }
        });
    }
   
    var login;
    
    $('#financeform-pinmode').change(function() {       
        var partnerLogin = $('.partner-login');  
        var tempLogin = partnerLogin.find('input').val();
        login = tempLogin.length ? tempLogin : login;
            
        if ($(this).val() === '1') { 
            partnerLogin.attr('disabled', false).show();
            partnerLogin.find('input').val(login);
        } else {
            partnerLogin.attr('disabled', true).hide();
            partnerLogin.find('input').val('');
        }
    });
    
    $('#pincode').on('beforeSubmit', function(event, jqXHR, settings) {
        var form = $(this);
        
        if(form.find('.has-error').length) {
                return false;
        }
        
        //@todo it doesn't work WTF?
        
        // $.ajax({
        //         url: form.attr('action'),
        //         type: 'post',
        //         data: form.serialize(),
        //         success: function(data) {
        //                 // do something ...
        //         }
        // });
        //
        return false;
    });  
    
    $('#btnBuyProductWithPincode').on('click', function () {               
        var pincode = $('#financeform-pincode').val();

        if(pincode){
            $.ajax({
                url: $linkBuyProductWithPincode,
                type: 'POST',
                data: {
                    pincode : pincode
                },
                success: function (data) {
                    if(!!data === true){
                        var listWarehouse = $("#listWarehouse");
                        $('#btnSelectWarehouse').data('partner', false);
                        listWarehouse.modal();
                    } else {
                        submitPincodeForm();
                    }
                }
            });
        }       
    }); 
    
    $('#btnPartnerPaymentAccept').on('click', function (event) {               
        event.preventDefault();
        $('#btnSelectWarehouse').data('partner', true);
        submitVoucherForm();     
    }); 
    
    $('#btn-withdrawal').on('click',function(event) {
        withdrawalAmount = '';
        withdrawalAmount = $('#withdrawal-amount').val();

        $("#withdrawalAmountError").html('');

        if(withdrawalAmount === '' || withdrawalAmount < 50){
            event.preventDefault();
            event.stopImmediatePropagation();

            $("#withdrawalAmountError").html(
                '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                     $attentionLittleMoney +
                '</div>'
            );
        }

    });

    $('form#withdrawal,form#pincode').keydown(function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }
    });
    
    $('#btnSelectWarehouse').on('click',function () {
        warehouseId = $("#selectWarehouse").val();
        
        var isPartner = $(this).data('partner');
        
        if(warehouseId){
            $(".infoPincode").html('<input type="hidden" value="'+warehouseId+'" name="warehouse"><input type="hidden" value="'+isPartner+'" name="is_partner">');
            submitPincodeForm();
        } else {
            $('#listWarehouse .blError').html(
                '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                    $attentionNotSelectWarehouse +
                '</div>');
        }
        
    });
    
    function submitPincodeForm(){
        var form = $('#pincode'); 

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(data) {
                $('#listWarehouse').after(data);
                $('#pincode-sposnor').modal();
                    // do something ...
            }
        });
    }
    
        
    function submitVoucherForm(){
        var form = $('#voucher'); 

        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: form.serialize(),
            success: function(data) {               
                if(data){
                    var error = '';
                    
                    if(typeof data === 'object') {
                        $.each(data, function(key, value) {
                            if(key === 'modal') {
                                switch (value) {
                                    case 'warehouse':
                                         $("#listWarehouse").modal();
                                         break; 
                                    case 'partner_confirm':
                                         $("#listWarehouse").after(data.template);
                                         $("#partner-confirm").modal();
                                         break;
                                        
                                }                             
                            } 
                            
                            if(key === 'financeform-product') {
                                error = value;
                            }                      
                        });
                  
                        if(error) { 
                           $("#withdrawalAmountError").html(
                            '<div class="alert alert-danger fade in">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                 error +
                            '</div>'
                            );
                        }   
                    } 
                } 
                
                $('#listWarehouse').after(data);
                $('#pincode-sposnor').modal();
                    // do something ...
            }
        });
    }
    
    
JS;
$this->registerJs($JS, View::POS_END);
?>

<?php $this->registerJsFile('js/main/date.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('/js/main/business_finance.js'); ?>
