<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    $listStatusShowroom = \app\models\Sales::getStatusShowroom();
?>

<div class="m-b-md">
    <h3 class="m-b-none">Выдача товаров</h3>
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
<!--                    <li class="active issueGoods"><a href="javascript:void(0);">Выдача</a></li>-->
<!--                    <li class="receptionGoods"><a href="javascript:void(0);">Приём</a></li>-->
<!--                    <li class="orderGoods"><a href="javascript:void(0);" >Заказ</a></li>-->

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
                                            Кол.
                                        </th>
                                        <th>
                                            Статус
                                        </th>
                                        <th>
                                            Даты закрытия заказа
                                        </th>
                                        <th>
                                            Время доставки
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
                                                <td><?=$saleItem['countPack']?></td>
                                                <td><?=$saleItem['statusShowroom']?></td>
                                                <td><?=$saleItem['dateFinish']?></td>
                                                <td><?=$saleItem['dateDelivery']?></td>
                                                <td><?=$saleItem['addressDelivery']?></td>
                                                <td>
                                                    <a class="editIssue" href="#issueInfo" data-id="<?=$saleItem['saleId']?>" data-toggle="modal">
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

<div class="modal fade issueInfo " id="issueInfo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Заказ</h4>
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
                        <p>Название продукта:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-product-name m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Количество:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-count m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Шоу-рум:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-showroom m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Куда отправлеям:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-address m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Информация о доставке:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-info-delivery m-l m-r"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Получатель:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold issue-FIO m-l m-r"></span>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                                <p>Телефоны 1:</p>
                            </div>
                            <div class="col-md-8">
                                <span class="font-bold issue-phone-1 m-l m-r"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p>Телефоны 2:</p>
                            </div>
                            <div class="col-md-8">
                                <span class="font-bold issue-phone-2 m-l m-r"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                                <p>Логин :</p>
                            </div>
                            <div class="col-md-8">
                                <span class="font-bold issue-login m-l m-r"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p>Скайп:</p>
                            </div>
                            <div class="col-md-8">
                                <span class="font-bold issue-skype m-l m-r"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p>Email:</p>
                            </div>
                            <div class="col-md-8">
                                <span class="font-bold issue-email m-l m-r"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Заказано:</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <ul class="issue-order list-unstyled">

                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t">Статус:</p>
                    </div>
                    <div class="col-md-9">
                        <?=Html::dropDownList('',false,[],[
                            'class' => 'issueSelect w-50 form-control m',
                        ])?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p>Коментарий:</p>
                    </div>
                    <div class="col-md-12">
                        <textarea name="" rows="3" class="form-control issueComment"></textarea>
                        <textarea rows="3" class="form-control lookComment scrollForComment" disabled="disabled"></textarea>
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
    <div class="modal-dialog modal-lg">
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
                                <p>Дата заказа:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-date m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Название продукта:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-product-name m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Количество:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-count m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Шоу-рум:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-showroom m-l m-r"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p>Куда отправлеям:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-address m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Информация о доставке:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-info-delivery m-l m-r"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Получатель:</p>
                            </div>
                            <div class="col-md-9">
                                <span class="font-bold issue-FIO m-l m-r"></span>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>Телефоны 1:</p>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="font-bold issue-phone-1 m-l m-r"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>Телефоны 2:</p>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="font-bold issue-phone-2 m-l m-r"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>Логин :</p>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="font-bold issue-login m-l m-r"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>Скайп:</p>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="font-bold issue-skype m-l m-r"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>Email:</p>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="font-bold issue-email m-l m-r"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <p>Заказано:</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <ul class="issue-order list-unstyled">

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

<?php $this->registerCssFile('/js/datepicker/datepicker.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js', ['position' => yii\web\View::POS_END]); ?>

<script>

    var listStatusShowroom = JSON.parse('<?=json_encode($listStatusShowroom)?>');

    var availableStatusShowroom = {
        waiting : ['waiting','delivered','delegate_company'],
        delivering : ['delivering','delivered_company'],
        delivered : ['delivered'],
        delegate_company : ['delegate_company','delivered_showroom'],
        sending_showroom : ['sending_showroom','delivered_company'],
        delivered_company : ['delivered_company'],
        issue_part : ['issue_part','delivered']
    };

    $('#table-issue').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

    $('.issueInfo').on('click','.issueFromBalance',function(){

        var blInfo = $('.issueInfo');

        var blProductLine = $(this).closest('li');

        var statusSale = blInfo.find('.issueSelect').val();

        if(statusSale == 'waiting' || statusSale == 'delegate_company' || statusSale == 'issue_part'){
            $.ajax({
                url: '/ru/business/showrooms/issue-product-from-showroom',
                type: 'POST',
                data: {
                    saleId:$(this).data('sale-id'),
                    parts_accessories_id:$(this).data('parts_accessories_id'),
                    number:$(this).data('number')
                },
                beforeSend: function () {
                    $('.issueInfo').find('.modal-body').append('<div class="loader"><div></div></div>');
                },
                complete: function () {
                    $('.issueInfo').find('.loader').remove();
                },
                success: function(msg){
                    blProductLine.prepend(
                        '<div class="alert alert-'+msg.typeAlert+' fade in">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                            msg.message +
                        '</div>'
                    );

                    if(msg.typeAlert === 'success'){
                        blProductLine.find('a').remove();
                        blProductLine.append('<span class="label label-primary pull-right">Выдан</span>');
                    }

                }
            });
        } else {
            blInfo.find('.blError').html(
                '<div class="alert alert-danger fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                        'При данном статусе выдавать нельзя' +
                '</div>'
            );
        }
    });

    $('.modal').on('click','.editReceptionSave',function(){
        // сохраняем редактирование приём товара

        $('#editReceiptedGoods').modal('hide');
    });

    $('.modal').on('click','.receptionSave',function(){
        // сохраняем приём товара

        $('#receiptionGoodsEdit').modal('hide');
    });

    $('table').on('click','.editIssue',function(){

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
                    blInfo.find('.saveEditedIssue').data({id:msg.saleId});

                    blInfo.find('.issue-date').text(msg.dateCreate);
                    blInfo.find('.issue-product-name').text(msg.pack);
                    blInfo.find('.issue-count').text(msg.count);
                    blInfo.find('.issue-showroom').text(msg.showroomName);
                    blInfo.find('.issue-FIO').text(msg.secondName + ' ' + msg.firstName);
                    blInfo.find('.issue-phone-1').text(msg.phone1);
                    blInfo.find('.issue-phone-2').text(msg.phone2);

                    blInfo.find('.issue-login').text(msg.login);
                    blInfo.find('.issue-skype').text(msg.skype);
                    blInfo.find('.issue-email').text(msg.email);

                    var statusShowroomOptions = '';
                    availableStatusShowroom[msg.statusShowroom].forEach(function(item) {
                        statusShowroomOptions += '<option value="'+item+'">'+listStatusShowroom[item]+'</option>'
                    });

                    blInfo.find('.issueSelect').html(statusShowroomOptions).val(msg.statusShowroom);
                    blInfo.find('.saveEditedIssue').prop('disabled',false);

                    if(msg.statusShowroom == 'delivered_company'
                        || msg.statusShowroom == 'delivered') {
                        blInfo.find('.saveEditedIssue').prop('disabled',true);
                    }

                    blInfo.find('.issue-dateDelivery').text(msg.dateDelivery);
                    blInfo.find('.issue-address').text(msg.addressDelivery);

                    if(msg.typeDelivery == 'courier'){
                        blInfo.find('.issue-info-delivery').html(
                            'Служба доставки - ' + msg.titleDelivery +
                            '<br>Время доставки - ' + msg.dateDelivery + ' дней' +
                            '<br>Стоимость доставки - ' + msg.priceDelivery + ' eur'
                        );
                    }

                    blInfo.find('.lookComment').text(msg.commentShowroom);

                    var blOrder = blInfo.find('.issue-order');
                    blOrder.html('');
                    $.each(msg.products, function( k, v ) {

                        blProduct = '<li>'+v.title;

                        if((msg.statusShowroom == 'waiting' || msg.statusShowroom == 'sending_showroom')
                            && v.status == 'status_sale_new' && v.parts_accessories_id !== null){
                            blProduct += '<a href="javascript:void(0);" class="issueFromBalance pull-right" data-sale-id="'+msg.saleId+'" data-number="'+k+'" data-parts_accessories_id="'+v.parts_accessories_id.$oid+'">Выдать с моего шоу-рума демонстрационый образец</a><span class="spanIssued pull-right m-r"></span></li>'
                        } else if(v.status == 'status_sale_issued' && v.parts_accessories_id !== null){
                            blProduct += '<span class="label label-primary pull-right">Выдан</span>';
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
        var commentShowroom = $(this).closest('.issueInfo').find('.issueComment').val();

        $.ajax({
            url: '/ru/business/sale/change-status-showroom-sale',
            type: 'POST',
            data: {saleId:saleId,statusShowroom:statusShowroom,comment:commentShowroom},
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
                        blInfo.find('.issue-product-name').text(msg.pack);
                        blInfo.find('.issue-count').text(msg.count);
                        blInfo.find('.issue-showroom').text(msg.showroomName);
                        blInfo.find('.issue-login').text(msg.login);
                        blInfo.find('.issue-FIO').text(msg.secondName + ' ' + msg.firstName);
                        blInfo.find('.issue-phone-1').text(msg.phone1);
                        blInfo.find('.issue-phone-2').text(msg.phone2);

                        blInfo.find('.issueSelect').prop( "disabled", true ).val(msg.statusShowroom);

                        blInfo.find('.issue-dateDelivery').text(msg.dateDelivery);
                        blInfo.find('.issue-address').text(msg.addressDelivery);

                        if(msg.typeDelivery == 'courier'){
                            blInfo.find('.issue-info-delivery').html('Служба доставки - ' + msg.titleDelivery +
                                '<br>Время доставки - ' + msg.dateDelivery + ' дней' +
                                '<br>Стоимость доставки - ' + msg.priceDelivery + ' eur'
                            );
                        }

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

    // $('#content').on('click','.receiptGoods',function(){
    //
    //     // нажимаем на подобрать заказа
    //     $('.receiptGoodsRow').toggle();
    // })
    //
    // $('#content').on('click','.findReceiptOrder',function(){
    //
    //     //ищем товар в БД и выдаём в таблицу ниже
    //
    //     $('.table-reception-goods').toggle();
    // });

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
        blInfo.find('.issue-product-name').text('');
        blInfo.find('.issue-count').text('');
        blInfo.find('.issue-showroom').text('');
        blInfo.find('.issue-login').text('');
        blInfo.find('.issue-FIO').text('');
        blInfo.find('.issue-phone-1').text('');
        blInfo.find('.issue-phone-2').text('');
        blInfo.find('.issue-login').text('');
        blInfo.find('.issue-skype').text('');
        blInfo.find('.issue-email').text('');
        blInfo.find('.issueSelect').html('');
        blInfo.find('.issue-dateDelivery').text('');
        blInfo.find('.issue-address').text('');
        blInfo.find('.issue-info-delivery').text('');
        blInfo.find('.issue-order').html('');
        blInfo.find('.checkLogin').data({id:''}).prop('disabled', true);
        blInfo.find('.lookComment').val('');
    }
    function clearIssueInfo() {
        var blInfo = $('.issueInfo');

        blInfo.find('.issue-date').text('');
        blInfo.find('.issue-product-name').text('');
        blInfo.find('.issue-count').text('');
        blInfo.find('.issue-showroom').text('');
        blInfo.find('.issue-login').text('');
        blInfo.find('.issue-FIO').text('');
        blInfo.find('.issue-phone-1').text('');
        blInfo.find('.issue-phone-2').text('');
        blInfo.find('.issue-login').text('');
        blInfo.find('.issue-skype').text('');
        blInfo.find('.issue-email').text('');
        blInfo.find('.issueSelect').prop( "disabled", false ).val('');
        blInfo.find('.issue-dateDelivery').text('');
        blInfo.find('.issue-address').text('');
        blInfo.find('.issue-info-delivery').text('');
        blInfo.find('.issue-order').html('');
    }

</script>