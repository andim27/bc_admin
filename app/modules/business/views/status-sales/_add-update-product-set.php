<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\THelper;
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title"><?= THelper::t('sidebar_parts_accessories') ?></h4>
        </div>

        <div class="modal-body">
            <?php $formCom = ActiveForm::begin([
                'action' => '/' . $language . '/business/status-sales/save-product-set',
                'options' => ['name' => 'saveProduct'],
            ]); ?>

            <?=(!empty($model->_id) ? Html::hiddenInput('_id',$model->_id) : '')?>


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('product')?>
                        <?= Html::input('text','product',(!empty($model->product) ? $model->product : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('idInMarket')?>
                        <?= Html::input('text','idInMarket',(!empty($model->idInMarket) ? $model->idInMarket : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('productName')?>
                        <?= Html::input('text','productName',(!empty($model->productName) ? $model->productName : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('price')?>
                        <?= Html::input('text','price',(!empty($model->price) ? $model->price : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('bonusMoney')?>
                        <?= Html::input('text','bonusMoney',(!empty($model->bonusMoney) ? $model->bonusMoney : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('bonusPoints')?>
                        <?= Html::input('text','bonusPoints',(!empty($model->bonusPoints) ? $model->bonusPoints : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('bonusStocks')?>
                        <?= Html::input('text','bonusStocks',(!empty($model->bonusStocks) ? $model->bonusStocks : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('type')?>
                        <?= Html::input('text','bonusStocks',(!empty($model->type) ? $model->type : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <!-- TODO:KAA add module create pin -->

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('pinsVouchers')?>
                        <?= Html::textarea('pinsVouchers',(!empty($model->pinsVouchers) ? implode("\r\n",$model->pinsVouchers) : ''),[
                            'class'=>'form-control'
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::label('statusHide')?>
                        <?= Html::checkbox('statusHide',((!empty($model->statusHide) && $model->statusHide==1)? true : false),[
                            'class'=>''
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-right">
                    <?= Html::submitButton(THelper::t('settings_translation_edit_save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
