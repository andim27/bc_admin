<?php
    use app\components\THelper;

    $this->title = THelper::t('all_resources');
    $this->params['breadcrumbs'][] = $this->title;
?>
<?php foreach($resources as $resource) { ?>
    <div class="row m-b-md">
        <div class="col-sm-4 col-md-3 m-b-md">
            <?php if ($resource->url && $resource->img) { ?>
                <a onclick="window.open('<?= $resource->url ?>', '', 'toolbar=1,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=auto,height=auto');" href="javascript:void(0);"><img height="200" width="200" alt="Resource Logo" src="<?= $resource->img ?>"></a>
            <?php } ?>
        </div>
        <div class="col-sm-8 col-md-9 m-b-md">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        <span class="h4"><?= $resource->title ?></span>
                    </header>
                    <div class="slimScrollDiv">
                        <section style="overflow: hidden; width: auto; height: 100px;" class="panel-body slim-scroll">
                            <article class="media">
                                <div class="media-body">
                                    <?= $resource->body ?>
                                </div>
                            </article>
                        </section>
                    </div>
                </section>
            </div>
            <div class="col-sm-12 col-md-9 m-b-md">
                <?php if ($resource->url && $resource->img) { ?>
                    <a style="font-size: medium; color: blue" onclick="window.open('<?= $resource->url ?>', '', 'toolbar=1,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=auto,height=auto');" href="javascript:void(0);" class="" target="_blank"><?= $resource->url ?></a>
                <?php } ?>
            </div>
            <div class="col-sm-12 col-md-3 m-b-md">
                <?php if ($resource->url && $resource->img) { ?>
                    <a onclick="window.open('<?= $resource->url ?>', '', 'toolbar=1,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=auto,height=auto');" href="javascript:void(0);" class="btn btn-danger btn-rounded" target="_blank"><?= THelper::t('open_in_new_window') ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>

