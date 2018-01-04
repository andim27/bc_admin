<?php
    use app\components\THelper;
    $this->title = THelper::t('geography_structure');
    $this->params['breadcrumbs'][] = $this->title;
?>
<section class="vbox bg-white" style="height: 94%">
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
<script>
    var action = 'geography';
</script>

<?php $this->registerJsFile('//maps.google.com/maps/api/js?v=3.1&sensor=true',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/maps/gmaps.js',['depends'=>['app\assets\AppAsset']]); ?>
<?php $this->registerJsFile('js/main/geography.js',['depends'=>['app\assets\AppAsset']]); ?>