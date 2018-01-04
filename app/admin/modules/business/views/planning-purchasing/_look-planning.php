<?php
use app\components\THelper;
use yii\helpers\Html;
use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();

$idPopup = 'popup'.rand();

$sum = 0;
?>

<div class="modal-dialog modal-more-lg popupPlanning" id="<?=$idPopup?>">
    <div class="modal-content ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Планирование</h4>
        </div>

        <div class="modal-body">
            <div>

                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <?=Html::input('text','',$listGoods[(string)$model->parts_accessories_id],[
                                'class'=>'form-control',
                                'disabled' => true
                            ])?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::input('text','',$model->need_collect,[
                            'class'=>'form-control',
                            'disabled' => true
                        ])?>
                    </div>
                </div>

                <div class="form-group blPartsAccessories row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-2">На одну шт.</div>
                                        <div class="col-md-2">Цена за одну шт</div>
                                        <div class="col-md-2">Берем</div>
                                        <div class="col-md-2">Стоимость</div>
                                    </div>
                                    <?php if(!empty($model->complect)){ ?>
                                        <?php foreach($model->complect as $item){ ?>
                                            <?php $sum += $item['buy']; ?>
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <?=Html::input('text','',$listGoods[$item['parts_accessories_id']],['class'=>'form-control','disabled'=>true]);?>
                                                </div>
                                                <div class="col-md-2">
                                                    <?=Html::input('text','',$item['needForOne'],['class'=>'form-control','disabled'=>true]);?>
                                                </div>
                                                <div class="col-md-2">
                                                    <?=Html::input('text','',$item['priceForOne'],['class'=>'form-control','disabled'=>true]);?>
                                                </div>
                                                <div class="col-md-2">
                                                    <?=Html::input('text','',$item['buy'],['class'=>'form-control','disabled'=>true]);?>
                                                </div>
                                                <div class="col-md-2">
                                                    <?=Html::input('text','',($item['buy']*$item['priceForOne']),['class'=>'form-control','disabled'=>true]);?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                </div>


                <div class="row fullSumma form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">Итого:</div>
                    <div class="col-md-2">
                        <span class="eur"><?=$sum?></span> eur /
                    </div>
                    <div class="col-md-2">
                        <span class="usd"></span> usd /
                    </div>
                    <div class="col-md-2">
                        <span class="uah"></span> uah /
                    </div>
                    <div class="col-md-2">
                        <span class="rub"></span> rub
                    </div>
                </div>


                <div class="row form-group">
                    <div class="col-md-6 text-left">
                        <?= Html::a('Удалить заказ с таблицы',['remove-planning','id'=>(string)$model->_id],['class' => 'btn btn-success']) ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <?php //= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success assemblyBtn']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
