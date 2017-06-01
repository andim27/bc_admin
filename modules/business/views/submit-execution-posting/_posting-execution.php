<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\components\THelper;

use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();


?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Оприходование: <?=$listGoods[(string)$model->parts_accessories_id]?></h4>
        </div>

        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-posting-execution',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(string)$model->_id);?>

                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-3">
                        Заказано:
                    </div>
                    <div class="col-md-3">
                        <?=$model->number?>
                        <?=Html::hiddenInput('',$model->number,['class'=>'orderingExecution']);?>
                    </div>
                    <div class="col-md-3">
                        Оприходованно:
                    </div>
                    <div class="col-md-3">
                        <?=$model->received?>
                        <?=Html::hiddenInput('',$model->received,['class'=>'receivedExecution']);?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        Пришло:
                    </div>
                    <div class="col-md-6">
                        <?=Html::input('number','received','0',[
                            'class'=>'form-control postingExecution',
                            'pattern'=>'\d*',
                            'min'=>'1',
                            'step'=>'1'
                        ])?>
                    </div>
                </div>

                <div class="form-group blPartsAccessories row">
                    <?php if(!empty($model)){ ?>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">Запас был</div>
                                    </div>
                                    <?php if(!empty($model->list_component)){ ?>
                                        <?php foreach($model->list_component as $item){ ?>
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],['class'=>'form-control','disabled'=>'disabled']);?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?=Html::input('text','',($item['number']*$model->number),['class'=>'form-control needSend','disabled'=>'disabled']);?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?=Html::input('text','',$item['reserve'],['class'=>'form-control needSend','disabled'=>'disabled']);?>
                                                </div>

                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-md-6 text-left">
                        <?= Html::a(THelper::t('disband_return'),['/business/submit-execution-posting/disband-return-execution','id'=>$model->_id->__toString()], ['class' => 'btn btn-success']) ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <?= Html::submitButton(THelper::t('posting'), ['class' => 'btn btn-success assemblyBtn']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('.postingExecution').on('change',function(){

        orderingExecution = parseInt($('.orderingExecution').val());
        receivedExecution = parseInt($('.receivedExecution').val());
        wantPosting = parseInt($(this).val());

        countInfo = orderingExecution - receivedExecution - wantPosting;
        if(countInfo >= 0){
            $('.assemblyBtn').show();
        } else {
            $(".infoDanger").html(
                '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                'Такое количество оприходовать нельзя. Доступно ' + (orderingExecution - receivedExecution) + 'шт.' +
                '</div>'
            );

            $('.assemblyBtn').hide();
        }
    })
</script>