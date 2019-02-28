<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Обработка заказов</h3>
</div>
<section class="panel panel-default">
    <div class="row">
        <div class="col-md-6">
            <div class="m">
                <span class="m-r">С</span>
                <input id="mainFrom" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['dateFrom']?>" data-date-format="yyyy-mm" data-filter="dateFrom"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months">
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
                    <li class="active orders"><a href="javascript:void(0);">Заказы</a></li>
                    <li class="looseOrders"><a href="/ru/business/showrooms/orders-non-distributed">Незакреплённые заказы</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="orders">
                        <!-- Заказы -->
                        <div class="table-responsive">
                            <table id="table-orders" class="table table-orders table-striped datagrid m-b-sm">
                                <thead>
                                <tr>
                                    <th>
                                        Дата заказа
                                    </th>
                                    <th>
                                        Название продукта
                                    </th>
                                    <th>
                                        Количество
                                    </th>
                                    <th>
                                        Статус
                                    </th>
                                    <th>
                                        Шоурум
                                    </th>
                                    <th>
                                        Страна
                                    </th>
                                    <th>
                                        Город
                                    </th>
                                    <th>
                                        Куда отправляем
                                    </th>
                                    <th>
                                        Покупатель
                                    </th>
                                    <th>
                                        Дата отправки
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($salesShowroom)){ ?>
                                    <?php foreach($salesShowroom as $itemSale){ ?>
                                        <tr data-sale-id="<?=$itemSale['saleId']?>">
                                            <td><?=$itemSale['dateCreate']?></td>
                                            <td><?=$itemSale['pack']?></td>
                                            <td><?=$itemSale['countPack']?></td>
                                            <td>
                                                <?=$itemSale['statusShowroom']?>
                                                <a class="editOrder m-l"><i class="fa fa-pencil"></i></a>
                                            </td>
                                            <td><?=$itemSale['showroomName']?></td>
                                            <td><?=$itemSale['country']?></td>
                                            <td><?=$itemSale['city']?></td>
                                            <td><?=$itemSale['addressDelivery']?></td>
                                            <td><?=$itemSale['secondName']?> <?=$itemSale['firstName']?> (<?=$itemSale['login']?>)</td>
                                            <td><?=$itemSale['dateSend']?></td>
                                            <td>
                                                <a class="showComment m-l" href="#showComment" data-toggle="modal">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="editOrder">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Заказ</h4>
            </div>
            <div class="modal-body">
                <form action="" name="editOrderForm" class="editOrderForm">
                    <div class="row">
                        <div class="col-md-3">
                            <p>Дата заказа:</p>
                        </div>
                        <div class="col-md-9">
                            <span class="font-bold orderDate"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>Шоу-рум:</p>
                        </div>
                        <div class="col-md-9">
                            <span class="font-bold orderShowroom"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>Страна:</p>
                        </div>
                        <div class="col-md-9">
                            <span class="font-bold orderCountry"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>Город:</p>
                        </div>
                        <div class="col-md-9">
                            <span class="font-bold orderCity"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>Куда отправляем:</p>
                        </div>
                        <div class="col-md-9">
                            <span class="font-bold orderDeliveryAddress"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>Покупатель:</p>
                        </div>
                        <div class="col-md-9">
                            <span class="font-bold orderCustomer"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>телефон1:</p>
                        </div>
                        <div class="col-md-3">
                            <span class="font-bold orderCustomerPhone1"></span>
                        </div>
                        <div class="col-md-2">
                            <p>Логин:</p>
                        </div>
                        <div class="col-md-3">
                            <span class="font-bold orderCustomerLogin"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>телефон2:</p>
                        </div>
                        <div class="col-md-3">
                            <span class="font-bold orderCustomerPhone2"></span>
                        </div>
                        <div class="col-md-2">
                            <p>Скайп:</p>
                        </div>
                        <div class="col-md-3">
                            <span class="font-bold orderCustomerSkype"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-6 col-md-2">
                            <p>Email:</p>
                        </div>
                        <div class="col-md-3">
                            <span class="font-bold orderCustomerEmail"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p>Заказано:</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-unstiled orderItemDetails">
                            </ul>
                        </div>
                    </div>

                    <div class="row m-b">
                        <div class="col-md-3">
                            <p class="m-t-xs">Статус:</p>
                        </div>
                        <div class="col-md-9">
                            <select name="editOrderStatusSelect" class="editOrderStatusSelect w-195p inline form-control"></select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p class="m-t-xs">Дата отправки:</p>
                        </div>
                        <div class="col-md-9">
                            <span class="font-bold orderDepartureDate"></span>
                        </div>
                    </div>

                    <div class="row m-b">
                        <div class="col-md-3">
                            <p class="m-t-xs">Способ доставки:</p>
                        </div>
                        <div class="col-md-9">
                            <input type="text" name="orderlogisticName" class="form-control w-195p inline orderlogisticName">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p class="m-t-xs">Номер декларации:</p>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="orderlogisticTTN" class="form-control inline orderlogisticTTN">
                        </div>

                        <div class="col-md-3">
                            <a href="#">Добавить фото</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p class="m-t-xs">Ориентировочная дата доставки:</p>
                        </div>
                        <div class="col-md-3">
                            <input id="orderCommingDate" class="orderCommingDate input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 m-t-xs">
                            Комментарий
                            <textarea class="form-control orderComment m-t m-b" name="orderComment" id="orderComment" rows="5" placeholder=""></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="col-sm-8 col-sm-offset-2 form-group">
                                <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                                <a class="btn btn-success orderSave">Сохранить</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showComment">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Заказ</h4>
            </div>
            <div class="modal-body">


                <div class="row">
                    <div class="col-md-3">
                        <p>Дата заказа:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderDate">12-03-2013</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Шоу-рум:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderShowroom">трайнова ю п</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Страна:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderCountry">Россия</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Город:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderCity">Москва</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Куда отправляем:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderDeliveryAddress">Москва, Калугина 23</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Покупатель:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderCustomer">Иванов Иван Иванович</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>телефон1:</p>
                    </div>
                    <div class="col-md-3">
                        <span class="font-bold orderCustomerPhone1">+78854555454</span>
                    </div>
                    <div class="col-md-2">
                        <p>Логин:</p>
                    </div>
                    <div class="col-md-3">
                        <span class="font-bold orderCustomerLogin">ivanov</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>телефон2:</p>
                    </div>
                    <div class="col-md-3">
                        <span class="font-bold orderCustomerPhone2">+78854555454</span>
                    </div>
                    <div class="col-md-2">
                        <p>Скайп:</p>
                    </div>
                    <div class="col-md-3">
                        <span class="font-bold orderCustomerSkype">ivanov</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-6 col-md-2">
                        <p>Email:</p>
                    </div>
                    <div class="col-md-3">
                        <span class="font-bold orderCustomerEmail">ivanov@loshara.com</span>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-3">
                        <p>Заказано:</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-unstiled orderItemDetails">
                            <li>Два прибора Life Balance</li>
                            <li>Два прибора Life Balance</li>
                        </ul>
                    </div>
                </div>

                <div class="row m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Статус:</p>
                    </div>
                    <div class="col-md-9">
                                    <span class="font-bold orderStatus">
                                        Доставлено
                                    </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t-xs">Дата отправки:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderDepartureDate">15.04.2016</span>
                    </div>
                </div>

                <div class="row m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Способ доставки:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderLogisticName">Новая почта</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t-xs">Номер декларации:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderLogisticTTN">2100346534442</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t-xs">Ориентировочная дата доставки:</p>
                    </div>
                    <div class="col-md-3">
                        <span class="font-bold orderCommingDate">12-02-2013</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Комментарий:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderComment">Тут длинный комментарий</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-success"  data-dismiss="modal">Выход</a>
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


    $('#table-orders').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        lengthChange: false,
        info: false,
        order: [[0, 'asc']]
    });

    $('table').on('click','.editIssue',function(){

        clearEditOrderForm();

        var blForm = $('.editOrderForm');

        $.ajax({
            url: '/ru/business/sale/get-sale',
            type: 'POST',
            data: {saleId:$(this).closest('tr').data('sale-id')},
            beforeSend: function () {
                blInfo.find('.modal-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                blInfo.find('.loader').remove();
            },
            success: function(msg){
                if(msg.error === ''){

                    var blForm = $('.editOrderForm');

                    blForm.find('.orderDate').text(msg.dateCreate);
                    blForm.find('.orderShowroom').text(msg.showroomName);
                    blForm.find('.orderCountry').text('');
                    blForm.find('.orderCity').text('');
                    blForm.find('.orderDeliveryAddress').text('');
                    blForm.find('.orderCustomer').text(msg.secondName + ' ' + msg.firstName);
                    blForm.find('.orderCustomerPhone1').text(msg.phone1);
                    blForm.find('.orderCustomerPhone2').text(msg.phone2);
                    blForm.find('.orderCustomerLogin').text(msg.login);
                    blForm.find('.orderCustomerSkype').text(msg.skype);
                    blForm.find('.orderCustomerEmail').text(msg.email);

                    blForm.find('.orderItemDetails').html('');

                    blForm.find('.editOrderStatusSelect').val('');

                    blForm.find('.orderDepartureDate').text('');

                    blForm.find('.orderlogisticName').val('');
                    blForm.find('.orderlogisticTTN').val('');
                    blForm.find('.orderCommingDate').val('');
                    blForm.find('.orderComment').val('');


                    // blInfo.find('.saveEditedIssue').data({id:msg.saleId});
                    //
                    // blInfo.find('.issue-date').text(msg.dateCreate);
                    // blInfo.find('.issue-product-name').text(msg.pack);
                    // blInfo.find('.issue-count').text(msg.count);
                    // blInfo.find('.issue-showroom').text(msg.showroomName);
                    // blInfo.find('.issue-FIO').text(msg.secondName + ' ' + msg.firstName);
                    // blInfo.find('.issue-phone-1').text(msg.phone1);
                    // blInfo.find('.issue-phone-2').text(msg.phone2);
                    //
                    // blInfo.find('.issue-login').text(msg.login);
                    // blInfo.find('.issue-skype').text(msg.skype);
                    // blInfo.find('.issue-email').text(msg.email);
                    //
                    // var statusShowroomOptions = '';
                    // availableStatusShowroom[msg.statusShowroom].forEach(function(item) {
                    //     statusShowroomOptions += '<option value="'+item+'">'+listStatusShowroom[item]+'</option>'
                    // });
                    //
                    // blInfo.find('.issueSelect').html(statusShowroomOptions).val(msg.statusShowroom);
                    // blInfo.find('.saveEditedIssue').prop('disabled',false);
                    //
                    // if(msg.statusShowroom == 'delivered_company'
                    //     || msg.statusShowroom == 'delivered') {
                    //     blInfo.find('.saveEditedIssue').prop('disabled',true);
                    // }
                    //
                    // blInfo.find('.issue-dateDelivery').text(msg.dateDelivery);
                    // blInfo.find('.issue-address').text(msg.addressDelivery);
                    //
                    // blInfo.find('.lookComment').text(msg.commentShowroom);
                    //
                    // var blOrder = blInfo.find('.issue-order');
                    // blOrder.html('');
                    // $.each(msg.products, function( k, v ) {
                    //
                    //     blProduct = '<li>'+v.name;
                    //
                    //     if(msg.statusShowroom == 'waiting' || msg.statusShowroom == 'sending_showroom'){
                    //         //blProduct += '<a href="javascript:void(0);" class="fromBalance pull-right" data-product-id="'+v.id+'">Выдать с моего шоу-рума демонстрационый образец</a><span class="spanIssued pull-right m-r"></span></li>'
                    //     }
                    //
                    //     blOrder.append(blProduct);
                    // });
                }
            }
        });

        $('#editOrder').show();
    });


    $('.modal').on('click','.orderSave',function(){
        // сохраняем редактирование приём товара

        $('#editOrder').modal('hide');
    });

    function clearEditOrderForm() {
        var blForm = $('.editOrderForm');

        blForm.find('.orderDate').text('');
        blForm.find('.orderShowroom').text('');
        blForm.find('.orderCountry').text('');
        blForm.find('.orderCity').text('');
        blForm.find('.orderDeliveryAddress').text('');
        blForm.find('.orderCustomer').text('');
        blForm.find('.orderCustomerPhone1').text('');
        blForm.find('.orderCustomerPhone2').text('');
        blForm.find('.orderCustomerLogin').text('');
        blForm.find('.orderCustomerSkype').text('');
        blForm.find('.orderCustomerEmail').text('');

        blForm.find('.orderItemDetails').html('');

        blForm.find('.editOrderStatusSelect').val('');

        blForm.find('.orderDepartureDate').text('');

        blForm.find('.orderlogisticName').val('');
        blForm.find('.orderlogisticTTN').val('');
        blForm.find('.orderCommingDate').val('');
        blForm.find('.orderComment').val('');
    }


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

</script>