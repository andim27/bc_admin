<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\PartsAccessories;
use app\models\SuppliersPerformers;
use app\models\PartsAccessoriesInWarehouse;
use kartik\widgets\Select2;


$listGoodsFromMyWarehouse = PartsAccessoriesInWarehouse::getCountGoodsFromMyWarehouse();

$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

$listSuppliersPerformers=SuppliersPerformers::getListSuppliersPerformers();
$listSuppliersPerformers = ArrayHelper::merge([''=>'Выберите поставщика-испонителя'],$listSuppliersPerformers);

$canMake = 0;
if(!empty($model) && !empty($model->suppliers_performers_id)){

    if($model->one_component != 1){
        $canMake = PartsAccessoriesInWarehouse::getHowMuchCanCollectWithInterchangeable((string)$model->parts_accessories_id,$model->suppliers_performers_id);
    } else {
        $canMake = $listGoodsFromMyWarehouse[(string)$model->parts_accessories_id];
    }

}

$want_number = 0;
if(!empty($model)){
    $want_number = $model->number;
}

?>
<style>
    .partNoneComplect {
        display: none;
        border-bottom-color: red;
        color: red;
    }
    .none-complect-ch-row {
        display: none;
    }
</style>
<div class="modal-dialog modal-more-lg popupSendingExecution">
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
            <div class="form-group row none-complect-ch-row">
                <div class="col-md-offset-8 col-md-3 text-right"><?=THelper::t('non_complect')?></div>
                <div class="col-md-1">
                    <?=Html::checkbox('none-complect-ch', false ,['id'=>'none-complect-ch'])?>
                </div>
            </div>
            <div class="form-group row none-complect-count">
                <div class="col-md-offset-10 col-md-2 text-left"><span><a onclick="showNkParts();" title="Показать н.к детали"> Н.к деталей - <span id="none-complect-count-val">0</span></a></span></div>
            </div>
            <?php } ?>

            <!--kit product-->
            <div id="execution-posting" <?=((empty($model->one_component) || $model->one_component != 1) ? '' : 'style="display:none"')?>>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-execution-posting',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(!empty($model) ? (string)$model->_id : ''));?>
                <?=Html::hiddenInput('one_component',0);?>
                <?=Html::hiddenInput('none-complect-ch-val',0);?>

                <div class="form-group row infoDanger"></div>

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

                <div class="form-group row">
                    <div class="col-md-7">
                        <?=Html::dropDownList('parts_accessories_id',
                            (!empty($model) ?  $model->parts_accessories_id : ''),
                            ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessoriesWithComposite()),[
                                'class'=>'form-control withComposite',
                                'id'=>'selectGoods',
                                'required'=>true,
                                'disabled' => true,
                                'options' => [
                                    '' => ['disabled' => true]
                                ]
                            ])?>
                        <?=(!empty($model) ?  Html::hiddenInput('parts_accessories_id',(string)$model->parts_accessories_id) : '')?>
                    </div>
                    <div class="col-md-2">
                       <label for="p-lang">Паспорт</label>
                        <select id="p-lang">
                            <option value="all">Все</option>
                            <option value="rus">Рус</option>
                            <option value="eng">Англ</option>
                            <option value="lat">Латв</option>
                            <option value="eng_rus">Англ-Рус</option>
                            <option value="eng_lat">Англ-Латв</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        можно собрать
                    </div>
                    <div class="col-md-1">
                        <?=Html::input('text','can_number',($canMake+$want_number),['class'=>'form-control CanCollect','disabled'=>'disabled'])?>
                    </div>
                    <div class="col-md-1">
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
                                    <div class="col-md-7"></div>
                                    <div class="col-md-1">В наличие</div>
                                    <div class="col-md-1">У исполнителя</div>
                                    <div class="col-md-1">На одну шт.</div>
                                    <div class="col-md-1">Надо отправить</div>
                                    <div class="col-md-1">С запасом</div>
                                </div>
                                <?php if(!empty($list_component)){ ?>
                                    <?php foreach($list_component as $k=>$item){ ?>
                                        <?php if(count($item)>1) {?>
                                            <?= $this->render('__line_interchangeable_component',[
                                                'items'         => $item,
                                                'k'             => $k,
                                                'performerId'   => $model->suppliers_performers_id,
                                            ]); ?>
                                        <?php } else { ?>
                                            <?= $this->render('__line_component',[
                                                'item'          => $item['0'],
                                                'performerId'   => $model->suppliers_performers_id,
                                                'want_number'   => $want_number
                                            ]); ?>
                                        <?php } ?>                                        
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
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
                        <?php if(empty($model)){?>
                            <div id="none-complect-items">

                            </div>
                        <?= Html::submitButton(THelper::t('save'), ['class' => 'btn btn-success assemblyBtn']) ?>
                        <?php } ?>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>

            <!--single product-->
            <div id="send-posting" <?=((!empty($model->one_component) && $model->one_component == 1) ? '' : 'style="display:none"')?>>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-execution-posting-replacement',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(!empty($model) ? (string)$model->_id : ''));?>
                <?=Html::hiddenInput('one_component',1);?>

                <div class="form-group row infoDanger"></div>



                <div class="form-group row blUnique">
                   <!-- add-edit-sending-exec-->
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
                        <div id="none-complect-items">

                        </div>
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

    $(document).on('change','#selectGoods,#p-lang',function () {
        blForm = $('#selectGoods').closest('form');
        $('.blPartsAccessories').html('');
        $.ajax({
            url: '<?=\yii\helpers\Url::to(['submit-execution-posting/kit-execution-posting'])?>',
            type: 'POST',
            data: {
                partsAccessoriesId  : $('#selectGoods').val(),
                performerId         : $('#selectChangeStatus').val(),
                p_lang              : $('#p-lang').val(),
            },
            success: function (data) {
                blForm.find('.blPartsAccessories').html(data);
            }
        });
    });
    function isInt(n)
    {
        return ((n != "" && !isNaN(n)) && (Math.round(n) == n));
    }
    function isFloat (x) {
        return !!(x % 1); //return n != "" && !isNaN(n) && Math.round(n) != n;

    }
    $(".WantCollect").on('change',function(){
        $('#none-complect-items').html("");
        $('#none-complect-count-val').html($("#none-complect-items").children().length);
        blForm = $(this).closest('form');
        $('.assemblyBtn').show();
        wantC = parseFloat($(this).val());
        canC = parseFloat(blForm.find('.CanCollect').val());
        if (wantC > canC) {
            nonComplectCells('show');
        } else {
            nonComplectCells('hide');
            //return;
        }
        blForm.find('.blPartsAccessories .row').each(function () {
            partNeedForOne = $(this).find('.partNeedForOne').val();
           $(this).find('.needSend').val((partNeedForOne*wantC).toFixed(2));
        });
        //--fill Interchangeable--
        blForm.find('.blInterchangeable .row').each(function () {
            partNeedForOneInterchangeable = $(this).find('.partNeedForOneInterchangeable').val();
            //$(this).find('.needSendInterchangeable').val((parseInt(partNeedForOneInterchangeable*wantC)));
            $(this).find('.needSendInterchangeable').val(0);
        });
        //--none complect--
        blForm.find('.form-group .row').each(function () {
            if ($(this).find('input.partTitle').val() != undefined) {

                noneVal=undefined;
                part_id = $(this).find('input.partTitle').attr('data-part_id');
                //--inWarehouseInterchangeable--
                partNeedForOne  = parseInt($(this).find('.partNeedForOneInterchangeable').val());
                partNeedForSend = parseInt($(this).find('.needSendInterchangeable').val());
                partNeedForSendAll = parseInt($(this).find('.needSendInterchangeable').val());//partNeedForSend*partNeedForOne;
                inwhInter = $(this).find('input.inWarehouseInterchangeable').val();//--inWarehouseInterchangeable inWarwhouse
                if (inwhInter != undefined) {
                    console.log('(inwhInter) yes inwhInter=' + inwhInter + '  wantC=' + wantC+ ' partNeedForSend=' + partNeedForSend+' partTitle='+$(this).find('input.partTitle').val());
                    if (isFloat(inwhInter)) {
                        inwhInter = parseFloat(inwhInter);
                        partNeedForOne  = parseFloat($(this).find('.partNeedForOneInterchangeable').val());
                        partNeedForSend = parseFloat($(this).find('.needSendInterchangeable').val());
                        partNeedForSendAll = partNeedForSend;//partNeedForSend*partNeedForOne;
                        if (inwhInter < partNeedForSendAll) {
                            noneVal = partNeedForSendAll - inwhInter;
                            console.log('(inwhInter)FLOAT-LESS yes inwhInter='+inwhInter+'< LESS! partNeedForOne=' + partNeedForOne +' partNeedForSendAll='+partNeedForSendAll+' noneVal='+noneVal);
                            $(this).find('input.partNoneComplect').val(noneVal);

                        }
                    } else {
                        if (inwhInter == 0) {
                            noneVal =parseInt(partNeedForOne * wantC);
                            console.log('(inwhInter)INT-0 yes >'+noneVal+'< ZERO! partNeedForOne=' + partNeedForOne +' noneVal='+noneVal);

                            $(this).find('input.partNoneComplect').val(noneVal);
                        }
                        if (inwhInter < partNeedForSend) {
                            noneVal = parseInt(partNeedForOne * wantC) - inwhInter;
                            console.log('(inwhInter)INT-LESS yes >'+noneVal+'< LESS! partNeedForOne=' + partNeedForOne  + wantC+' noneVal='+noneVal);
                            $(this).find('input.partNoneComplect').val(noneVal);

                        }
                    }

                }
                //--inWarehouse--
                inwh = $(this).find('input.inWarehouse').val();
                partNeedForOne = parseInt($(this).find('.partNeedForOne').val());
                partNeedForSend = parseInt($(this).find('.needSend').val());
                partNeedForSendAll = parseInt($(this).find('.needSend').val());//partNeedForSend*partNeedForOne;
                if (inwh != undefined) {
                    if (isFloat(inwh)) {
                        inwh            = parseFloat($(this).find('input.inWarehouse').val());
                        partNeedForOne  = parseFloat($(this).find('.partNeedForOne').val());
                        partNeedForSend = parseFloat($(this).find('.needSend').val());
                        partNeedForSendAll = partNeedForSend;//partNeedForSend*partNeedForOne;
                        if (inwh < partNeedForSendAll) {
                            noneVal = partNeedForSendAll - inwh;
                            console.log('FLOAT yes >'+noneVal+'< LESS! partNeedForSendAll=' + partNeedForSendAll + '  wantC=' + wantC+' noneVal='+noneVal);
                            $(this).find('input.partNoneComplect').val(noneVal);
                        }
                    } else {
                        if (inwh < partNeedForSendAll) {
                            noneVal = partNeedForSendAll - inwh;
                            console.log('(inwh)INT LESS yes inWarehouse >'+noneVal+'<LESS! partNeedForOne=' + partNeedForOne + '  wantC=' + wantC + ' inWareHouse=' + inwh+' partTitle='+$(this).find('input.partTitle').val());
                            $(this).find('input.partNoneComplect').val(noneVal);
                        }
                    }

                }
                console.log('NONEVAL='+noneVal+' part_id='+part_id);
                if ( (wantC > canC)&&(part_id != undefined)&&(noneVal != undefined)) {
                    $('#none-complect-items').append("<input type='hidden' id='itemNoneComplect-"+part_id+"' name='arrayNoneComplect["+part_id+"]' value='"+noneVal+"' />");
                    $('#none-complect-count-val').html($("#none-complect-items").children().length);
                }
            }
        });
        //checkBeforeSend();

    });

    $(".partNeedReserve").on("change",function () {
        checkBeforeSend();
    });

    function checkReserve() {
        answer = 1;
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
                answer = 0;
            }

        });

        return answer;
    }

    function checkInterchangeable(){
        answer = 1;

        $('.blPartsAccessories .blInterchangeable').each(function () {
            needSendInterchangeable = 0;
            $(this).find('.needSendInterchangeable').each(function () {
                weWantSend = parseFloat($(this).val());
                weHaveInWarehouse = parseFloat($(this).closest('.row').find('.inWarehouseInterchangeable').val());

                if(weHaveInWarehouse < weWantSend){
                    $(this).closest('.blInterchangeable').find('.infoDangerExecution').html(
                        '<div class="alert alert-danger fade in">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        'Замена:Не достаточно на складе' +
                        '</div>'
                    );
                    answer = 0;
                }

                needSendInterchangeable += weWantSend;
            });

            needSend = parseFloat($(this).find('.needSend').val());

            if(needSend!=needSendInterchangeable){
                $(this).closest('.blInterchangeable').find('.infoDangerExecution').html(
                    '<div class="alert alert-danger fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    'Не достаточно выбранно' +
                    '</div>'
                );
                answer = 0;
            }
        });

        return answer;
    }

    function checkWantCan() {
        blForm = $('.popupSendingExecution form');

        wantC = parseFloat(blForm.find('.WantCollect').val());
        canC = parseFloat(blForm.find('.CanCollect').val());

        if(wantC>canC){
            answer = 0;
            //nonComplectCells('show');
        } else {
            //nonComplectCells('hide');
            answer = 1;
        }

        return answer;
    }

    function checkBeforeSend(){
        answer = checkWantCan();
        if(answer == 1){
            answer = checkInterchangeable();
            if(answer == 1){
                answer = checkReserve();
                if(answer == 1){
                    $('.assemblyBtn').show();
                    return true;
                }
            }
        }
        //$('.assemblyBtn').hide();
        if (document.getElementById('none-complect-ch').checked) {
            $('.assemblyBtn').show();
        }

        return true;
    }
    $('#none-complect-ch').on('change',function(){
        if (document.getElementById('none-complect-ch').checked) {
            $('.assemblyBtn').show();
            $('#none-complect-ch-val').val(1);
            nonComplectCells('show');
        } else {
            nonComplectCells('hide');
            $('#none-complect-ch-val').val(0);
        }
    });

    $('.blPartsAccessories').on('change','.partNeedReserve',function(){
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
        $(".popupSendingExecution #execution-posting .blPartsAccessories").find('.blUnique').each(function () {

            title = $(this).find('.partTitle').val();

            if(title != undefined){
                tempBl +=
                    '<tr>' +
                        '<td>' + title +
                        '<td>' + $(this).find('.numberWarehouse').val() +
                        '<td>' + $(this).find('.partContractor').val() +
                        '<td>' + $(this).find('.partNeedForOne').val() +
                        '<td>' + $(this).find('.needSend').val() +
                        '<td>' + $(this).find('.partNeedReserve').val();
            }

        });

        $(".popupSendingExecution #execution-posting .blPartsAccessories").find('.blInterchangeable').each(function () {
            tempBlInterchangeable = '';

            $(this).find('.form-group.row').each(function () {

                title = $(this).find('.partTitle').val();
                if(title != undefined){
                    tempBlInterchangeable +=
                        '<tr>' +
                        '<td>' + title +
                        '<td>' + $(this).find('.numberWarehouse').val() +
                        '<td>' + $(this).find('.partContractorInterchangeable').val() +
                        '<td>' + $(this).find('.partNeedForOneInterchangeable').val() +
                        '<td>' + $(this).find('.needSendInterchangeable').val() +
                        '<td>' + $(this).find('.partNeedReserveInterchangeable').val();
                }


            });

            tempBl +=
                '<tr>' +
                    '<td colspan="6">' +
                        '<table>' +
                            '<tr>' +
                                '<td colspan="6">' + $(this).find('.blTitleInterchangeable').text() +
                            tempBlInterchangeable +
                            '<tr>' +
                                '<td>' +
                                '<td>' + $(this).find('.totalInterchangeable .numberWarehouse').val() +
                                '<td>' + $(this).find('.totalInterchangeable .partContractor').val() +
                                '<td>' + $(this).find('.totalInterchangeable .partNeedForOne').val() +
                                '<td>' + $(this).find('.totalInterchangeable .needSend').val() +
                                '<td>' + $(this).find('.totalInterchangeable .partNeedReserve').val()+
                        '</table>' +
                    '</td>' +
                '</tr>';

        });

        printFile =
            '<table>' +
                '<tr>' +
                    '<th colspan="6">Отправка на исполнение' +
                '<tr>' +
                    '<td><b>Собираем<b>'+
                    '<td colspan="5">' + $(".popupSendingExecution #execution-posting select[name='parts_accessories_id'] :selected").text() +
                '<tr>' +
                    '<td><b>Можно собрать<b>'+
                    '<td colspan="5">' + $(".popupSendingExecution #execution-posting input[name='can_number']").val()  + ' шт.' +
                '<tr>' +
                    '<td><b>Количество<b>'+
                    '<td colspan="5">' + $(".popupSendingExecution #execution-posting input[name='want_number']").val() + ' шт.' +
                '<tr>' +
                    '<th colspan="6">Необходимо:' +
                '<tr>' +
                    '<td> Комплектующая' +
                    '<td> В нали- чие' +
                    '<td> У испол- нителя' +
                    '<td> Нужно на одну' +
                    '<td> Нужно отпра- вить' +
                    '<td> Запас' +

                tempBl +
    
                '<tr>' +
                    '<td><b>Кому выдано<b>'+
                    '<td colspan="5">' + $(".popupSendingExecution #execution-posting input[name='fullname_whom_transferred']").val() +
    
                '<tr>' +
                    '<td><b>Поставщики и исполнители<b>'+
                    '<td colspan="5">' + $(".popupSendingExecution #execution-posting select[name='suppliers_performers_id'] :selected").text() +
        
                '<tr>' +
                    '<td><b>Дата исполнения<b>'+
                    '<td colspan="5">' + $(".popupSendingExecution #execution-posting input[name='date_execution']").val() +

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
    });

    $('#selectChangeStatus').on('change',function () {
        if($(this).val() != ''){
            $('#selectGoods').removeAttr('disabled');
        }
    });


    // add reserve for interchangeable
    $('.popupSendingExecution').on('change','.partNeedReserveInterchangeable',function () {
        needSend = 0;
        bl = $(this).closest('.blInterchangeable');
        bl.find('.partNeedReserveInterchangeable').each(function () {
            needSend += parseFloat($(this).val());
        });
        bl.find('.partNeedReserve').val(needSend);
    });

    // add reserve for interchangeable
    $('.popupSendingExecution').on('change','.needSendInterchangeable',function () {
        bl = $(this).closest('.row');
        blFull = $(this).closest('.blInterchangeable');
        blFull.find('.infoDangerExecution').html('');

        inWarehouseInterchangeable = parseFloat(bl.find('.inWarehouseInterchangeable').val());
        partContractorInterchangeable = parseFloat(bl.find('.partContractorInterchangeable').val());

        needSendInterchangeable = parseFloat($(this).val());
        if(needSendInterchangeable <= (inWarehouseInterchangeable+partContractorInterchangeable)){
            checkBeforeSend();
        } else{
            blFull.find('.infoDangerExecution').html(
                '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                'Не достаточно на складе' +
                '</div>'
            );
        }

    });
    //----non complect func-----
    function nonComplectCells(action) {
        console.log('noneComplectCells:'+action);
        if (action == 'show') {
            $('.none-complect-ch-row').show();
            $('#none-complect-ch').attr('checked',true);//.attr('disabled',true)
            $('#none-complect-ch-val').val(1);
            $('.partNoneComplect').show();
            $('.assemblyBtn').show();

        }
        if (action == 'hide') {
            $('.none-complect-ch-row').hide();
            $('#none-complect-ch').attr('checked',false);
            $('#none-complect-ch-val').val(0);
            $('.partNoneComplect').hide();
        }
    }
    function showNkParts() {
        var nk_str='';
        $('#none-complect-items').children().each(function(){
            var part_id = $(this).attr('id').split('-')[1];
            console.log('part is:'+part_id);
            nk_str=nk_str+$("input[data-part_id="+part_id+"]").val()+"\n";
        });
        nk_str=nk_str+'----------Всего:'+$("#none-complect-items").children().length+'----------\n';
        alert(nk_str);
    }

</script>