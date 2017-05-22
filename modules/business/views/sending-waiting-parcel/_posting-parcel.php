<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\components\THelper;
use app\models\Warehouse;
use app\models\PartsAccessories;

$listWarehouse = Warehouse::getArrayWarehouse();
$listWarehouse = ArrayHelper::merge([''=>'Выберите склад'],$listWarehouse);

$listGoods = PartsAccessories::getListPartsAccessories();
$listGoods = ArrayHelper::merge([''=>'Выберите товар'],$listGoods);

?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Посылка № <?= (!empty($model->id) ? $model->id : '')?></h4>
        </div>


        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/sending-waiting-parcel/save-posting-parcel',
                    'options' => ['enctype' => 'multipart/form-data'],
                ]); ?>

                <?=Html::hiddenInput('id', (string)$model->_id)?>

                <?php if(!empty($model->part_parcel)) { ?>
                    <?php foreach($model->part_parcel as $item) { ?>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <?=Html::input('text','', $listGoods[$item['goods_id']],[
                                    'class'=>'form-control',
                                    'disabled'=>true,
                                ])?>
                                <?=Html::hiddenInput('complect[id][]', $item['goods_id'],[
                                    'class'=>'form-control',
                                    'placeholder'=>'Комментарий',
                                ])?>
                            </div>
                            <div class="col-md-2">
                                <?=Html::input('text','', $item['goods_count'] . ' шт',[
                                    'class'=>'form-control',
                                    'disabled'=>true
                                ])?>
                            </div>
                            <div class="col-md-6">
                                <?=Html::input('text','complect[comment][]', '',[
                                    'class'=>'form-control',
                                    'placeholder'=>'Комментарий',
                                ])?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success assemblyBtn']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
