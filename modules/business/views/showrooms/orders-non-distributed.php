<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Незакреплённые заказы</h3>
</div>
<section class="panel panel-default">

    <div class="row">
        <div class="col-md-12">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active orders"><a href="/ru/business/showrooms/orders-company">Заказы</a></li>
                    <li class="looseOrders"><a href="javascript:void(0);">Незакреплённые заказы</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="looseOrders">
                        <!-- Незакреплённые заказы -->
                        <div class="table-responsive">
                            <table id="table-loose-orders" class="table table-loose-orders table-striped datagrid m-b-sm">
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
                                    <th>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
<!--                                <tr>-->
<!--                                    <td>1</td>-->
<!--                                    <td>2</td>-->
<!--                                    <td>3</td>-->
<!--                                    <td>Ожидает</td>-->
<!--                                    <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>-->
<!--                                    <td>6</td>-->
<!--                                    <td>7</td>-->
<!--                                    <td>Курьер, Москва, Калугина 23</td>-->
<!--                                    <td>Иванов И.И.</td>-->
<!--                                    <td>10</td>-->
<!--                                    <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a></td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>1</td>-->
<!--                                    <td>2</td>-->
<!--                                    <td>3</td>-->
<!--                                    <td>Ожидает</td>-->
<!--                                    <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>-->
<!--                                    <td>6</td>-->
<!--                                    <td>7</td>-->
<!--                                    <td>Курьер, Москва, Калугина 23</td>-->
<!--                                    <td>Иванов И.И.</td>-->
<!--                                    <td>10</td>-->
<!--                                    <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a></td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>1</td>-->
<!--                                    <td>2</td>-->
<!--                                    <td>3</td>-->
<!--                                    <td>Ожидает</td>-->
<!--                                    <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>-->
<!--                                    <td>6</td>-->
<!--                                    <td>7</td>-->
<!--                                    <td>Курьер, Москва, Калугина 23</td>-->
<!--                                    <td>Иванов И.И.</td>-->
<!--                                    <td>10</td>-->
<!--                                    <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a></td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>1</td>-->
<!--                                    <td>2</td>-->
<!--                                    <td>3</td>-->
<!--                                    <td>Ожидает</td>-->
<!--                                    <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>-->
<!--                                    <td>6</td>-->
<!--                                    <td>7</td>-->
<!--                                    <td>Курьер, Москва, Калугина 23</td>-->
<!--                                    <td>Иванов И.И.</td>-->
<!--                                    <td>10</td>-->
<!--                                    <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a></td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td>1</td>-->
<!--                                    <td>2</td>-->
<!--                                    <td>3</td>-->
<!--                                    <td>Ожидает</td>-->
<!--                                    <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>-->
<!--                                    <td>6</td>-->
<!--                                    <td>7</td>-->
<!--                                    <td>Курьер, Москва, Калугина 23</td>-->
<!--                                    <td>Иванов И.И.</td>-->
<!--                                    <td>10</td>-->
<!--                                    <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a></td>-->
<!--                                </tr>-->
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="editLooseOrder">
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

                <div class="row m-b">
                    <div class="col-md-3">
                        <p>Шоу-рум:</p>
                    </div>
                    <div class="col-md-9">
                        <select name="looseOrderShowroomSelect" class="looseOrderShowroomSelect w-195p inline form-control">
                            <option value="null">siren888</option>
                            <option value="1">main</option>
                        </select>
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
                    <div class="col-md-3">
                        <p>телефон3:</p>
                    </div>
                    <div class="col-md-3">
                        <span class="font-bold orderCustomerPhone3">+78854555454</span>
                    </div>
                    <div class="col-md-2">
                        <p>Email:</p>
                    </div>
                    <div class="col-md-3">
                        <span class="font-bold orderCustomerEmail">ivanov@loshara.com</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>телефон4:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderCustomerPhone4">+78854555454</span>
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
                        <select name="editLooseOrderStatusSelect" class="editLooseOrderStatusSelect w-195p inline form-control">
                            <option value="null">Доставлено</option>
                            <option value="1">Отгружено</option>
                        </select>
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
                        <input type="text" name="looseOrderlogisticName" class="form-control w-195p inline looseOrderlogisticName">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t-xs">Номер декларации:</p>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="looseOrderlogisticTTN" class="form-control inline looseOrderlogisticTTN">
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
                        <textarea class="form-control looseOrderComment m-t m-b" name="looseOrderComment" id="looseOrderComment" rows="5" placeholder=""></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success editLooseOrderSave">Сохранить</a>
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

    $('#table-loose-orders').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        lengthChange: false,
        info: false
    });


    $('#editLooseOrder').on('shown.bs.modal', function(){
        // вызываеться перед открытием модального окна редактирования неприкреплённого заказа - выдёргиваем заказ из БД и подгружаем в окно

    });


    $('.modal').on('click','.editLooseOrderSave',function(){
        // сохраняем редактирование приём товара

        $('#editLooseOrder').modal('hide');
    });


</script>