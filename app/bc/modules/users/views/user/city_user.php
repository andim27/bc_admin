<?php
use yii\helpers\Html;
use app\components\THelper;
?>
<?= Html::label(THelper::t('city'), 'select3-option') ?>
<select id="select3-option" class="block" name="city_id">

    <?php foreach($model as $city): ?>
        <option value="<?= $city['id'] ?>"><?= $city['region'] ?> <?= $city['title'] ?></option>
    <?php endforeach; ?>
</select>

<script>
    if ($.fn.select2) {
        $("#select3-option").select2({
            minimumInputLength: 3
        });
    }
</script>

<?php $this->registerJsFile('js/select2/select2.min.js',['depends'=>['app\assets\AppAsset']]); ?>
