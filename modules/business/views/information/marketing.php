<?php
    use app\components\THelper;
    $this->title = THelper::t('marketing_plan');
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12 document">
    <?php if ($model) : ?>
        <article class="panel panel-default">
            <header class="panel-heading">
                <h1 class="h1"><?= $model->title ?></h1>
            </header>
            <div class="panel-body o-a">
                <?= $model->body ?>
            </div>
        </article>
    <?php else: ?>
        <div class="alert alert-warning">
            <?= THelper::t('marketing_plan_is_not_created') ?>
        </div>
    <?php endif; ?>
</div>
<script>
    $('#content img').addClass('img-responsive').removeAttr('style');
</script>
