<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;
use app\components\THelper;

use app\models\PartsAccessories;

$listGoods = PartsAccessories::getListPartsAccessories();

$listComponent = PartsAccessories::getAllComponent((string)$model->parts_accessories_id,[]);

?>

<div class="modal-dialog modal-more-lg ">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Ремонт: <?=$listGoods[(string)$model->parts_accessories_id]?></h4>
        </div>

        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-posting-repair'
                ]); ?>

                <?=Html::hiddenInput('_id',(string)$model->_id);?>

                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-2">
                        На ремонте:
                    </div>
                    <div class="col-md-2">
                        <?=$model->number?>
                        <?=Html::hiddenInput('',$model->number,['class'=>'orderingExecution']);?>
                    </div>
                    <div class="col-md-2">
                        Оприходовано:
                    </div>
                    <div class="col-md-2">
                        <?=$model->received?>
                        <?=Html::hiddenInput('',$model->received,['class'=>'receivedExecution']);?>
                    </div>
                    <div class="col-md-2">
                        Отремонтировано:
                    </div>
                    <div class="col-md-2">
                        <?=Html::input('number','received','0',[
                            'class'=>'form-control postingExecution',
                            'pattern'=>'\d*',
                            'min'=>'1',
                            'max'=> ($model->number-$model->received),
                            'step'=>'1'
                        ])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-10">
                        <?=Select2::widget([
                            'name' => '',
                            'data' => $listComponent,
                            'options' => [
                                'class'=>'form-control',
                                'id'=>'selectGoods',
                                'placeholder' => 'Выберите комплектующую',
                                'multiple' => false
                            ]
                        ]);?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::button('<i class="fa fa-plus"></i>',[
                            'id' => 'addGoods',
                            'class'=>'btn btn-default btn-block',
                            'type'=>'button'])?>
                    </div>
                </div>


                <div class="form-group row">
                    <?php if(!empty($model)){ ?>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">Использовано для ремонта</div>
                                    </div>

                                    <div class="blUseComponents">
                                        <?php foreach($list_component as $items){ ?>

                                        <?php foreach($items as $k=>$item){ ?>
                                            <div class="form-group row">
                                                <div class="col-md-8">
                                                    <?=Html::hiddenInput('parts_accessories_id[]',(string)$item['parts_accessories_id'])?>
                                                    <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],['class'=>'form-control','disabled'=>'disabled']);?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?=Html::hiddenInput('number_use[]',$item['number']);?>
                                                    <?=Html::input('number','number[]',$item['number'],['class'=>'form-control']);?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-md-6 text-left">
                        <?php //= Html::a(THelper::t('disband_return'),['/business/submit-execution-posting/disband-return-execution','id'=>$model->_id->__toString()], ['class' => 'btn btn-success']); ?>
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
    $('#addGoods').on('click',function () {
        var flAddNow = 1;

        goodsID = $('#selectGoods :selected').val();
        goodsName = $('#selectGoods :selected').text();
        goodsCount = 0;

        if(goodsID==''){
            alert('Выберите товар!');
            flAddNow = 0;
        }


        $(".blUseComponents").find(".row").each(function () {
            if($(this).find('input[name="parts_accessories_id[]"]').val() == goodsID) {
                alert('Комплектующая уже использованна!');
                flAddNow = 0;
            }
        });

        if(flAddNow != 1){
            return;
        }

        $(".blUseComponents").append(
            '<div class="form-group row">' +
            '<div class="col-md-8">' +
                '<input type="hidden" name="parts_accessories_id[]" value="'+goodsID+'"  >' +
                '<input type="text" class="form-control" value="'+goodsName+'" disabled="disabled" >' +
            '</div>' +
            '<div class="col-md-4">' +
                '<input type="text" class="form-control" name="number[]"  value="'+goodsCount+'" min="0" step="0.01">' +
                '<input type="hidden" name="number_use[]"  value="0" >' +
            '</div>' +
            '</div>'
        );

    });
</script>