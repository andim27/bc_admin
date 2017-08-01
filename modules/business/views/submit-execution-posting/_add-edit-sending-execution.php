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

    if($model->one_component != 1){
        $canMake = PartsAccessoriesInWarehouse::getHowMuchCanCollect((string)$model->parts_accessories_id,$listComponents);
    } else {
        $canMake = $listGoodsFromMyWarehouse[(string)$model->parts_accessories_id];
    }

}

$want_number = 0;
if(!empty($model)){
    $want_number = $model->number;
}

?>

<div class="modal-dialog modal-lg popupSendingExecution">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Отправка на исполнение</h4>
        </div>

        <div class="modal-body">

            <?php if(empty($model)){ ?>
            <div class="form-group row">
                <div class="col-md-offset-8 col-md-3 text-right"><?=THelper::t('send_one_component')?></div>
                <div class="col-md-1">
                    <?=Html::checkbox('',(!empty($model->one_component) ?  true : false ),['class'=>'flOneComponent'])?>
                </div>
            </div>
            <?php } ?>

            <div id="execution-posting" <?=((empty($model->one_component) || $model->one_component != 1) ? '' : 'style="display:none"')?>>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-execution-posting',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(!empty($model) ? (string)$model->_id : ''));?>
                <?=Html::hiddenInput('one_component',0);?>


                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-7">
                        <?=Html::dropDownList('parts_accessories_id',
                            (!empty($model) ?  $model->parts_accessories_id : ''),
                            ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessoriesWithComposite()),[
                            'class'=>'form-control withComposite',
                            'id'=>'selectGoods',
                            'required'=>true,
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
                                <div class="form-group row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-2">В наличие</div>
                                    <div class="col-md-2">У исполнителя</div>
                                    <div class="col-md-1">На одну шт.</div>
                                    <div class="col-md-2">Надо отправить</div>
                                    <div class="col-md-1">С запасом</div>
                                </div>
                                <?php if(!empty($model->list_component)){ ?>
                                    <?php foreach($model->list_component as $item){ ?>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <?php if(!empty(PartsAccessories::getInterchangeableList((string)$item['parts_accessories_id']))) { ?>
                                                    <?=Html::dropDownList('complect[]','',
                                                        PartsAccessories::getInterchangeableList((string)$item['parts_accessories_id']),[
                                                            'class'=>'form-control partTitle',
                                                            'required'=>'required',
                                                            'options' => [
                                                            ]
                                                        ])?>

                                                <?php } else {?>
                                                    <?=Html::hiddenInput('complect[]',(string)$item['parts_accessories_id'],[]);?>
                                                    <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],[
                                                        'class'             =>'form-control partTitle',
                                                        'disabled'          =>true,
                                                        'data-placement'    => 'left',
                                                        'title'             => $listGoods[(string)$item['parts_accessories_id']]
                                                        ]);?>

                                                <?php } ?>
                                            </div>
                                            <div class="col-md-2">
                                                <?=Html::input('text',
                                                    '',
                                                    (!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? ($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']] + ($item['number']*$want_number)) : 0 ),
                                                    ['class'=>'form-control inWarehouse','disabled'=>'disabled']);?>
                                            </div>

                                            <div class="col-md-2">
                                                <?=Html::hiddenInput('contractor[]',
                                                    (!empty($contractorInfo[(string)$item['parts_accessories_id']]) ? $contractorInfo[(string)$item['parts_accessories_id']] : '0'),
                                                    []); ?>
                                                <?=Html::input('text','',
                                                    (!empty($contractorInfo[(string)$item['parts_accessories_id']]) ? $contractorInfo[(string)$item['parts_accessories_id']] : '0'),
                                                    ['class'=>'form-control partContractor','disabled'=>'disabled']); ?>
                                            </div>

                                            <div class="col-md-1">
                                                <?=Html::hiddenInput('number[]',$item['number'],[]);?>
                                                <?=Html::input('text','',$item['number'],['class'=>'form-control partNeedForOne','disabled'=>'disabled']);?>
                                            </div>
                                            <div class="col-md-2">
                                                <?=Html::hiddenInput('',(!empty($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']]) ? ($listGoodsFromMyWarehouse[(string)$item['parts_accessories_id']] + ($item['number']*$want_number)) : 0 ),['class'=>'numberWarehouse']);?>
                                                <?=Html::input('text','',($item['number']*$want_number),['class'=>'form-control needSend','disabled'=>'disabled']);?>
                                            </div>
                                            <div class="col-md-2">
                                                <?=Html::input('number','reserve[]',(!empty($item['reserve']) ? $item['reserve'] : 0),[
                                                    'class'=>'form-control partNeedReserve',
                                                    'pattern'=>'\d*',
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
                        <?=Html::input('text','date_execution',(!empty($model->date_execution) ? $model->date_execution->toDateTime()->format('Y-m-d') : date('Y-m-d')),['class'=>'form-control datepicker-input','data-date-format'=>'yyyy-mm-dd'])?>
                    </div>
                </div>

                <div class="form-group row blFullnameWhomTransferred">
                    <div class="col-md-12">
                        <?=Html::label(THelper::t('fullname_whom_transferred'))?>
                        <?=Html::input('text','fullname_whom_transferred',(!empty($model->fullname_whom_transferred) ? $model->fullname_whom_transferred : ''),[
                            'class'=>'form-control',
                            'required'=>true,
                        ]);?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 text-left">
                        <?= Html::button(THelper::t('print'), ['class' => 'btn btn-success btnPrint','type'=>'button']) ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success assemblyBtn']) ?>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>

            <div id="send-posting" <?=((!empty($model->one_component) && $model->one_component == 1) ? '' : 'style="display:none"')?>>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-execution-posting-replacement',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(!empty($model) ? (string)$model->_id : ''));?>
                <?=Html::hiddenInput('one_component',1);?>

                <div class="form-group row infoDanger"></div>



                <div class="form-group row">
                    <div class="col-md-7">

                        <?=Html::dropDownList('parts_accessories_id',
                            (!empty($model) ?  $model->parts_accessories_id : ''),
                            ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessoriesWithoutComposite()),[
                                'class'=>'form-control withoutComposite',
                                'id'=>'selectOneGoods',
                                'required'=>true,
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

                <div class="form-group row">
                    <div class="col-md-12">
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



<script type="text/javascript">

    listGoodsFromMyWarehouse = <?=json_encode($listGoodsFromMyWarehouse)?>;

    $(document).on('change','#selectGoods',function () {
        blForm = $(this).closest('form');

        $.ajax({
            url: '<?=\yii\helpers\Url::to(['submit-execution-posting/kit-execution-posting'])?>',
            type: 'POST',
            data: {
                PartsAccessoriesId : $(this).val(),
            },
            success: function (data) {
                blForm.find('.blPartsAccessories').html(data);
            }
        });
    });


    $(".WantCollect").on('change',function(){

        blForm = $(this).closest('form');

        wantC = parseFloat($(this).val());
        canC = parseFloat(blForm.find('.CanCollect').val());

        blForm.find('.blPartsAccessories .row').each(function () {
           needNumber = $(this).find('input[name="number[]"]').val();
           $(this).find('.needSend').val((needNumber*wantC).toFixed(2));
        });

        if(wantC>canC){
            blForm.find('.assemblyBtn').hide();
        } else {
            blForm.find('.assemblyBtn').show();

            checkReserve();
        }
    });

    $(".partNeedReserve").on("change",function () {
        checkReserve();
    });

    function checkReserve() {
        $(".infoDanger").html('');

        $('.blPartsAccessories .row').each(function () {
            needSend = parseInt($(this).find('.needSend').val());
            needReserve = parseInt($(this).find('.partNeedReserve').val());

            if((needReserve + needSend) < 0){
                $(".infoDanger").html(
                    '<div class="alert alert-danger fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    'Резерв не может быть меньше чем нужно' +
                    '</div>'
                );
            }

        });
    }

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
    });

    $("#execution-posting .btnPrint").on('click', function() {

        tempBl = '';
        $(".popupSendingExecution #execution-posting .blPartsAccessories").find('.form-group.row').each(function () {
            title = $(this).find('.partTitle :selected').text();
            if(title == ''){
                title = $(this).find('.partTitle').val();
            }
            tempBl +=
                '<tr>' +
                '<td>'+  title +
                '<td>'+  $(this).find('.numberWarehouse').val() +
                '<td>'+ $(this).find('.partNeedForOne').val() +
                '<td>'+ $(this).find('.needSend').val() +
                '<td>'+ $(this).find('.partNeedReserve').val();
        });

        printFile =
            '<table>' +
                '<tr>' +
                    '<th colspan="5">Отправка на исполнение' +
                '<tr>' +
                    '<td><b>Собираем<b>'+
                    '<td colspan="4">' + $(".popupSendingExecution #execution-posting select[name='parts_accessories_id'] :selected").text() +
                '<tr>' +
                    '<td><b>Можно собрать<b>'+
                    '<td colspan="4">' + $(".popupSendingExecution #execution-posting input[name='can_number']").val()  + ' шт.' +
                '<tr>' +
                    '<td><b>Количество<b>'+
                    '<td colspan="4">' + $(".popupSendingExecution #execution-posting input[name='want_number']").val() + ' шт.' +
                '<tr>' +
                    '<th colspan="5">Необходимо:' +
                '<tr>' +
                    '<td> Коплектующая' +
                    '<td> В наличие' +
                    '<td> Нужно на одну' +
                    '<td> Нужно отправить' +
                    '<td> Запас' +

                tempBl +
    
                '<tr>' +
                    '<td><b>Кому выдано<b>'+
                    '<td colspan="4">' + $(".popupSendingExecution #execution-posting input[name='fullname_whom_transferred']").val() +
    
                '<tr>' +
                    '<td><b>Поставщики и исполнители<b>'+
                    '<td colspan="4">' + $(".popupSendingExecution #execution-posting select[name='suppliers_performers_id'] :selected").text() +
        
                '<tr>' +
                    '<td><b>Дата исполнения<b>'+
                    '<td colspan="4">' + $(".popupSendingExecution #execution-posting input[name='date_execution']").val() +

            '</table>';

        $.print(printFile,{
            stylesheet : window.location.origin + '/css/print.css'
        });
    });
    
    $('.popupSendingExecution .flOneComponent').on('change', function () {
        if($(this).is(':checked')){
            $('#execution-posting').hide();
            $('#send-posting').show();
        } else{
            $('#send-posting').hide();
            $('#execution-posting').show();
        }

    });
    
    $('#selectOneGoods').on('change',function () {
        countGoods = listGoodsFromMyWarehouse[$(this).val()];

        if(countGoods == undefined){
            countGoods = 0;
        }

        $('#send-posting .CanCollect').val(countGoods);
    })
    
</script>