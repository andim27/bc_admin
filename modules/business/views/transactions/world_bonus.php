<?php
    use app\components\THelper;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('sidebar_world_bonus') ?></h3>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a data-toggle="tab" href="#home">Выплата</a></li>
            <li><a data-toggle="tab" href="#menu1">Таблица начислений</a></li>
        </ul>
        <div class="tab-content" style="padding:15px">
            <div id="home" class="tab-pane fade in active">
                <div class="panel panel-body">
                    <div class="row">
                        <div class="col-md-2 m-b-20">
                            <?= $this->render('/common/months', [
                                'id'  => 'month-from-pay',
                                'class' => 'form-control',
                                'currentMonth' => $month - 1
                            ]) ?>
                        </div>
                        <div class="col-md-2 m-b-20">
                            <?= $this->render('/common/years', [
                                'id'  => 'year-from-pay',
                                'class' => 'form-control',
                                'startYear' => 2017,
                                'endYear' => $year,
                                'currentYear' => $year
                            ]) ?>
                        </div>
                        <div class="col-md-3 m-b-20">
                            <input type="button" class="btn btn-success" id="get-world-bonus-pay-info" value="Показать">
                        </div>
                    </div>
                    <div class="progress-1 progress progress-sm progress-striped active" style="display: none;">
                        <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
                    </div>
                    <div id="world-bonus-pay-info"></div>
                </div>
            </div>
            <div id="menu1" class="tab-pane fade">
                <div class="panel panel-body">
                    <div class="row">
                        <div class="col-md-2 m-b-20">
                            <?= $this->render('/common/months', [
                                'id'  => 'month-from',
                                'class' => 'form-control',
                                'currentMonth' => $month - 2
                            ]) ?>
                        </div>
                        <div class="col-md-2 m-b-20">
                            <?= $this->render('/common/years', [
                                'id'  => 'year-from',
                                'class' => 'form-control',
                                'startYear' => 2017,
                                'endYear' => $year,
                                'currentYear' => $year
                            ]) ?>
                        </div>
                        <div class="col-md-2 m-b-20">
                            <?= $this->render('/common/months', [
                                'id'  => 'month-to',
                                'class' => 'form-control',
                                'currentMonth' => $month
                            ]) ?>
                        </div>
                        <div class="col-md-2 m-b-20">
                            <?= $this->render('/common/years', [
                                'id'  => 'year-to',
                                'class' => 'form-control',
                                'startYear' => 2017,
                                'endYear' => $year,
                                'currentYear' => $year
                            ]) ?>
                        </div>
                        <div class="col-md-3 m-b-20">
                            <input type="button" class="btn btn-success" id="get-world-bonus" value="Показать">
                        </div>
                    </div>
                    <div class="progress-2 progress progress-sm progress-striped active" style="display: none;">
                        <div class="progress-bar progress-bar-success" data-toggle="tooltip" style="width: 100%"></div>
                    </div>
                    <div id="world-bonus-table"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function loadWorldBonusData() {
        $('.progress-2').show();
        $('#world-bonus-table').html('');
        $.ajax({
            url: '/' + LANG + '/business/transactions/get-world-bonus',
            method: 'post',
            data: {
                from: $('#month-from').val() + '.' + $('#year-from').val(),
                to: $('#month-to').val() + '.' + $('#year-to').val()
            },
            success: function (data) {
                $('#world-bonus-table').html(data);
                $('.progress-2').hide();
            }
        });
    }

    function loadWorldBonusPayInfo() {
        $('.progress-1').show();
        $('#world-bonus-pay-info').html('');
        $.ajax({
            url: '/' + LANG + '/business/transactions/get-world-bonus-pay-info',
            method: 'post',
            data: {
                date: $('#month-from-pay').val() + '.' + $('#year-from-pay').val(),
            },
            success: function (data) {
                $('#world-bonus-pay-info').html(data);
                $('.progress-1').hide();
            }
        });
    }

    loadWorldBonusData();

    $('#get-world-bonus').click(function () {
        loadWorldBonusData();
    });

    $('#get-world-bonus-pay-info').click(function () {
        loadWorldBonusPayInfo();
    });

    loadWorldBonusPayInfo();
</script>
