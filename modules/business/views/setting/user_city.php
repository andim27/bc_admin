<?php

use yii\helpers\Html;
use app\components\THelper;

/* @var $model */
/* @var $mod */

?>
<div class="col-xs-3" style="width: 6%; text-align: right;padding-top: 6px;">
    <label class="control-label" for="select2-option"><?=THelper::t('city')?></label>
</div>

<div class="col-xs-3" style="width: 20%; padding-top: 6px;">
    <select id="select4-option" class="block" name="city_id">

        <?php foreach($model as $cit): ?>
            <option value="<?= $cit['id'] ?>" <?= ($cit['id'] == $mod)?'selected="selected"':''; ?>><?= $cit['title'] ?> <?= $cit['region'] ?></option>
        <?php endforeach; ?>
    </select>
</div>
<script>
    if ($.fn.select2) {
        $("#select4-option").select2({
            minimumInputLength: 3
        });
    }
</script>

<?php $this->registerJsFile('js/select2/select2.min.js',['depends'=>['app\assets\AppAsset']]); ?>
