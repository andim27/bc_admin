<?php
    use yii\helpers\Html;
    use app\components\THelper;
    $this->title = THelper::t('certificate');
    $this->params['breadcrumbs'][] = $this->title;
?>

<script type='text/javascript'>
    $(function() {
        $(".print").on('click', function() {
            $.print("#printable");
        });
    });
</script>

<div class="container">
    <div class="raw">
        <?php if ($showCertificate) { ?>
            <img class="img-responsive" src="<?= $certificateUrl ?>" id="printable" />
            <br/>
            <?= Html::button(THelper::t('print'), ['class' => 'btn btn-primary print top15']) ?>
        <?php } else {
            echo THelper::t('certification_info');
        } ?>
    </div>
</div>

<?php $this->registerJsFile('js/jQuery.print.js', ['depends'=>['app\assets\AppAsset']]); ?>
