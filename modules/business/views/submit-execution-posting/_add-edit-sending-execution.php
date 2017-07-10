<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\models\PartsAccessoriesInWarehouse;

$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

$listSuppliersPerformers=SuppliersPerformers::getListSuppliersPerformers();
$listSuppliersPerformers = ArrayHelper::merge([''=>'Выберите поставщика-испонителя'],$listSuppliersPerformers);

$canMake = 0;
if(!empty($model) && !empty($model->list_component)){
    $listComponents = [];
    foreach($model->list_component as $item){
        $listComponents[] = (string)$item['parts_accessories_id'];
    }
    $canMake = PartsAccessoriesInWarehouse::getHowMuchCanCollect((string)$model->parts_accessories_id,$listComponents);
}

$want_number = 0;
if(!empty($model)){
    $want_number = $model->number;
}

?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Отправка на исполнение</h4>
        </div>

        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-execution-posting',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(!empty($model) ? (string)$model->_id : ''));?>
                
                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-7">
                        <?=Html::dropDownList('parts_accessories_id',
                            (!empty($model) ?  $model->parts_accessories_id : ''),
                            ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessoriesWithComposite()),[
                            'class'=>'form-control',
                            'id'=>'selectGoods',
                            'required'=>'required',
                            'options' => [
                                '' => ['disabled' => true]
                            ],
                            'disabled' => (!empty($model) ?  true : false)
                        ])?>

                        <?=(!empty($model) ?  Html::hiddenInput('parts_accessories_id',(string)$model->parts_accessories_id) : '')?>
                    </div>
                    <div class="col-md-1">
                        можно собрать
                    </div>
                    <div class="col-md-2">
                        <?=Html::input('text','can_number',($canMake+$want_number),['class'=>'form-control CanCollect','disabled'=>'disabled'])?>
                    </div>
                    <div class="col-md-2">
                        <?=Html::input('number','want_number',$want_number,[
                            'class'=>'form-control WantCollect',
                            'pattern'=>'\d*',
                            'min'=>'1',
                            'step'=>'1',
                        ])?>
                    </div>


                </div>

                <div class="form-group blPartsAccessories row">
                    <?php if(!empty($model)){ ?>
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">На одну шт.</div>
                                    <div class="col-md-3">Надо отправить</div>
                                    <div class="col-md-3">С запасом</div>
                                </div>
                                <?php if(!empty($model->list_component)){ ?>
                                    <?php foreach($model->list_component as $item){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['parts_accessories_id']))) { ?>
                                                    <?=Html::dropDownList('complect[]','',
                                                        PartsAccessories::getInterchangeableList((string)$item['parts_accessories_id']),[
                                                            'class'=>'form-control',
                                                            'required'=>'required',
                                                            'options' => [
                                                            ]
                                                        ])?>

                                                <?php } else {?>
                                                    <?=Html::hiddenInput('complect[]',(string)$item['parts_accessories_id'],[]);?>
                                                    <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],['class'=>'form-control','disabled'=>'disabled']);?>

                                                <?php } ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                                                <?=Html::input('text','',$item['number'],['class'=>'form-control','disabled'=>'disabled']);?>
                                            </div>
                                            <div class="col-md-3">
                                                <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? ($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']] + ($item['number']*$want_number)) : 0 ),['class'=>'numberWarehouse']);?>
                                                <?=Html::input('text','',($item['number']*$want_number),['class'=>'form-control needSend','disabled'=>'disabled']);?>
                                            </div>
                                            <div class="col-md-3">
                                                <?=Html::input('number','reserve[]',(!empty($item['reserve']) ? $item['reserve'] : 0),[
                                                    'class'=>'form-control',
                                                    'pattern'=>'\d*',
                                                    'min' => '0',
                                                    'step'=>'1',
                                                ]);?>
                                            </div>

                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <div class="form-group row">
                    <div class="col-md-9">
                        <?=Html::label(THelper::t('sidebar_suppliers_performers'))?>
                        <?=Html::dropDownList('suppliers_performers_id',
                            (!empty($model) ? (string)$model->suppliers_performers_id : ''),
                            $listSuppliersPerformers,[
                                'class'=>'form-control',
                                'id'=>'selectChangeStatus',
                                'required'=>'required',
                                'options' => [
                                    '' => ['disabled' => true]
                                ]
                            ])?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::label(THelper::t('date_execution'))?>
                        <?=Html::input('text','date_execution',(!empty($model) ? $model->date_execution->toDateTime()->format('Y-m-d') : date('Y-m-d')),['class'=>'form-control datepicker-input','data-date-format'=>'yyyy-mm-dd'])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?=Html::label(THelper::t('fullname_whom_transferred'))?>
                        <?=Html::input('text','fullname_whom_transferred',(!empty($model->fullname_whom_transferred) ? $model->fullname_whom_transferred : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);?>
                    </div>
                </div>

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



<script>


    $(document).on('change','#selectGoods',function () {
        $.ajax({
            url: '<?=\yii\helpers\Url::to(['submit-execution-posting/kit-execution-posting'])?>',
            type: 'POST',
            data: {
                PartsAccessoriesId : $(this).val(),
            },
            success: function (data) {
                $('.blPartsAccessories').html(data);
            }
        });
    });

    $(".WantCollect").on('change',function(){
        wantC = parseInt($(this).val());
        canC = parseInt($('.CanCollect').val());

        $('.blPartsAccessories .row').each(function () {
           needNumber = $(this).find('input[name="number[]"]').val();
           $(this).find('.needSend').val(needNumber*wantC);
        });

        if(wantC>canC){
            $('.assemblyBtn').hide();
        } else {
            $('.assemblyBtn').show();
        }
    });

    $('.blPartsAccessories').on('change','input[name="reserve[]"]',function(){
        bl = $(this).closest('.row');

        inWarehouse = parseInt(bl.find('.numberWarehouse').val());
        need = parseInt(bl.find('.needSend').val());
        wantReserve = parseInt($(this).val());

        countInfo = inWarehouse - need - wantReserve;
        if(countInfo >= 0){
            $('.assemblyBtn').show();
        } else {
            $(".infoDanger").html(
                '<div class="alert alert-danger fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    'Такого количества нет на складе. Доступно ' + inWarehouse + 'шт.' +
                '</div>'
            );

            $('.assemblyBtn').hide();
        }
    })

    
</script>