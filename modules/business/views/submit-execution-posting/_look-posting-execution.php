<?php
use app\components\THelper;
use yii\helpers\Html;
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


$want_number = $model->number;


?>

<div class="modal-dialog modal-lg popupLookExecution">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">x</button>
            <h4 class="modal-title">Выполненная заявка на исполнение</h4>
        </div>

        <div class="modal-body">
            <div>

                <div class="form-group row infoDanger"></div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label>Товар</label>
                        <?=Html::dropDownList('parts_accessories_id',
                            $model->parts_accessories_id,
                            ArrayHelper::merge([''=>'выберите товар'],PartsAccessories::getListPartsAccessoriesWithComposite()),[
                                'class'=>'form-control',
                                'id'=>'selectGoods',
                                'required'=>'required',
                                'options' => [
                                    '' => ['disabled' => true]
                                ],
                                'disabled' => (!empty($model) ?  true : false)
                            ])?>
                    </div>
                    <div class="col-md-3">
                        <label>Заказано</label>
                        <?=Html::input('text','ordering_number',$model->number,['class'=>'form-control CanCollect','disabled'=>true])?>
                    </div>
                    <div class="col-md-3">
                        <label>Cобрано</label>
                        <?=Html::input('text','make_number',$model->received,['class'=>'form-control CanCollect','disabled'=>true])?>
                    </div>
                </div>

                <div class="form-group blPartsAccessories row">
                    <?php if(!empty($model)){ ?>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-3">Отправленно</div>
                                        <div class="col-md-3">Запас</div>
                                    </div>
                                    <?php if(!empty($model->list_component)){ ?>
                                        <?php foreach($model->list_component as $item){ ?>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                   <?=Html::input('text','',$listGoods[(string)$item['parts_accessories_id']],['class'=>'form-control partTitle','disabled'=>true]);?>
                                                </div>

                                                <div class="col-md-3">
                                                    <?=Html::input('text','',($item['number']*$want_number),['class'=>'form-control needSend','disabled'=>true]);?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?=Html::input('number','reserve[]',(!empty($item['reserve']) ? $item['reserve'] : 0),[
                                                        'class'=>'form-control partNeedReserve',
                                                        'pattern'=>'\d*',
                                                        'min' => '0',
                                                        'step'=>'1',
                                                        'disabled'=>true
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
                            (string)$model->suppliers_performers_id,
                            $listSuppliersPerformers,[
                                'class'=>'form-control',
                                'id'=>'selectChangeStatus',
                                'required'=>'required',
                                'disabled' => true
                            ])?>
                    </div>
                    <div class="col-md-3">
                        <?=Html::label(THelper::t('date_execution'))?>
                        <?=Html::input('text','date_execution',$model->date_execution->toDateTime()->format('Y-m-d'),['class'=>'form-control','disabled' => true])?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?=Html::label(THelper::t('fullname_whom_transferred'))?>
                        <?=Html::input('text','fullname_whom_transferred',(!empty($model->fullname_whom_transferred) ? $model->fullname_whom_transferred : ''),[
                            'class'=>'form-control',
                            'disabled' => true
                        ]);?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 text-left">
                        <?= Html::button(THelper::t('print'), ['class' => 'btn btn-success btnPrint','type'=>'button']) ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">


    $(".btnPrint").on('click', function() {

        tempBl = '';
        $(".popupLookExecution .blPartsAccessories").find('.form-group.row').each(function () {
            title = $(this).find('.partTitle :selected').text();
            if(title == ''){
                title = $(this).find('.partTitle').val();
            }
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
            '<td colspan="3">' + $(".popupLookExecution select[name='parts_accessories_id'] :selected").text() +
            '<tr>' +
            '<td><b>Заказано<b>'+
            '<td colspan="3">' + $(".popupLookExecution input[name='ordering_number']").val()  + ' шт.' +
            '<tr>' +
            '<td><b>Собранно<b>'+
            '<td colspan="3">' + $(".popupLookExecution input[name='make_number']").val() + ' шт.' +
            '<tr>' +
            '<th colspan="4">Необходимо:' +
            '<tr>' +
                '<td> Коплектующая' +
                '<td> Отправленно' +
                '<td> Запас' +

            tempBl +

            '<tr>' +
            '<td><b>Кому выдано<b>'+
            '<td colspan="3">' + $(".popupLookExecution input[name='fullname_whom_transferred']").val() +

            '<tr>' +
            '<td><b>Поставщики и исполнители<b>'+
            '<td colspan="3">' + $(".popupLookExecution select[name='suppliers_performers_id'] :selected").text() +

            '<tr>' +
            '<td><b>Дата исполнения<b>'+
            '<td colspan="3">' + $(".popupLookExecution input[name='date_execution']").val() +

            '</table>';

        $.print(printFile,{
            stylesheet : window.location.origin + '/css/print.css'
        });
    });
</script>