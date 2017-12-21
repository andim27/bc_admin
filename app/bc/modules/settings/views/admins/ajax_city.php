<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\components\THelper;
if(empty($model)){
    echo Html::label(THelper::t('city'), 'users-city_id');
}
?>
<select id="users-city_id" class="form-control size100p" name="Users[city_id]">
    <?php foreach($cities as $key=>$city) : ?>
        <option value="<?= $city->id ?>" <?= (isset($model->city_id) && $model->city_id == $city->id)? 'selected':'';  ?> "><strong><?= \app\components\THelper::t($city->title)?></strong> <?= ($city->state) ? '('.$city->state.')' : ''; ?></option>
    <?php endforeach; ?>
</select>