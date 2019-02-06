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
            <select name="compensationSelect" class="compensationSelect form-control m"> 
                <option value="null">Страна / Город / Логин / ФИО</option> 
                <option value="1">Россия / Москва / firely / Вильховая ИИ</option> 
                <option value="2" >Россия / Москва / firely / Вильховая ИИ</option> 
                <option value="3">Россия / Москва / firely / Вильховая ИИ</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active issueGoods"><a href="#issueGoods" data-toggle="tab">Выдача</a></li>
                    <li class="receptionGoods"><a href="#receptionGoods" data-toggle="tab">Приём</a></li>
                    <li class="orderGoods"><a href="#orderGoods" data-toggle="tab">Заказ</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="issueGoods"> 
                        <!-- Выдача -->
                        <div class="row">
                            <div class="col-md-12 m-b">
                                <span class="m-r">С</span>
                                <input id="issueFrom" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                                <span class="m-r m-l">ПО</span>
                                <input id="issueTo" class="input-s datepicker-input inline input-showroom form-control text-center  m-r" size="16" type="text" value="22-01-2019" data-date-format="dd-mm-yyyy">
                                <input type="checkbox" id="showOnlyNotIssue">
                                <span class="m-l-xs">Отобразить только невыданные</span>
                            </div>
                        </div>                        
                        <div class="table-responsive">
                            <table id="table-issue" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Дата
                                        </th>
                                        <th>
                                            Время
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
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                        <td><a class="editIssue" href="#"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                        <td><a class="editIssue" href="#"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                        <td><a class="editIssue" href="#"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="button" class="btn btn-success pull-right issueOrder m-sm" value="Подобрать заказ">
                            </div>
                        </div>

                        <div class="panel panel-default issueInfo">
                            <div class="panel-body">
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Дата заявки:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-date m-l m-r">14.02.2019</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Логин:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-login m-l m-r">mai</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>ФИО:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-FIO m-l m-r">Сидоров Иван</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Телефоны:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-phones m-l m-r">+54545454545, +5454545454</span>
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
                                        <select name="issueSelect" class="issueSelect w-50 form-control m"> 
                                            <option value="null">Доставлено</option> 
                                            <option value="1">Отгружено</option> 
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Дата доставки:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-dateDelivery m-l m-r">10.12.18</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Адрес:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-address m-l m-r">Архитектора бекетова 23</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="button" class="btn btn-sm btn-success pull-right saveEditedIssue m-n" value="Сохранить">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default issueOrderRow">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" placeholder="Номер накладной">
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
                                    <div class="col-md-3">
                                        <p>Дата заявки:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-date m-l m-r">14.02.2019</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Логин:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-login m-l m-r">mai</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>ФИО:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-FIO m-l m-r">Сидоров Иван</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Телефоны:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-phones m-l m-r">+54545454545, +5454545454</span>
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
                                        <select name="issueSelect" class="issueSelect w-50 form-control m"> 
                                            <option value="null">Доставлено</option> 
                                            <option value="1">Отгружено</option> 
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Дата доставки:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-dateDelivery m-l m-r">10.12.18</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <p>Адрес:</p>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="font-bold issue-address m-l m-r">Архитектора бекетова 23</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="button" class="btn btn-sm btn-success pull-right checkLogin m-n" value="Подобрать заказ">
                                    </div>

                                </div>
                                
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="receptionGoods">
                        <!-- Приём -->
                        <div class="row">
                            <div class="col-md-12 m-b">
                                <span class="m-r">С</span>
                                <input id="receptionFrom" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                                <span class="m-r m-l">ПО</span>
                                <input id="receptionTo" class="input-s datepicker-input inline input-showroom form-control text-center m-r" size="16" type="text" value="22-01-2019" data-date-format="dd-mm-yyyy">
                                <input type="checkbox" id="showOnlyNotReception">
                                <span class="m-l-xs">Отобразить только невыданные</span>
                            </div>
                        </div>                        
                        <div class="table-responsive">
                            <table id="table-reception" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Дата
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
                                            Серийный номер
                                        </th>
                                        <th>
                                            Статус
                                        </th>
                                        <th>
                                            Гарантия
                                        </th>
                                        <th>
                                           
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td><a class="editReceiptedGoods" href="#editReceiptedGoods" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td><a class="editReceiptedGoods" href="#editReceiptedGoods" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td><a class="editReceiptedGoods" href="#editReceiptedGoods" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="button" class="btn btn-success pull-right receiptGoods m-sm" value="Принять товар">
                            </div>
                        </div>

                        <div class="panel panel-default receiptGoodsRow">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="Логин">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="Телефон">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" placeholder="Серийный номер">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="button" class="btn btn-success findReceiptOrder m-n" value="Найти">
                                        
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="table-responsive m-t">
                                        <table id="table-reception-goods" class="table table-reception-goods table-striped datagrid m-b-sm">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Дата покупки
                                                    </th>
                                                    <th>
                                                        Название продукта
                                                    </th>
                                                    <th>
                                                        Серийный номер
                                                    </th>
                                                    <th>
                                                        Статус
                                                    </th>
                                                    <th>
                                                        Был в ремонте раз
                                                    </th>
                                                    <th>
                                                        Дата принятия в ремонт
                                                    </th>
                                                    <th>
                                                        Гарантия
                                                    </th>
                                                    <th>
                                                        Требуеться оплата
                                                    </th>
                                                    <th>
                                                    
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>4</td>
                                                    <td>5</td>
                                                    <td>6</td>
                                                    <td>7</td>
                                                    <td>8</td>
                                                    <td><a class="receiptionGoodsEdit" href="#receiptionGoodsEdit"  data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>4</td>
                                                    <td>5</td>
                                                    <td>6</td>
                                                    <td>7</td>
                                                    <td>8</td>
                                                    <td><a class="receiptionGoodsEdit" href="#receiptionGoodsEdit"  data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>4</td>
                                                    <td>5</td>
                                                    <td>6</td>
                                                    <td>7</td>
                                                    <td>8</td>
                                                    <td><a class="receiptionGoodsEdit" href="#receiptionGoodsEdit"  data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="orderGoods">
                        <!-- Заказ -->                 
                        <div class="table-responsive">
                            <table id="table-order" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Дата
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
                                            Ориентировочная дата прибытия
                                        </th>
                                        <th>
                                            Дата отправки
                                        </th>
                                        <th>
                                            Подробности
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td><a class="infoOrder" href="#">Просмотр</a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td><a class="infoOrder" href="#">Просмотр</a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td><a class="infoOrder" href="#">Просмотр</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="button" class="btn btn-success pull-right makeOrder m-sm" value="Сделать заказ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

</section>

<div class="modal fade" id="receiptionGoodsEdit">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Приём товара</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-3">
                        <p>Дата покупки:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold dateOrder m-l m-r">14.02.2019</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Название продукта:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold itemOrder m-l m-r">набор Life Balance</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Серийный номер:</p>
                    </div>
                    <div class="col-md-9">
                        <a href="#" class="m-l">Добавить</a>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Статус:</p>
                    </div>
                    <div class="col-md-9">
                        <select name="receiptionStatusSelect" class="receiptionStatusSelect w-195p inline form-control m-l"> 
                            <option value="null">Доставлено</option> 
                            <option value="1">Отгружено</option> 
                        </select>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Гарантия:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold receptionWarranty m-l m-r">Да</span>
                        <a href="#" class="pull-right">Не гарантийный случай</a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        Комментарий
                        <textarea class="form-control receptionComment m-t m-b" name="receptionComment" id="receptionComment" rows="5" placeholder=""></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success receptionSave">Сохранить</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editReceiptedGoods">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Редактирование</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-3">
                        <p>Дата покупки:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold dateReceipted m-l m-r">14.02.2019</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Логин:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold loginReceipted m-l m-r">main</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>ФИО:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold fioReceipted m-l m-r">Абдула Абрамович КамЕнский</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Телефон:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold phoneReceipted m-l m-r">03</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Название продукта:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold itemReceipted m-l m-r">набор Life Balance</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Серийный номер:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold serialReceipted m-l m-r">3424234234</span>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-3">
                        <p>Статус:</p>
                    </div>
                    <div class="col-md-9">
                        <select name="receiptedStatusSelect" class="receiptedStatusSelect w-195p inline form-control m-l"> 
                            <option value="null">Доставлено</option> 
                            <option value="1">Отгружено</option> 
                        </select>
                    </div>
                </div>

                <div class="row m-b m-t">
                
                    <div class="col-md-3">
                        <p>Гарантия:</p>
                    </div>
                    <div class="col-md-9">
                        <select name="receiptedStatusSelect" class="receiptedStatusSelect w-195p inline form-control m-l"> 
                            <option value="null">Да</option> 
                            <option value="1">Нет</option> 
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        Комментарий
                        <textarea class="form-control receptionComment m-t m-b" name="receptionComment" id="receptionComment" rows="5" placeholder=""></textarea>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success editReceptionSave">Сохранить</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="historyEditCompensation">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Редактирование</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       <p>Шоу-рум <span class="font-bold cityShowroom m-l m-r">Новосибирск</span> Логин <span class="font-bold loginShowroom  m-l m-r">main</span>
                       </p> 
                    </div>
                    <div class="col-md-12 m-b-sm">
                        <h4>Иванов Иван Иванович</h4>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <select name="compensationHistoryTypeSelect" class="compensationHistoryTypeSelect form-control m-b"> 
                            <option value="1">Безнал</option> 
                            <option value="2">Нал</option> 
                            <option value="3">Бонусы</option> 
                            <option value="4" selected>Тугрики</option>
                            <option value="5">Виртуальное "Спасибо"</option>
                            <option value="6">Хер вам а не пополнение</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="compensationHistoryEditAmount" id="compensationHistoryEditAmount" placeholder="Сумма">
                    </div>
                    <div class="col-md-12">
                        Комментарий
                        <textarea class="form-control compensationHistoryEditComment m-t m-b" name="compensationHistoryEditComment" id="compensationHistoryEditComment" rows="5" placeholder=""></textarea>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success editHistoryCompensation">Сохранить</a>
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

    $('table').on('click','.historyCompensation',function(){
        $('.mainLi, .historyLi').toggleClass('active');
    });

   

    $('.modal').on('click','.addTopUpCompensation',function(){
        // сохраняем пополнение
    });   

    $('.modal').on('click','.editHistoryCompensation',function(){
        // редактируем историю
    });

    $('.issueInfo').on('click','.fromBalnce',function(){

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
        // и отображаем выдачу товара
        //$('.issueInfo').val('Изменить');
        $('.issueOrderRow').hide();
        $('.issueOrderDetail').hide();
        $('.issueInfo').show();
    })

    $('#content').on('click','.checkIssue',function(){ 

    // нажимаем на подобрать заказа
        $('.issueOrderDetail').toggle();
    })

    

    $('#content').on('click','.issueOrder',function(){ 
        
        
        $('.issueOrderDetail').hide();
        $('.issueInfo').hide();
        $('.issueOrderRow').toggle();
    })

    $('#content').on('click','.receiptGoods',function(){ 

        // нажимаем на подобрать заказа
        $('.receiptGoodsRow').toggle();
    })

    $('#content').on('click','.findReceiptOrder',function(){ 

        //ищем товар в БД и выдаём в таблицу ниже

        $('.table-reception-goods').toggle();
    })


    

</script>