<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Ремонт товаров</h3>
</div>
<section class="panel panel-default">

    <header class="panel-heading bg-light no-borders">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#main" data-toggle="tab">Оборудование в ремонте</a></li>
            <li><a href="#serviceList" data-toggle="tab">Список сервисных центр</a></li>
        </ul>
    </header>
    <div class="panel-body">

        <div class="tab-content">
            <div class="tab-pane active" id="main"> 
                <!-- Оборудование в ремонте -->

                <div class="table-responsive">
                    <table id="table-under-repair" class="table table-under-repair table-striped datagrid m-b-sm">
                        <thead>
                            <tr>
                                <th>
                                    №
                                </th>
                                <th>
                                    Страна
                                </th>
                                <th>
                                    Город
                                </th>
                                <th>
                                    ФИО
                                </th>
                                <th>
                                    Название продукта
                                </th>
                                <th>
                                    Серийный номер
                                </th>
                                <th>
                                    Гарантия
                                </th>
                                <th>
                                    Дата отправки в ремонт
                                </th>
                                <th>
                                    Сервисный центр
                                </th>
                                <th>
                                    Дата принятия в ремонт
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Vasya ass</td>
                                <td>Life Expert</td>
                                <td>222224444</td>
                                <td>Да</td>
                                <td>24.01.2019</td>
                                <td>Технорем <a class="showServiceInfo m-l" href="#showServiceInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>28.01.2019</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Vasya ass</td>
                                <td>Life Expert</td>
                                <td>222224444</td>
                                <td>Да</td>
                                <td>24.01.2019</td>
                                <td>Технорем <a class="showServiceInfo m-l" href="#showServiceInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>28.01.2019</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Vasya ass</td>
                                <td>Life Expert</td>
                                <td>222224444</td>
                                <td>Да</td>
                                <td>24.01.2019</td>
                                <td>Технорем <a class="showServiceInfo m-l" href="#showServiceInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>28.01.2019</td>
                            </tr>
                        </tbody>
                    </table>
                </div>        
            </div>

            <div class="tab-pane" id="serviceList"> 
                <!-- Список сервисных центров -->
                <div class="table-responsive">
                    <table id="table-service-list" class="table table-service-list table-striped datagrid m-b-sm">
                        <thead>
                            <tr>
                                <th>
                                    №
                                </th>
                                <th>
                                    Страна
                                </th>
                                <th>
                                    Город
                                </th>
                                <th>
                                    Сервисный центр
                                </th>
                                <th>
                                    Адрес
                                </th>
                                <th>
                                    Телефон
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Технорем <a class="showServiceInfo m-l" href="#showServiceInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>Ленина 128 кв. 18</td>
                                <td>+7960123456</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Технорем <a class="showServiceInfo m-l" href="#showServiceInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>Ленина 128 кв. 18</td>
                                <td>+7960123456</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Технорем <a class="showServiceInfo m-l" href="#showServiceInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>Ленина 128 кв. 18</td>
                                <td>+7960123456</td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>

        </div>


    </div>

</section>

<div class="modal fade" id="showServiceInfo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Сервисный центр</h4>
            </div>
            <div class="modal-body">

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Название:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serviceCenterName">Технорем</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Страна:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serviceCenterCountry">Казахстан</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Город:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serviceCenterCity">Астана</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Адрес:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serviceCenterAddress">ул. Ленина д.3 щф 3</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Почтовый индекс:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serviceCenterZip">31232</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Телефон:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serviceCenterPhone">+71212343542</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Контактное лицо:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serviceCenterContact">Николай Степанович БлаБлаБла</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Примечание:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serviceCenterNote">Работаем только по будням с 10 до 22</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-success" data-dismiss="modal">Закрыть</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $('#showServiceInfo').on('shown.bs.modal', function () {
        // вызываеться перед открытием модального окна - выдёргиваем инфу по сервисному центру из БД и подгружаем в окно

        console.log('showServiceInfo');

    })


</script>