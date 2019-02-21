<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

?>

<div class="m-b-md">
    <h3 class="m-b-none">Прием/выдача товаров</h3>
</div>
<section class="panel panel-default">
    <div class="row">
        <div class="col-md-6">
            <div class="m">
                <span class="m-r">С</span>
                <input id="mainFrom" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['dateFrom']?>" data-date-format="yyyy-mm" data-filter="dateFrom"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months" >
                <span class="m-r m-l">ПО</span>
                <input id="mainTo" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['dateTo']?>" data-date-format="yyyy-mm" data-filter="dateTo"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active issueGoods"><a href="javascript:void(0);">Выдача</a></li>
                    <li class="receptionGoods"><a href="javascript:void(0);">Приём</a></li>
                    <li class="orderGoods"><a href="javascript:void(0);" >Заказ</a></li>

<!--                    <li class="receptionGoods"><a href="/ru/business/showrooms/reception-issue-goods-reception">Приём</a></li>-->
<!--                    <li class="orderGoods"><a href="/ru/business/showrooms/reception-issue-goods-order">Заказ</a></li>-->
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="issueGoods"> 
                        <!-- Выдача -->
                        <div class="row">
                            <div class="col-md-12 m-b">
                                <input type="checkbox" id="showOnlyNotIssue">
                                <span class="m-l-xs">Отобразить только невыданные</span>
                            </div>
                        </div>                        
                        <div class="table-responsive">
                            <table id="table-issue" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Дата создания
                                        </th>
                                        <th>
                                            Логин
                                        </th>
                                        <th>
                                            ФИО
                                        </th>
                                        <th>
                                            Телефон
                                        </th>
                                        <th>
                                            Название продукта
                                        </th>
                                        <th>
                                            Статус
                                        </th>
                                        <th>
                                            Даты закрытия заказ
                                        </th>
                                        <th>
                                            Будет доставлен ориентировочно
                                        </th>
                                        <th>
                                            Адрес доставки
                                        </th>
                                        <th>
                                           
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($salesShowroom)){ ?>
                                        <?php foreach ($salesShowroom as $saleItem) { ?>
                                            <tr>
                                                <td><?=$saleItem['dateCreate']?></td>
                                                <td><?=$saleItem['login']?></td>
                                                <td>
                                                    <?=$saleItem['secondName']?><br>
                                                    <?=$saleItem['firstName']?>
                                                </td>
                                                <td>
                                                    <?=$saleItem['phone1']?><br>
                                                    <?=$saleItem['phone2']?>
                                                </td>
                                                <td><?=$saleItem['pack']?></td>
                                                <td><?=$saleItem['statusShowroom']?></td>
                                                <td><?=$saleItem['dateFinish']?></td>
                                                <td><?=$saleItem['dateDelivery']?></td>
                                                <td><?=$saleItem['addressDelivery']?></td>
                                                <td>
                                                    <a class="editIssue" href="#issueInfo" data-id="<?=$saleItem['saleId']?>"
                                                    data-toggle="modal"
                                                    >
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                               <a href="#issueOrder" class="btn btn-success pull-right issueOrder m-sm" data-toggle="modal">Подобрать заказ</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade issueInfo" id="issueInfo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Выдача товара</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12 blError"></div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <p>Дата заявки:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-date m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Логин:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-login m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>ФИО:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-FIO m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Телефоны:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-phones m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Заказано:</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <ul class="issue-order list-unstyled">
                            <li>Набор Life Balanсe 2 шт. <a href="#" class="fromBalnce pull-right">Выдать с баланса</a> <span class="spanIssued pull-right m-r"></span></li>
                            <li>Life Expert 2 шт. <a href="#" class="fromBalnce issued pull-right">Отменить</a> <span class="spanIssued pull-right m-r">Выдано с баланса</span></li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t">Статус:</p>
                    </div>
                    <div class="col-md-9">
                        <?=Html::dropDownList('',false,\app\models\Sales::getStatusShowroom(),[
                            'class' => 'issueSelect w-50 form-control m'
                        ])?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Дата доставки:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-dateDelivery m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Адрес:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-address m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Закрыть</a>
                            <button type="button" class="btn btn-success saveEditedIssue m-n">
                            Сохранить
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade issueOrder" id="issueOrder">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Подобрать заказ</h4>
            </div>
            <div class="modal-body">

                <div class="issueOrderRow">
                    <div>
                        <div class="row">
                            <div class="col-md-12 blError"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-2">
                                <input type="text" class="form-control orderId" placeholder="Номер накладной">
                            </div>
                            <div class="col-md-4">
                                <input type="button" class="btn btn-success checkIssue m-n" value="Подобрать заказ">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default issueOrderDetail">
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12 blError"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Дата заявки:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-date m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Логин:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-login m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>ФИО:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-FIO m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Телефоны:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-phones m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Заказано:</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <ul class="issue-order list-unstyled"></ul>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p class="m-t">Статус:</p>
                            </div>
                            <div class="col-md-9">
                                <?=Html::dropDownList('',false,\app\models\Sales::getStatusShowroom(),[
                                    'class' => 'issueSelect w-50 form-control m'
                                ])?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Дата доставки:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-dateDelivery m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Адрес:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-address m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-sm btn-success pull-right checkLogin m-n" disabled="disabled">
                                    Подобрать заказ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row m-t-lg">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Закрыть</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--<div class="modal fade" id="receiptionGoodsEdit">-->
<!--    <div class="modal-dialog" >-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <h4 class="modal-title">Приём товара</h4>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Дата покупки:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold dateOrder m-l m-r">14.02.2019</span>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Название продукта:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold itemOrder m-l m-r">набор Life Balance</span>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Серийный номер:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <a href="#" class="m-l">Добавить</a>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row m-t m-b">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Статус:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <select name="receiptionStatusSelect" class="receiptionStatusSelect w-195p inline form-control m-l"> -->
<!--                            <option value="null">Доставлено</option> -->
<!--                            <option value="1">Отгружено</option> -->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row m-t m-b">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Гарантия:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold receptionWarranty m-l m-r">Да</span>-->
<!--                        <a href="#" class="pull-right">Не гарантийный случай</a>-->
<!--                    </div>-->
<!--                </div>-->
<!--                -->
<!--                <div class="row">-->
<!--                    <div class="col-md-12">-->
<!--                        Комментарий-->
<!--                        <textarea class="form-control receptionComment m-t m-b" name="receptionComment" id="receptionComment" rows="5" placeholder=""></textarea>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-12 text-center">-->
<!--                        <div class="col-sm-8 col-sm-offset-2 form-group">-->
<!--                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>-->
<!--                            <a class="btn btn-success receptionSave">Сохранить</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--<div class="modal fade" id="editReceiptedGoods">-->
<!--    <div class="modal-dialog" >-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header">-->
<!--                <h4 class="modal-title">Редактирование</h4>-->
<!--            </div>-->
<!--            <div class="modal-body">-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Дата покупки:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold dateReceipted m-l m-r">14.02.2019</span>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Логин:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold loginReceipted m-l m-r">main</span>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>ФИО:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold fioReceipted m-l m-r">Абдула Абрамович КамЕнский</span>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Телефон:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold phoneReceipted m-l m-r">03</span>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Название продукта:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold itemReceipted m-l m-r">набор Life Balance</span>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-3">-->
<!--                        <p>Серийный номер:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <span class="font-bold serialReceipted m-l m-r">3424234234</span>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row">-->
<!---->
<!--                    <div class="col-md-3">-->
<!--                        <p>Статус:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <select name="receiptedStatusSelect" class="receiptedStatusSelect w-195p inline form-control m-l"> -->
<!--                            <option value="null">Доставлено</option> -->
<!--                            <option value="1">Отгружено</option> -->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row m-b m-t">-->
<!--                -->
<!--                    <div class="col-md-3">-->
<!--                        <p>Гарантия:</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-9">-->
<!--                        <select name="receiptedStatusSelect" class="receiptedStatusSelect w-195p inline form-control m-l"> -->
<!--                            <option value="null">Да</option> -->
<!--                            <option value="1">Нет</option> -->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!--                -->
<!--                <div class="row">-->
<!--                    <div class="col-md-12">-->
<!--                        Комментарий-->
<!--                        <textarea class="form-control receptionComment m-t m-b" name="receptionComment" id="receptionComment" rows="5" placeholder=""></textarea>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-12 text-center">-->
<!--                        <div class="col-sm-8 col-sm-offset-2 form-group">-->
<!--                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>-->
<!--                            <a class="btn btn-success editReceptionSave">Сохранить</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->


<?php $this->registerCssFile('/js/datepicker/datepicker.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js', ['position' => yii\web\View::POS_END]); ?>

<script>


    $('.issueInfo').on('click','.fromBalnce',function(){

        return true;

        if ($(this).hasClass('issued')) {
            //товар был выдан с баланса нажали отменить

            // отменяем всё в БД и меняем текст кнопки
            $(this).removeClass('issued');
            $(this).text('Выдать с баланса');
            $(this).next().text('');
        } else {
            //товар НЕ был выдан с баланса нажали выдать с балнса

            // выдаём с баланса всё в БД и меняем текст кнопки
            $(this).addClass('issued');
            $(this).text('Отменить');
            $(this).next().text('Выдано с баланса');

        }
    })

    $('.modal').on('click','.editReceptionSave',function(){
        // сохраняем редактирование приём товара

        $('#editReceiptedGoods').modal('hide');
    });

    $('.modal').on('click','.receptionSave',function(){
        // сохраняем приём товара

        $('#receiptionGoodsEdit').modal('hide');
    });

    $('table').on('click','.editIssue',function(){
      //  $('.issueOrderRow').hide();
      //  $('.issueOrderDetail').hide();

        clearIssueInfo();

        var blInfo = $('.issueInfo');

        $.ajax({
            url: '/ru/business/sale/get-sale',
            type: 'POST',
            data: {saleId:$(this).data('id')},
            beforeSend: function () {
                blInfo.find('.modal-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                blInfo.find('.loader').remove();
            },
            success: function(msg){
                if(msg.error === ''){
                    blInfo.find('.issue-date').text(msg.dateCreate);
                    blInfo.find('.issue-login').text(msg.login);
                    blInfo.find('.issue-FIO').text(msg.secondName + ' ' + msg.firstName);
                    blInfo.find('.issue-phones').text(msg.phone1 + ' ' + msg.phone2);

                    blInfo.find('.issueSelect').val(msg.statusShowroom);

                    blInfo.find('.issue-dateDelivery').text(msg.dateDelivery);
                    blInfo.find('.issue-address').text(msg.addressDelivery);



                    if(msg.statusShowroom === 'delivered'){
                        blInfo.find('.saveEditedIssue').data({id:''}).prop('disabled',true);
                        blInfo.find('.issueSelect').prop('disabled',true);
                    } else {
                        blInfo.find('.saveEditedIssue').data({id:msg.saleId}).prop('disabled',false);
                        blInfo.find('.issueSelect').prop('disabled',false);
                    }

                    var blOrder = blInfo.find('.issue-order');
                    blOrder.html('');
                    $.each(msg.products, function( k, v ) {

                        blProduct = '<li>'+v.name;

                        if(msg.typeDelivery == '-' || msg.typeDelivery == 'showroom'){
                            //blProduct += '<a href="javascript:void(0);" class="fromBalnce pull-right">Выдать с баланса</a> <span class="spanIssued pull-right m-r"></span></li>'
                        }

                        blOrder.append(blProduct);
                    });
                }
            }
        });

        $('.issueInfo').show();
    });

    $('.issueInfo').on('click','.saveEditedIssue',function () {
        $(this).prop('disabled', true);
        var saleId = $(this).data('id');
        var statusShowroom = $(this).closest('.issueInfo').find('.issueSelect').val();

        $.ajax({
            url: '/ru/business/sale/change-status-showroom-sale',
            type: 'POST',
            data: {saleId:saleId,statusShowroom:statusShowroom},
            beforeSend: function () {
                $('.issueInfo').find('.modal-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                $('.issueInfo').find('.loader').remove();
            },
            success: function(msg){
                $('.issueInfo .blError').html(
                    '<div class="alert alert-'+msg.typeAlert+' fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                    msg.message +
                    '</div>'
                );

                if(msg.typeAlert == 'success'){
                    location.reload();
                }
            }
        });
    });

    // $('#content').on('click','.issueOrder',function(){
    //     $('.issueOrderDetail').hide();
    //     $('.issueInfo').hide();
    //     $('.issueOrderRow').toggle();
    // });

    $('#content').on('click','.checkIssue',function(){

        var orderId = $(this).closest('.issueOrderRow').find('.orderId').val();
        var blInfo = $('.issueOrderDetail');

        clearIssueOrderDetail();

        $.ajax({
            url: '/ru/business/sale/get-sale',
            type: 'POST',
            data: {orderId:orderId},
            beforeSend: function () {
                $('#issueOrder').find('.modal-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                $('#issueOrder').find('.loader').remove();
            },
            success: function(msg){
                if(msg.error === ''){
                    if(msg.flHasAccruals === true){
                        blInfo.find('.issue-date').text(msg.dateCreate);
                        blInfo.find('.issue-login').text(msg.login);
                        blInfo.find('.issue-FIO').text(msg.secondName + ' ' + msg.firstName);
                        blInfo.find('.issue-phones').text(msg.phone1 + ' ' + msg.phone2);

                        blInfo.find('.issueSelect').prop( "disabled", true ).val(msg.statusShowroom);

                        blInfo.find('.issue-dateDelivery').text(msg.dateDelivery);
                        blInfo.find('.issue-address').text(msg.addressDelivery);

                        var blOrder = blInfo.find('.issue-order');
                        blOrder.html('');
                        $.each(msg.products, function( k, v ) {
                            blOrder.append('<li>'+v.name+'</li>');
                        });

                        if(msg.showroomId === ''){
                            blInfo.find('.checkLogin').data({id:msg.saleId}).prop('disabled', false);
                        } else {
                            blInfo.find('.blError').html(
                                '<div class="alert alert-danger fade in">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                                'Данный заказ уже привязан к шоу-руму' +
                                '</div>'
                            );

                            blInfo.find('.checkLogin').data({id:''}).prop('disabled', true);
                        }

                        if(msg.dateCreateY < 2019){
                            blInfo.find('.blError').html(
                                '<div class="alert alert-danger fade in">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                                'Данный заказ создан в 2018 году' +
                                '</div>'
                            );

                            blInfo.find('.checkLogin').data({id:''}).prop('disabled', true);
                        }

                        $('.issueOrderDetail').show();
                    } else {
                        $('.issueOrderRow .blError').html(
                            '<div class="alert alert-danger fade in">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                            'Данный заказ не возможно подобрать' +
                            '</div>'
                        );
                    }

                } else {
                    $('.issueOrderRow .blError').html(
                        '<div class="alert alert-danger fade in">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                        'Данный заказ не существует' +
                        '</div>'
                    );
                }

            }
        });

    });

    $('#content').on('click','.receiptGoods',function(){ 

        // нажимаем на подобрать заказа
        $('.receiptGoodsRow').toggle();
    })

    $('#content').on('click','.findReceiptOrder',function(){ 

        //ищем товар в БД и выдаём в таблицу ниже

        $('.table-reception-goods').toggle();
    });

    $('#content').on('click','.checkLogin',function () {
        $(this).prop('disabled', true);
        var saleId = $(this).data('id');

        $.ajax({
            url: '/ru/business/sale/set-showroom-sale',
            type: 'POST',
            data: {saleId:saleId},
            beforeSend: function () {
                $('#issueOrder').find('.modal-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                $('#issueOrder').find('.loader').remove();
            },
            success: function(msg){
                $('.issueOrderDetail .blError').html(
                    '<div class="alert alert-'+msg.typeAlert+' fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                    msg.message +
                    '</div>'
                );


                if(msg.typeAlert == 'success'){
                    location.reload();
                }
            }
        });
    });

    $('.filterInfoDate').datepicker().on('changeDate', function (e) {
        var link = window.location.href;
        var date = new Date(e.date);
        var newDate = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2);
        var newFilter = e.currentTarget.dataset.filter;

        $('.filterInfoDate').each(function () {
            link = updateQueryStringParameter(link,$(this).data('filter'),$(this).datepicker({ dateFormat: 'yy-mm' }).val());
        });

        link = updateQueryStringParameter(link,newFilter,newDate);

        document.location.href = link;

    });
    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|#|$)", "i");
        if( value === undefined ) {
            if (uri.match(re)) {
                return uri.replace(re, '$1$2');
            } else {
                return uri;
            }
        } else {
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                var hash =  '';
                if( uri.indexOf('#') !== -1 ){
                    hash = uri.replace(/.*#/, '#');
                    uri = uri.replace(/#.*/, '');
                }
                var separator = uri.indexOf('?') !== -1 ? "&" : "?";
                return uri + separator + key + "=" + value + hash;
            }
        }
    }

    function clearIssueOrderDetail() {
        var blInfo = $('.issueOrderDetail');

        blInfo.find('.issue-date').text('');
        blInfo.find('.issue-login').text('');
        blInfo.find('.issue-FIO').text('');
        blInfo.find('.issue-phones').text('');
        blInfo.find('.issueSelect').prop( "disabled", false ).val('');
        blInfo.find('.issue-dateDelivery').text('');
        blInfo.find('.issue-address').text('');
        blInfo.find('.issue-order').html('');
        blInfo.find('.checkLogin').data({id:''}).prop('disabled', true);
    }
    function clearIssueInfo() {
        var blInfo = $('.issueInfo');

        blInfo.find('.issue-date').text('');
        blInfo.find('.issue-login').text('');
        blInfo.find('.issue-FIO').text('');
        blInfo.find('.issue-phones').text('');
        blInfo.find('.issueSelect').prop( "disabled", false ).val('');
        blInfo.find('.issue-dateDelivery').text('');
        blInfo.find('.issue-address').text('');
        blInfo.find('.issue-order').html('');
    }

</script>