<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\components\THelper;

use app\models\PartsAccessories;
use app\models\SuppliersPerformers;


$listGoods = PartsAccessories::getListPartsAccessories();
$listSuppliers = SuppliersPerformers::getListSuppliersPerformers();

?>

<div class="modal-dialog modal-more-lg popupPostingExecution">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Оприходование: <span><?=$listGoods[(string)$model->parts_accessories_id]?></span></h4>
        </div>

        <div class="modal-body">
            <div>
                <?php $formCom = ActiveForm::begin([
                    'action' => '/' . $language . '/business/submit-execution-posting/save-posting-execution',
                    'options' => ['name' => 'savePartsAccessories'],
                ]); ?>

                <?=Html::hiddenInput('_id',(string)$model->_id);?>
                <?=Html::hiddenInput('',$model->fullname_whom_transferred,['class'=>'fullnameWhomTransferred']);?>
                <?=Html::hiddenInput('',$model->date_execution->toDateTime()->format('Y-m-d H:i:s'),['class'=>'dateExecution']);?>
                <?=Html::hiddenInput('',$listSuppliers[(string)$model->suppliers_performers_id],['class'=>'SuppliersPerformers']);?>

                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-2">
                        Заказано:
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
                    <div class="col-md-1">
                        Пришло:
                    </div>
                    <div class="col-md-3">
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
                                        <div class="col-md-7"></div>
                                        <div class="col-md-2">Отправленно</div>
                                        <div class="col-md-2">Использованно</div>
                                        <div class="col-md-1">Запас</div>
                                    </div>
                                    <?php foreach($list_component as $items){ ?>
                                        <?php if(count($items)>1){ ?>
                                            <div class="panel panel-default blInterchangeable">
                                                <div class="panel-body">
                                                    <div class="infoDangerExecution"></div>

                                                    <?php foreach($items as $k=>$item){ ?>
                                                        <div class="form-group row">
                                                            <div class="col-md-7">
                                                                <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],['class'=>'form-control partTitle','disabled'=>'disabled']);?>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <?=Html::hiddenInput('number_for_one',$item['number'],['class'=>'needForOne'])?>
                                                                <?=Html::input('text','',($item['number_use']),['class'=>'form-control needSend','disabled'=>'disabled']);?>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <?=Html::input('text','',!empty($item['use_for_received']) ? $item['use_for_received'] : 0,['class'=>'form-control alreadyUse','disabled'=>'disabled']);?>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <?=Html::input('text','need_use['.(string)$item['parts_accessories_id'].']','0',['class'=>'form-control needUse','disabled'=>($item['number_use']=='0' ? true : false)]);?>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <?=Html::input('text','',$item['reserve'],['class'=>'form-control partNeedReserve','disabled'=>'disabled']);?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } else {?>
                                            <?php $item=$items['0']; ?>
                                            <div class="form-group row">
                                                <div class="col-md-7">
                                                    <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],['class'=>'form-control partTitle','disabled'=>'disabled']);?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?=Html::input('text','',($item['number']*$model->number),['class'=>'form-control needSend','disabled'=>'disabled']);?>
                                                </div>
                                                <div class="col-md-1">
                                                    <?=Html::input('text','',$item['reserve'],['class'=>'form-control partNeedReserve','disabled'=>'disabled']);?>
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
                        <?= Html::button(THelper::t('print'), ['class' => 'btn btn-success btnPrint','type'=>'button']) ?>
                        <?= Html::submitButton(THelper::t('posting'), ['class' => 'btn btn-success assemblyBtn']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('form[name="savePartsAccessories"]').keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    $('.postingExecution').on('change',function(){
        checkBeforeSend();
    });

    $('.needUse').on('change',function(){
        checkBeforeSend();
    });

    $(".btnPrint").on('click', function() {

        tempBl = '';
        $(".popupPostingExecution .blPartsAccessories").find('.form-group.row').each(function () {
            title = $(this).find('.partTitle').val();
            tempBl +=
                '<tr>' +
                '<td>'+  title +
                '<td>'+ $(this).find('.needSend').val() +
                '<td>'+ $(this).find('.partNeedReserve').val();
        });

        printFile =
            '<table>' +
            '<tr>' +
            '<th colspan="4">Выполненная заявка на исполнение' +
            '<tr>' +
            '<td><b>Собираем<b>'+
            '<td colspan="3">' + $(".popupPostingExecution .modal-title span").text() +
            '<tr>' +
            '<td><b>Заказано<b>'+
            '<td colspan="3">' + $(".popupPostingExecution .orderingExecution").val()  + ' шт.' +
            '<tr>' +
            '<td><b>Собранно<b>'+
            '<td colspan="3">' + $(".popupPostingExecution .receivedExecution").val() + ' шт.' +
            '<tr>' +
            '<th colspan="4">Необходимо:' +
            '<tr>' +
            '<td> Коплектующая' +
            '<td> Отправленно' +
            '<td> Запас' +

            tempBl +

            '<tr>' +
            '<td><b>Кому выдано<b>'+
            '<td colspan="3">' + $(".popupPostingExecution .fullnameWhomTransferred").val() +

            '<tr>' +
            '<td><b>Поставщики и исполнители<b>'+
            '<td colspan="3">' + $(".popupPostingExecution .SuppliersPerformers").val() +

            '<tr>' +
            '<td><b>Дата исполнения<b>'+
            '<td colspan="3">' + $(".popupPostingExecution .dateExecution").val() +

            '</table>';

        $.print(printFile,{
            stylesheet : window.location.origin + '/css/print.css'
        });
    });

    function checkBeforeSend(){
        answer = checkWantCan();
        if(answer == 1){
            answer = checkInterchangeable();
            if(answer == 1){
                $('.assemblyBtn').show();
                return true;
            }
        }
        $('.assemblyBtn').hide();
        return true;
    }

    function checkWantCan() {
        $(".infoDanger").html('');
        answer = 1;
        orderingExecution = parseInt($('.orderingExecution').val());
        receivedExecution = parseInt($('.receivedExecution').val());
        wantPosting = parseInt($('.postingExecution').val());

        if(orderingExecution < (receivedExecution + wantPosting)){
            $(".infoDanger").html(
                '<div class="alert alert-danger fade in">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                'Такое количество оприходовать нельзя. Доступно ' + (orderingExecution - receivedExecution) + 'шт.' +
                '</div>'
            );

            answer = 0;
        }

        return answer;
    }

    function checkInterchangeable(){
        answer = 1;

        needPosting = $('.postingExecution').val();

        $('.blPartsAccessories .blInterchangeable').each(function () {

            $(this).closest('.blInterchangeable').find(".infoDangerExecution").html('');

            needUseInterchangeable = 0;
            $(this).find('.needUse').each(function () {
                wasSend = parseFloat($(this).closest('.row').find('.needSend').val());
                alreadyUse = parseFloat($(this).closest('.row').find('.alreadyUse').val());
                wantUse = parseFloat($(this).val());
                needForOne = parseFloat($(this).closest('.row').find('.needForOne').val());

                if(wantUse >  (wasSend-alreadyUse).toFixed(2)){
                    answer = 0;
                    $(this).closest('.blInterchangeable').find(".infoDangerExecution").html(
                        '<div class="alert alert-danger fade in">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                        'Неверные данные!!!' +
                        '</div>'
                    );
                }
                needUseInterchangeable += 1 *(wantUse/needForOne).toFixed(2);


            });

            if(needPosting!=needUseInterchangeable){
                answer = 0;

                console.log(needPosting +'!='+ needUseInterchangeable);

                $(this).closest('.blInterchangeable').find(".infoDangerExecution").html(
                    '<div class="alert alert-danger fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    'Неверные данные!!!' +
                    '</div>'
                );
            }
        });

        return answer;
    }

</script>