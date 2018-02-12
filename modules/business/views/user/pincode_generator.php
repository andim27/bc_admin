<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;

?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('pincode_generate_title') ?></h3>
</div>
<div class="row">
    <section class="panel panel-default">
        <?php $form = ActiveForm::begin(['id' => $model->formName(), 'enableAjaxValidation' => true]); ?>
        <div class="col-md-12">
            <?= $form->field($model, 'pin')->label(THelper::t('pin')) ?>
        </div>

        <div class="col-md-12">
            <?php $model->product = (!empty($model->product) ? $model->product : $defaultProduct);?>
            <?= $form->field($model, 'product')->widget(Select2::className(),[
                'data' => $productList,
                'language' => 'ru',
                'options' => [
                    'id'=>'product-list',
                    'placeholder' => '',
                    'multiple' => false
                ]
            ])->label(THelper::t('product')) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'loan')->checkbox([],[false])->label(THelper::t('loan')) ?>
        </div>

        <div class="col-md-12" style="margin-bottom: 15px;">
            <div>
                <?php $productData = $productListData[$defaultProduct]; ?>

                <?=THelper::t('price')?>: <strong><span id="price"><?=$productData['price']?></span></strong>
                <?=THelper::t('bonus')?>: <strong><span id="bonus-money"><?=$productData['bonusMoney']?></span></strong>
                <?=THelper::t('points')?>: <strong><span id="bonus-points"><?=$productData['bonusPoints']?></span></strong>
            </div>
        </div>

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
            <div class="checkbox">
                <?= $form->field($model, 'isLogin')->checkbox(['id' => 'is-login', 'label' => THelper::t('direct_replenishment')]); ?>
            </div>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'partnerLogin')->textInput(['id' => 'login', 'disabled' => true])->label(THelper::t('login')) ?>
        </div>

        <div class="col-md-12 text-center">
            <?= Html::submitButton(THelper::t('apply'), array('class' => 'btn btn-s-md btn-success', 'style' => 'margin-bottom:15px')); ?>
        </div>


        <?php ActiveForm::end(); ?>

    </section>
</div>
<br>
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


    /**
     * Handlers
     */
    $(document).ready(function () {
        switchLoginField();
        clearFields();
    });

    $isLogin.on('change', function () {
        switchLoginField();
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


    /**
     *  Functions
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
</script>
