<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    use kartik\widgets\Select2;
?>
<div class="row m-b">
    <div class="col-md-12">
        <h3><?= THelper::t('pincode_generate_title') ?></h3>
    </div>
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="panel-body">

                <?php $form = ActiveForm::begin(['id' => $model->formName(), 'enableAjaxValidation' => true]); ?>
                <div class="col-md-12">
                    <?= $form->field($model, 'pin')->label(THelper::t('pin')) ?>
                </div>




                <div class="col-md-6">
                    <?= $form->field($model, 'partnerLogin')->textInput(['id' => 'login'])->label(THelper::t('login')) ?>

                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="loan-balance"><span id="loan-block-moneys" class="mark" >Расчеты:</span></label><br>
                        <button type="button" class="btn btn-s-md btn-success" style=""  id="loan-balance"><?= THelper::t('sidebar_loan') ?> ?</button>

                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="user-balance">На счету:<span id="balance-block-moneys" class="mark" ></span></label>
                        <button type="button" class="btn btn-s-md btn-success" style=""  id="user-balance"><?=THelper::t('current_balance') ?> ?</button>

                    </div>

                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="kind-operation">Вид операции</label>
                        <select id="kind-operation" name="kind-operation" class="form-control">
                            <option value="loan">Займ</option>
                            <option value="bank">Пополнение через банк</option>
                            <option value="paysera">Пополнение баланса PaySera</option>
                            <option value="advcash">Пополнение баланса AdvCash</option>
                            <option value="advaction">Пополнение по рекламной акции</option>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="product-list">Операция</label>
                    <select id="product-list" class="form-control">
                        <?php foreach ($productList as $key=>$value) {  ?>
                        <option value="<?=$key ?>" selected ><?=$value ?></option>
                        <?php } ?>
                    </select>
                    <?php $model->product = (!empty($model->product) ? $model->product : $defaultProduct);?>
<!--                    $form->field($model, 'product')->widget(Select2::className(),[-->
<!--                        'data' => $productList,-->
<!--                        'language' => 'ru',-->
<!--                        'options' => [-->
<!--                            'id'=>'product-list',-->
<!--                            'placeholder' => '',-->
<!--                            'multiple' => false-->
<!--                        ]-->
<!--                    ])->label(THelper::t('product'))-->

                </div>

                <div class="col-md-12" id="comment-row" >
                    <div class="form-group">
                        <label for="comment">Примечание</label>
                        <input id="comment" name="comment" class="form-control" type="text" />
                    </div>
                </div>
                <div class="col-md-12" style="display: none">
                    <?= $form->field($model, 'loan')->checkbox([],['checked '=>true])->label(THelper::t('loan')) ?>
                </div>
                <?php if (isset($productListData[$defaultProduct])) { ?>
                    <div class="col-md-12" style="margin-bottom: 15px;">
                        <div>
                            <?php $productData = $productListData[$defaultProduct]; ?>

                            <?=THelper::t('price')?>: <strong><span id="price"><?=$productData['price']?></span></strong>
                            <?=THelper::t('bonus')?>: <strong><span id="bonus-money"><?=$productData['bonusMoney']?></span></strong>
                            <?=THelper::t('points')?>: <strong><span id="bonus-points"><?=$productData['bonusPoints']?></span></strong>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-12">
                    <?= $form->field($model, 'quantity')->textInput(['value' => 1])->label(THelper::t('quantity')) ?>
                </div>
                <?php if ($pincode) { ?>
                    <div class="col-md-12">
                        <div id="pincode" class="well" style="font-size: 2vw;">
                            <?=$pincode?>
                            <button id="copy-to-clipboard" class="pull-right" title="<?=THelper::t('copy_to_clipboard')?>" style="margin-top: -3px;"><i class="fa fa-files-o" aria-hidden="true"></i></button>
                        </div>
                        <input type="hidden" id="hidden-pincode" value="<?=$pincode?>">
                    </div>
                <?php } ?>
                <div class="col-md-12">
                    <div class="checkbox" style="display: none">
                        <?= $form->field($model, 'isLogin')->checkbox(['id' => 'is-login','checked ' => true, 'value' =>1, 'label' => THelper::t('direct_replenishment')]); ?>
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <?= Html::submitButton(THelper::t('apply'), array('class' => 'btn btn-s-md btn-success', 'style' => 'margin-bottom:15px')); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
</div>
<script>
    var $productList = $('#product-list');
    var $isLogin = $('#is-login');
    var $login = $('#login');
    var $price = $('#price');
    var $bonusMoney = $('#bonus-money');
    var $bonusPoints = $('#bonus-points');
    var $copyToClipboard = $('#copy-to-clipboard');
    var $hiddenPincode = $('#hidden-pincode');
    var $form = $('#<?=$model->formName()?>');

    var productsData = <?=json_encode($productListData)?>;

    var $kind_operation = $('#kind-operation');
    var $loan = $('#pincodegenerateform-loan');
    var $user_balance = $('#user-balance');
    var $loan_balance = $('#loan-balance');
    /**
     * Handlers
     */
    $(document).ready(function () {
        //switchLoginField();
        clearFields();
    });

    $isLogin.on('change', function () {
        //switchLoginField();
    });

    $productList.on('change', function () {
        loadProductData($(this).val());
    });

    $copyToClipboard.on('click', function (e) {
        e.preventDefault();
        var pinCode = $hiddenPincode.val();

        if(copyToClipboard(pinCode)) {
            alert('<?=THelper::t('text_copied_to_clipboard')?>: ' + pinCode);
        }
    });

    $kind_operation.on('change',function () {
       changeKindOperation($(this).val())
    });

    $user_balance.on('click', function () {
        var $username=$login.val();
        if ($username=='') {
            alert('No user login!');
            return;
        }
        $.ajax({
            url: '/' + LANG + '/business/user/get-balance-table',// '<?= \yii\helpers\Url::to(['user/get-balance-table']) ?>',
            type: 'POST',
            data: {
                action:'user-balance',
                login: $username
            },
            success: function (response) {
                if (response) {
                    console.log(response);
                    $('#balance-block-moneys').html('<strong>'+response.data.moneys+'</strong>');
                }
            }
        });
    });
    $loan_balance.on('click', function () {
        var $username=$login.val();
        if ($username=='') {
            alert('No user login!');
            return;
        }
        $.ajax({
            url: '/' + LANG + '/business/user/get-loan-table',// '<?= \yii\helpers\Url::to(['user/get-loan-table']) ?>',
            type: 'POST',
            data: {
                action:'user-loan',
                login: $username
            },
            success: function (response) {
                if (response) {
                    console.log(response);
                    if (response.data.debt >0) {
                        debt_html ='Долг:<span style="color:red">'+response.data.debt+'</span>';
                    } else {
                        debt_html ='Долг:нет';
                    }
                    $('#loan-block-moneys').html('Займ:<strong>'+response.data.loans+'</strong> Выплата:<strong>'+response.data.payments+'</strong> '+debt_html);

                }
            }
        });
    });

     /**  Functions
     */
    function switchLoginField() {
        $login.attr('disabled', !$isLogin.is(':checked'));
    }

    function loadProductData($productId) {
        var product = productsData[$productId];

        $price.text(product.price);
        $bonusPoints.text(product.bonusPoints);
        $bonusMoney.text(product.bonusMoney);
    }

    function clearFields(){
        $form.find('input[name="<?=$model->formName()?>[pin]"]').val('');
        $form.find('input[name="<?=$model->formName()?>[partnerLogin]"]').val('');
    }

  function copyToClipboard(text) {
        if (window.clipboardData && window.clipboardData.setData) {
            // IE specific code path to prevent textarea being shown while dialog is visible.
            return clipboardData.setData("Text", text);

        } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
            var textarea = document.createElement("textarea");
            textarea.textContent = text;
            textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
            document.body.appendChild(textarea);
            textarea.select();
            try {
                return document.execCommand("copy");  // Security exception may be thrown by some browsers.
            } catch (ex) {
                console.warn("Copy to clipboard failed.", ex);
                return false;
            } finally {
                document.body.removeChild(textarea);
            }
        }
    }

    function changeKindOperation(value) {
        if (value =='loan') {
            $loan.attr('checked',true);
        } else {
            $loan.attr('checked',false);
        }
    }

</script>
