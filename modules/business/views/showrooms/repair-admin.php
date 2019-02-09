<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\models\Settings;
    use yii\helpers\ArrayHelper;

    
    $listCountry = Settings::getListCountry();

?>

<div class="m-b-md">
    <h3 class="m-b-none">Ремонт товаров (Админ)</h3>
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

                 <div class="row">
                    <div class="col-sm-12">
                        <a class="btn btn-success pull-right addServiceCenter m-sm" href="#addServiceCenter" data-toggle="modal">
                            Добавить сервисный центр
                        </a>
                    </div>
                </div>

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
                                <th>

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
                                <th>
                                     <a class="editServiceCenter m-l" href="#editServiceCenter" data-toggle="modal">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="deleteServiceCenter m-l" href="#deleteServiceCenter"  data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Технорем <a class="showServiceInfo m-l" href="#showServiceInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>Ленина 128 кв. 18</td>
                                <td>+7960123456</td>
                                <th>
                                     <a class="editServiceCenter m-l" href="#editServiceCenter" data-toggle="modal">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="deleteServiceCenter m-l" href="#deleteServiceCenter"  data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Технорем <a class="showServiceInfo m-l" href="#showServiceInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>Ленина 128 кв. 18</td>
                                <td>+7960123456</td>
                                <th>
                                    <a class="editServiceCenter m-l" href="#editServiceCenter" data-toggle="modal">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="deleteServiceCenter m-l" href="#deleteServiceCenter"  data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </th>
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

<div class="modal fade" id="deleteServiceCenter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Удалить сервисный центр</h4>
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
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success deleteService">Удалить</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editServiceCenter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Редактировать сервисный центр</h4>
            </div>
            <div class="modal-body">

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Название:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterName" class="form-control serviceCenterName" value="Технорем">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Страна:</p>
                    </div>
                    <div class="col-md-9">
                        <select class="form-control serviceCenterCountry" name="serviceCenterCountry">
                            <option value="by">Belarus</option>
                            <option value="eg" selected="">Egypt</option>
                            <option value="de">Germany</option>
                            <option value="gr">Greece</option>
                            <option value="il">Israel</option>
                            <option value="kz">Kazakhstan</option>
                            <option value="kg">Kyrgyzstan</option>
                            <option value="lv">Latvia</option>
                            <option value="lt">Lithuania</option>
                            <option value="pl">Poland</option>
                            <option value="ru">Russian Federation</option>
                            <option value="rs">Serbia</option>
                            <option value="es">Spain</option>
                            <option value="tr">Turkey</option>
                            <option value="ua">Ukraine</option>
                        </select>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Город:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterCity" class="form-control serviceCenterCity" value="Астана">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Адрес:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterAddress" class="form-control serviceCenterAddress" value="ул. Ленина д.3 щф 3">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Почтовый индекс:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterZip" class="form-control serviceCenterZip" value="31232">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Телефон:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterPhone" class="form-control serviceCenterPhone" value="+71212343542">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Контактное лицо:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterContact" class="form-control serviceCenterContact" value="Николай Степанович БлаБлаБла">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Примечание:</p>
                    </div>
                    <div class="col-md-9">
                         <textarea class="form-control serviceCenterNote" rows="5" name="comment" placeholder="">Работаем только по будням с 10 до 22</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success editService">Сохранить</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addServiceCenter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Добавить сервисный центр</h4>
            </div>
            <div class="modal-body">

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Название:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterName" class="form-control serviceCenterName" placeholder="Введите название сервисного центра">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Страна:</p>
                    </div>
                    <div class="col-md-9">
                        <select class="form-control serviceCenterCountry" name="serviceCenterCountry">
                            <option value="null" selected="">Выберите страну</option>
                            <option value="by">Belarus</option>
                            <option value="eg">Egypt</option>
                            <option value="de">Germany</option>
                            <option value="gr">Greece</option>
                            <option value="il">Israel</option>
                            <option value="kz">Kazakhstan</option>
                            <option value="kg">Kyrgyzstan</option>
                            <option value="lv">Latvia</option>
                            <option value="lt">Lithuania</option>
                            <option value="pl">Poland</option>
                            <option value="ru">Russian Federation</option>
                            <option value="rs">Serbia</option>
                            <option value="es">Spain</option>
                            <option value="tr">Turkey</option>
                            <option value="ua">Ukraine</option>
                        </select>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Город:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterCity" class="form-control serviceCenterCity" placeholder="Введите город">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Адрес:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterAddress" class="form-control serviceCenterAddress" placeholder="Введите адрес">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Почтовый индекс:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterZip" class="form-control serviceCenterZip" placeholder="Введите индекс">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Телефон:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterPhone" class="form-control serviceCenterPhone" placeholder="Введите номер телефона">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Контактное лицо:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="serviceCenterContact" class="form-control serviceCenterContact" placeholder="ФИО ответственного">
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Примечание:</p>
                    </div>
                    <div class="col-md-9">
                         <textarea class="form-control serviceCenterNote" rows="5" name="comment" placeholder=""></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success addService">Добавить</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#showServiceInfo').on('shown.bs.modal', function () {
        // вызываеться перед открытием модального окна просмотра инфы по сервисному центру - выдёргиваем инфу по сервисному центру из БД и подгружаем в окно
        console.log('showServiceInfo');

    })

    $('#deleteServiceCenter').on('shown.bs.modal', function () {
        // вызываеться перед открытием модального окна удаления сервисного центра - выдёргиваем инфу по сервисному центру из БД и подгружаем в окно

        console.log('deleteServiceCenter');
    })

    
    $('#editServiceCenter').on('shown.bs.modal', function () {
        // вызываеться перед открытием модального окна htlfrnbhjdfybz сервисного центра - выдёргиваем инфу по сервисному центру из БД и подгружаем в окно

        console.log('editServiceCenter');

    })


</script>