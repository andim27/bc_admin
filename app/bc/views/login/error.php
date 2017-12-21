<?php
    use app\assets\AppAsset;
    use app\components\THelper;
    AppAsset::register($this);
?>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <div class="container aside-xxl">
        <a class="block logo_authorization" href="#"><?= $image ?></a>
        <section class="panel panel-default bg-white m-t-lg">
           <?php if (!empty($message)) { ?>
               <p class="text-center"><strong><?= $message ?></strong></p>
           <?php } ?>
        </section>
    </div>
</section>
<!-- footer -->
<footer id="footer">
    <div class="text-center padder">
        <p>
            <small><?= strtoupper(THelper::t('company_name')) ?><br>&copy; <?= $year ?></small>
        </p>
    </div>
</footer>
<?php $this->registerCssFile('css/main.css',['depends'=>['app\assets\AppAsset']]); ?>

