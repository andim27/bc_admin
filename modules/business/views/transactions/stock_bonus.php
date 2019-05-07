<?php
    use app\components\THelper;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_stock_bonus') ?></h3>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a data-toggle="tab" href="#wellness">Wellness</a></li>
            <li><a data-toggle="tab" href="#vipvip">VipVip</a></li>
        </ul>
        <div class="tab-content" style="padding:15px">
            <div id="wellness" class="tab-pane fade in active">
                <div class="panel panel-body">
                    <div class="progress-wellness progress progress-sm progress-striped active" style="display: none;">
                        <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
                    </div>
                    <div id="stock-bonus-pay-info-wellness"></div>
                </div>
            </div>
            <div id="vipvip" class="tab-pane fade in">
                <div class="panel panel-body">
                    <div class="progress-vipvip progress progress-sm progress-striped active" style="display: none;">
                        <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
                    </div>
                    <div id="stock-bonus-pay-info-vipvip"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function loadStockBonusPayInfoWellness(amount) {
        $('.progress-wellness').show();
        $('#stock-bonus-pay-info-wellness').html('');
        $.ajax({
            url: '/' + LANG + '/business/transactions/get-stock-bonus-pay-info',
            method: 'post',
            data: {
                type: 'wellness',
                amount: amount
            },
            success: function (data) {
                $('#stock-bonus-pay-info-wellness').html(data);
                bindWellness();
                $('.progress-wellness').hide();
            },
            error: function () {
                $('.progress-wellness').hide();
            }
        });
    }

    loadStockBonusPayInfoWellness();

    function loadStockBonusPayInfoVipVip(amount) {
        $('.progress-vipvip').show();
        $('#stock-bonus-pay-info-vipvip').html('');
        $.ajax({
            url: '/' + LANG + '/business/transactions/get-stock-bonus-pay-info',
            method: 'post',
            data: {
                type: 'vipvip',
                amount: amount
            },
            success: function (data) {
                $('#stock-bonus-pay-info-vipvip').html(data);
                bindVipVip();
                $('.progress-vipvip').hide();
            },
            error: function () {
                $('.progress-vipvip').hide();
            }
        });
    }

    loadStockBonusPayInfoVipVip();

    function bindWellness() {
        $('#get-stock-bonus-wellness').on('click', function () {
            var amount = $('#stock-bonus-amount-wellness').val();
            loadStockBonusPayInfoWellness(amount);
        });
    }

    function bindVipVip() {
        $('#get-stock-bonus-vipvip').on('click', function () {
            var amount = $('#stock-bonus-amount-vipvip').val();
            loadStockBonusPayInfoVipVip(amount);
        });
    }
</script>
