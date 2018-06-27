<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="row">
    <div class="col-md-3">
        <?php if ($userFrom) { ?>
            <section class="panel">
                <div class="panel-body">
                    <div class="clearfix text-center m-t">
                        <div class="inline">
                            <img src="<?= $userFrom->avatar ? $userFrom->avatar : '/images/avatar_default.png'; ?>" class="img-responsive" width="128" height="128">
                        </div>
                        <div class="h3 m-t m-b-xs user-from-login"><?= $userFrom->username ?></div>
                        <div class="h4 m-t m-b-xs"><?= $userFrom->firstName ?> <?= $userFrom->secondName ?></div>
                        <p class="text-muted m-b"><?= THelper::t('money_transfer_moneys') ?>: <span id="user-from-moneys"><?= $userFrom->moneys ?></span></p>
                    </div>
                </div>
            </section>
        <?php } else { ?>
            <div class="alert alert-danger">
                <?= THelper::t('user_info_not_found') ?>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-3">
        <?php if ($userTo) { ?>
        <section class="panel">
            <div class="panel-body">
                <div class="clearfix text-center m-t">
                    <div class="inline">
                        <img src="<?= $userTo->avatar ? $userTo->avatar : '/images/avatar_default.png'; ?>" class="img-responsive" width="128" height="128">
                    </div>
                    <div class="h3 m-t m-b-xs user-to-login"><?= $userTo->username ?></div>
                    <div class="h4 m-t m-b-xs"><?= $userTo->firstName?> <?= $userTo->secondName ?></div>
                    <p class="text-muted m-b"><?= THelper::t('money_transfer_moneys') ?>: <span id="user-to-moneys"><?= $userTo->moneys ?></span></p>
                </div>
            </div>
        </section>
        <?php } else { ?>
            <div class="alert alert-danger">
                <?= THelper::t('user_info_not_found') ?>
            </div>
        <?php } ?>
    </div>
</div>
<?php if ($userFrom && $userTo) { ?>
    <div class="row">
        <div class="col-md-6">
            <input type="text" class="form-control money-transfer-moneys-number" placeholder="<?= THelper::t('money_transfer_moneys_number') ?>">
        </div>
        <div class="col-md-1 m-b">
            <a href="javascript:void(0);" class="btn btn-s-md btn-info money-transfer-send-money"><?= THelper::t('money_transfer_send_money') ?></a>
        </div>
        <div class="col-md-12">
            <div class="progress progress-sm progress-striped active progress-money-transfer" style="display: none;">
                <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
            </div>
            <div id="info-money-transfer" style="display: none;">
                <div class="alert alert-danger result-error" style="display: none;"></div>
                <div class="alert alert-success result-success" style="display: none;"><?= THelper::t('transfer_money_success') ?></div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    $('.money-transfer-send-money').click(function() {
        var moneys = $('.money-transfer-moneys-number').val();
        if (moneys && moneys > 0) {
            $('.progress-money-transfer').show();
            $.ajax({
                url: '/' + LANG + '/business/user/money-transfer-send',
                method: 'POST',
                data: {
                    u1: $('.user-from-login').html(),
                    u2: $('.user-to-login').html(),
                    money: $('.money-transfer-moneys-number').val()
                },
                success: function (data) {
                    if (data.success) {
                        $('#user-from-moneys').html(data.data.saldoFrom);
                        $('#user-to-moneys').html(data.data.saldoTo);
                        $('.result-error').hide();
                        $('.result-success').show();
                    } else {
                        $('.result-success').hide();
                        if (data.error) {
                            $('.result-error').html(data.error).show();
                        } else {
                            $('.result-error').html('').hide();
                        }
                    }
                    $('.progress-money-transfer').hide();
                    $('#info-money-transfer').show();
                }
            });
        } else {
            $('.result-error').hide();
            $('.result-success').hide();
        }
    });
</script>
