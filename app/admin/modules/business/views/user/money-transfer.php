<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('money_transfer_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="input-group m-b">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control money-transfer-user-from" placeholder="<?= THelper::t('money_transfer_from') ?>">
        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group m-b">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control money-transfer-user-to" placeholder="<?= THelper::t('money_transfer_to') ?>">
        </div>
    </div>
    <div class="col-md-1 m-b">
        <a href="javascript:void(0);" class="btn btn-s-md btn-info money-transfer-search-users"><?= THelper::t('money_transfer_search_users') ?></a>
    </div>
</div>
<div class="progress progress-sm progress-striped active" style="display: none;">
    <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
</div>
<div id="info" style="display: none;"></div>
<script>
    $('.money-transfer-search-users').click(function() {
        if ($('.money-transfer-user-from').val() && $('.money-transfer-user-to').val()) {
            $('#info').html('').hide();
            $('.progress').show();
            $.ajax({
                url: '/' + LANG + '/business/user/money-transfer',
                method: 'POST',
                data: {
                    u1: $('.money-transfer-user-from').val(),
                    u2: $('.money-transfer-user-to').val()
                },
                success: function (data) {
                    if (data) {
                        $('#info').html(data).show();
                    } else {
                        $('#info').hide();
                    }
                    $('.progress').hide();
                }
            });
        }
    });
</script>