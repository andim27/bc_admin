<?php
    use app\components\THelper;
    $this->title = THelper::t('statistic');
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <h4><?= THelper::t('statistic_of_your_business') ?></h4>
    </div>
    <div class="col-sm-12">
        <div class="form-group wrapper m-b-none">
            <section class="panel panel-default">
                <div class="row m-l-none m-r-none bg-light lter">
                    <div class="col-sm-4 padder-v b-r b-light">
                        <span class="fa-stack fa-2x pull-left m-r-sm">
                            <i class="fa fa-circle fa-stack-2x text-info" style="color: #4c6cc1"></i>
                            <i class="fa fa-calendar fa-stack-1x text-white"></i>
                        </span>
                        <div class="clear">
                            <span class="h3 block m-t-xs"><strong><?=$data['in_busines']?></strong></span>
                            <small class="text-muted text-uc"><?= THelper::t('days_in_the_business') ?></small>
                        </div>
                    </div>
                    <div class="col-sm-4 padder-v b-r b-light">
                        <span class="fa-stack fa-2x pull-left m-r-sm">
                            <i class="fa fa-circle fa-stack-2x text-info" style="color: #4cc0c1"></i>
                            <i class="fa fa-group fa-stack-1x text-white"></i>
                        </span>
                        <div class="clear">
                            <span class="h3 block m-t-xs"><strong><?=$data['registrations']?></strong></span>
                            <small class="text-muted text-uc"><?= THelper::t('registrations_in_the_structure') ?></small>
                        </div>
                    </div>
                    <div class="col-sm-4 padder-v b-r b-light">
                        <span class="fa-stack fa-2x pull-left m-r-sm">
                            <i class="fa fa-circle fa-stack-2x text-info" style="color: #c14cba"></i>
                            <i class="fa fa-male fa-stack-1x text-white"></i>
                        </span>
                        <div class="clear">
                            <span class="h3 block m-t-xs"><strong><?= $data['partners'] ?></strong></span>
                            <small class="text-muted text-uc"><?= THelper::t('statistic_partners') ?></small>
                        </div>
                    </div>
                </div>
            </section>
            <section class="panel panel-default" style="margin-top: -30px">
                <div class="row m-l-none m-r-none bg-light lter">
                    <div class="col-sm-4 padder-v b-r b-light">
                        <span class="fa-stack fa-2x pull-left m-r-sm">
                            <i class="fa fa-circle fa-stack-2x text-info" style="color: #ffe00e"></i>
                            <i class="fa fa-magnet fa-stack-1x text-white"></i>
                        </span>
                        <div class="clear">
                            <span class="h3 block m-t-xs"><strong><?=$data['self_recommendations']?></strong></span>
                            <small class="text-muted text-uc"><?= THelper::t('self_recommendations') ?></small>
                        </div>
                    </div>
                    <div class="col-sm-4 padder-v b-r b-light">
                        <span class="fa-stack fa-2x pull-left m-r-sm">
                            <i class="fa fa-circle fa-stack-2x text-info" style="color: #c14d4c"></i>
                            <i class="fa fa-briefcase fa-stack-1x text-white"></i>
                        </span>
                        <div class="clear">
                            <span class="h3 block m-t-xs"><strong><?=$data['self_partners']?></strong></span>
                            <small class="text-muted text-uc"><?= THelper::t('self_partners') ?></small>
                        </div>
                    </div>
                    <div class="col-sm-4 padder-v b-r b-light">
                        <span class="fa-stack fa-2x pull-left m-r-sm">
                            <i class="fa fa-circle fa-stack-2x text-info" style="color: #61c14c"></i>
                            <i class="fa fa-money fa-stack-1x text-white"></i>
                        </span>
                        <div class="clear">
                            <span class="h3 block m-t-xs"><strong><?=$data['total_earned']?></strong></span>
                            <small class="text-muted text-uc"><?= THelper::t('total_earned') ?></small>
                        </div>
                    </div>
                </div>
            </section>
          </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group wrapper m-b-none">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?=THelper::t('schedule_of_structure')?></header>
                <div class="panel-body">
                    <div id="flot-chart" style="text-align: center;">
                        <i class="fa fa-5x fa-spinner fa-spin"></i>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group wrapper m-b-none">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?=THelper::t('schedule_of_checks')?></header>
                <div class="panel-body">
                    <div id="flot-income" style="text-align: center;">
                        <i class="fa fa-5x fa-spinner fa-spin"></i>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group wrapper m-b-none">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><?=THelper::t('schedule_of_income_statistic')?></header>
                <div class="panel-body">
                    <div id="flot-income-statistic" style="text-align: center;">
                        <i class="fa fa-5x fa-spinner fa-spin"></i>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group wrapper m-b-none">
            <h4><?= THelper::t('map_of_structure') ?></h4>
            <section class="vbox bg-white" style="height: 600px">
                <header class="header b-b">
                    <form method="post" id="geocoding_form" class="input-s m-t-sm m-b-none pull-right">
                        <div class="input-group">
                            <input type="text" id="address" name="address" class="input-sm form-control" placeholder="<?=THelper::t('search')?>">
                <span class="input-group-btn">
                  <button class="btn btn-sm btn-default" type="submit"><?=THelper::t('go')?></button>
                </span>
                        </div>
                    </form>
                    <p><?=THelper::t('google_maps')?></p>
                </header>
                <section id="gmap_geocoding" style="min-height:240px;"></section>
            </section>
            <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
        </div>
    </div>
</div>
<script>
    var months = {
        "01":"<?=tHelper::t('january')?>",
        "02":"<?=tHelper::t('february')?>",
        "03":"<?=tHelper::t('march')?>",
        "04":"<?=tHelper::t('april')?>",
        "05":"<?=tHelper::t('may')?>",
        "06":"<?=tHelper::t('june')?>",
        "07":"<?=tHelper::t('july')?>",
        "08":"<?=tHelper::t('august')?>",
        "09":"<?=tHelper::t('september')?>",
        "10":"<?=tHelper::t('october')?>",
        "11":"<?=tHelper::t('november')?>",
        "12":"<?=tHelper::t('december')?>"
    };
    var labelPaid              = "<?= tHelper::t('paid') ?>";
    var labelRegistrations     = "<?= tHelper::t('registrations') ?>";
    var labelCheck             = "<?= tHelper::t('check') ?>";
    var labelProjectedIncoming = "<?= tHelper::t('projected_incoming') ?>";
    var labelIncoming          = "<?= tHelper::t('incoming') ?>";
    var user_id                = "<?= $data['user_id'] ?>";

    var registrationsStatisticsPerMoths = <?= $registrationsStatisticsPerMoths ?>;
    var incomeStatisticsPerMoths = <?= $incomeStatisticsPerMoths ?>;
    var checksStatisticsPerMoths = <?= $checksStatisticsPerMoths ?>;
    var user = <?= $user ?>;
    var action = 'statistic';
</script>

<?php $this->registerJsFile('js/main/flot_graph.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('//maps.google.com/maps/api/js?sensor=true',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/maps/gmaps.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/geography.js',['depends'=>['app\assets\AppAsset']]); ?>
