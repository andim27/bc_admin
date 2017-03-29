<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('users_info_title'); ?></h3>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="input-group m-b">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control search-info-user" placeholder="<?= THelper::t('user_info_search_placeholder') ?>">
        </div>
    </div>
    <div class="col-sm-1 m-b">
        <a href="javascript:void(0);" class="btn btn-s-md btn-info search-info"><?= THelper::t('user_info_search') ?></a>
    </div>
</div>
<div class="progress progress-sm progress-striped active" style="display: none;"> <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div> </div>
<div id="info" style="display: none;"></div>
<script>
    $('.search-info').click(function() {
        if ($('.search-info-user').val()) {
            $('#info').html('').hide();
            $('.progress').show();
            $.ajax({
                url: '/' + LANG + '/business/user/get-info',
                method: 'GET',
                data: {
                    u: $('.search-info-user').val()
                },
                success: function (data) {
                    if (data) {
                        $('#info').html(data).show();
                        sparkline(false);
                    } else {
                        $('#info').hide();
                    }
                    $('.progress').hide();
                }
            });
        }
    });

    var sr, sparkline = function($re){
        $('.sparkline-index').each(function(){
            var $data = $(this).data();
            if($re && !$data.resize) return;
            ($data.type == 'pie') && $data.sliceColors && ($data.sliceColors = eval($data.sliceColors));
            ($data.type == 'bar') && $data.stackedBarColor && ($data.stackedBarColor = eval($data.stackedBarColor));
            $data.valueSpots = {'0:': $data.spotColor};
            $(this).sparkline('html', $data);
        });
    };

    $(window).resize(function(e) {
        clearTimeout(sr);
        sr = setTimeout(function(){sparkline(true)}, 500);
    });
</script>