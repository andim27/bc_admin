<?php
    use app\components\THelper;
    $this->title = THelper::t('charity_reports');
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12 document">
    <?php if ($reports) { ?>
        <?php foreach ($reports as $report) { ?>
            <article class="panel panel-default">
                <header class="panel-heading">
                    <h1 class="h1"><?= $report->title ?></h1>
                </header>
                <div class="panel-body">
                    <?php if ($report->dateOfPublication) { ?>
                        <div class="document-meta">
                            <p>
                                <?= THelper::t('publication_date') ?>:
                                <time pubdate><strong><?= gmdate('d-m-Y, H:i:s', $report->dateOfPublication) ?></strong></time>
                            </p>
                        </div>
                    <?php } ?>
                    <?= $report->body ?>
                </div>
            </article>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-warning">
            <?= THelper::t('charity_reports_is_not_created') ?>
        </div>
    <?php } ?>
</div>
