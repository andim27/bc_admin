<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Заказы</h3>
</div>
<section class="panel panel-default">

    <div class="row">
        <div class="col-md-12">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active orders"><a href="#orders" data-toggle="tab">Заказы</a></li>
                    <li class="looseOrders"><a href="#looseOrders" data-toggle="tab">Незакреплённые заказы</a></li>
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
                                            Остатки в шоуруме
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
                                            Статус
                                        </th>
                                        <th>
                                            Дата отправки
                                        </th>
                                        <th>
                                            Количество
                                        </th>
                                        <th>
                                            Комментарий
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
                                        <td>ожидает обработки<a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                       <a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>ожидает обработки<a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                       <a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                     <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>ожидает обработки<a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                       <a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                     <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>ожидает обработки<a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                       <a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                     <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>отказано <a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                       <a class="editOrder m-l" href="#editOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="looseOrders">
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
                                            Остатки
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
                                            Статус
                                        </th>
                                        <th>
                                            Дата отправки
                                        </th>
                                        <th>
                                            Количество
                                        </th>
                                        <th>
                                            Комментарий
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>ожидает обработки</td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                       <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>ожидает обработки</td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                        <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>ожидает обработки</td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                        <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>ожидает обработки</td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                        <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>назначить <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>ожидает обработки</td>
                                        <td>9</td>
                                        <td>10</td>
                                        <td><a class="showComment m-l" href="#showComment" data-toggle="modal"><i class="fa fa-eye"></i></a>
                                        <a class="editLooseOrder m-l" href="#editLooseOrder" data-toggle="modal"><i class="fa fa-pencil"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
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

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Статус:</p>
                    </div>
                    <div class="col-md-9">
                        <select name="editOrderStatusSelect" class="editOrderStatusSelect w-195p inline form-control"> 
                            <option value="null">Доставлено</option> 
                            <option value="1">Отгружено</option> 
                        </select>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Название продукта:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderItemName">набор Life Balance</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Количество:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="editOrderCount" class="form-control w-69 inline editOrderCount">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t-xs">Дата отправки:</p>
                    </div>
                    <div class="col-md-9">
                        <input id="departureDate" class="departureDate input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 m-t-xs">
                        Комментарий
                        <textarea class="form-control orderComment m-t m-b" name="orderComment" id="orderComment" rows="5" placeholder=""></textarea>
                    </div>
                </div>

                 <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Логистика:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold logisticName">Новая почта</span>
                    </div>
                </div>

                 <div class="row m-t">
                    <div class="col-md-3">
                        <p class="m-t-xs">ТТН:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold logisticTTN">2100346534442</span>
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

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Название продукта:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderItemName">набор Life Balance</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Количество:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderCount">10</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p>Дата отправки:</p>
                    </div>
                    <div class="col-md-9">
                        <input id="departureDate" class="departureDate input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                    </div>
                </div>
                
                <div class="row m-t">
                    <div class="col-md-3">
                        <p>Комментарий:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold orderComment">Тут длинный комментарий</span>
                    </div>
                </div>

                 <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Логистика:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold logisticName">Новая почта</span>
                    </div>
                </div>

                 <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>ТТН:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold logisticTTN">2100346534442</span>
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


<div class="modal fade" id="editLooseOrder">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Заказ</h4>
            </div>
            <div class="modal-body">

                <div class="row m-t">
                    <div class="col-md-3">
                        <p class="m-t-xs">Шоурум:</p>
                    </div>
                    <div class="col-md-9">
                        <select name="editOrderShowroomSelect" class="editLooseOrderShowroomSelect w-195p inline form-control"> 
                            <option value="null">siren888</option> 
                            <option value="1">main</option> 
                        </select>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Статус:</p>
                    </div>
                    <div class="col-md-9">
                        <select name="editLooseOrderStatusSelect" class="editLooseOrderStatusSelect w-195p inline form-control"> 
                            <option value="null">Ожидает обработки</option> 
                            <option value="1">Отгружено</option> 
                        </select>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Название продукта:</p>
                    </div>
                    <div class="col-md-9">
                        <span class="font-bold editLooseOrderItemName">набор Life Balance</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p class="m-t-xs">Количество:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="editLooseOrderCount" class="form-control w-69 inline editLooseOrderCount">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t-xs">Дата отправки:</p>
                    </div>
                    <div class="col-md-9">
                        <input id="departureLooseDate" class="departureLooseDate input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 m-t-xs">
                        Комментарий
                        <textarea class="form-control editLooseOrderComment m-t m-b" name="editLooseOrderComment" id="editLooseOrderComment" rows="5" placeholder=""></textarea>
                    </div>
                </div>

                 <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>Логистика:</p>
                    </div>
                    <div class="col-md-9">
                         <input type="text" name="editLooseOrderlogisticName" class="form-control w-195p inline editLooseOrderlogisticName">
                    </div>
                </div>

                 <div class="row m-t m-b">
                    <div class="col-md-3">
                        <p>ТТН:</p>
                    </div>
                    <div class="col-md-9">
                        <input type="text" name="editLooseOrderlogisticTTN" class="form-control w-195p inline editLooseOrderlogisticTTN">
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

    $('#editOrder').on('shown.bs.modal', function(){
        // вызываеться перед открытием модального окна - выдёргиваем заказ из БД и подгружаем в окно
        
    })
   
    $('.modal').on('click','.orderSave',function(){
        // сохраняем редактирование приём товара

        $('#editOrder').modal('hide');
    });

    $('#editLooseOrder').on('shown.bs.modal', function(){
        // вызываеться перед открытием модального окна редактирования неприкреплённого заказа - выдёргиваем заказ из БД и подгружаем в окно
        
    })

    
    $('.modal').on('click','.editLooseOrderSave',function(){
        // сохраняем редактирование приём товара

        $('#editLooseOrder').modal('hide');
    });

    

   

</script>