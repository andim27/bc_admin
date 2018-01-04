<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
?>

<?php $formCom = ActiveForm::begin([
    'action' => '/' . $language . '/business/setting/admin-warehouse-update',
    'options' => ['name' => 'saveWarehouseInfo',],
]); ?>

<?=Html::input('hidden','id',$model->_id)?>

<div class="row">
    <div class="col-md-12">
        <?=Html::label('Склад(ru)')?>
        <?=Html::input('text','warehouseName[ru]',(!empty($model->warehouseName['ru']) ? $model->warehouseName['ru'] : ''),['class'=>'form-control'])?>
    </div>


    <div class="col-md-12">
        <?=Html::label('Склад(en)')?>
        <?=Html::input('text','warehouseName[en]',(!empty($model->warehouseName['en']) ? $model->warehouseName['en'] : ''),['class'=>'form-control'])?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-right">
        <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
