<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Таблица компенсаций</h3>
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
                    <li class="active"><a href="#main" data-toggle="tab">Сводная</a></li>
                    <li><a href="#profit" data-toggle="tab">Начисления</a></li>
                    <li><a href="#purchases" data-toggle="tab">Покупки</a></li>
                    <li><a href="#onBalance" data-toggle="tab">Товар на балансе</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="main"> 
                        <!-- Сводная -->
                        <div class="row">
                            <div class="col-md-12 m-b">
                                <span class="m-r">С</span>
                                <input id="mainFrom" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                                <span class="m-r m-l">ПО</span>
                                <input id="mainTo" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="22-01-2019" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>                        
                        <div class="table-responsive">
                            <table id="table-main" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            <?=THelper::t('country')?>
                                        </th>
                                        <th>
                                            <?=THelper::t('city')?>
                                        </th>
                                        <th>
                                            Общий оборот
                                        </th>
                                        <th>
                                            Webwellness
                                        </th>
                                        <th>
                                            Vipcoin
                                        </th>
                                        <th>
                                            VIPVIP
                                        </th>
                                        <th>
                                            Начислений
                                        </th>
                                        <th>
                                            Выплачено безналом
                                        </th>
                                        <th>
                                            Скидка на лиц.сч.
                                        </th>
                                        <th>
                                            Остаток
                                        </th>
                                        <th class="text-center">
                                            Данные шоу-рума
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
                                        <td>10</td>
                                        <td class="text-center"><a href="#" class="editShowroomData" data-showroom="1"><i class="fa fa-pencil"></i></a></td>
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
                                        <td>10</td>
                                        <td class="text-center"><a href="#" class="editShowroomData" data-showroom="2"><i class="fa fa-pencil"></i></a></td>
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
                                        <td>10</td>
                                        <td class="text-center"><a href="#" class="editShowroomData" data-showroom="3"><i class="fa fa-pencil"></i></a></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="profit">
                        <!-- Начисления -->
                        <div class="row">
                            <div class="col-md-12 m-b">
                                <span class="m-r">С</span>
                                <input id="profitFrom" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                                <span class="m-r m-l">ПО</span>
                                <input id="profitTo" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="22-01-2019" data-date-format="dd-mm-yyyy">
                                <span class="m-r m-l">Логин</span>
                                <span class="font-bold loginProfit">Main</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table-profit" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Дата выплаты
                                        </th>
                                        <th>
                                            Выплачено безналом
                                        </th>
                                        <th>
                                            Скидки на лиц.сч.
                                        </th>
                                        <th>
                                            Оплата ремонта
                                        </th>
                                        <th>
                                            Остаток
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
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="purchases">
                        <!-- Покупки -->
                        <div class="row">
                            <div class="col-md-12 m-b">
                                <span class="m-r">С</span>
                                <input id="purchasesFrom" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                                <span class="m-r m-l">ПО</span>
                                <input id="purchasesTo" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="22-01-2019" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="table-purchases" class="table table-users table-striped datagrid m-b-sm">
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
                                            Начисление
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
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="tab-pane" id="onBalance">
                        <!-- Товар на балансе -->
                        <div class="row">
                            <div class="col-md-12 m-b">
                                <span class="m-r">С</span>
                                <input id="onBalanceFrom" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                                <span class="m-r m-l">ПО</span>
                                <input id="onBalanceTo" class="input-s datepicker-input inline input-showroom form-control text-center" size="16" type="text" value="22-01-2019" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="table-onBalance" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Название товара
                                        </th>
                                        <th>
                                            Отправлено
                                        </th>
                                        <th>
                                            В наличие
                                        </th>
                                        <th>
                                            Стоимость
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
   

</section>

<div class="modal fade" id="showroomData">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-body showroomWebData">
                <button type="button" class="close" data-dismiss="modal">x</button>
              
                <div class="col-md-12 m-b-sm">
                    <div class="col-md-12 m-t-sm">
                        <p>
                            Данные ШОУ-РУМА отображаемые на сайтах
                        </p>
                        <p>
                            Адрес в городе <b class="showroomCity">Москва</b>:
                        </p>
                        <input type="text" name="shoowroomAddress" class="shoowroomAddress w-100">
                    </div>
                    <div class="col-md-12">
                        <div class="row m-t-xs">
                            <div class="col-md-5">
                                День
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                С
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                До
                            </div>
                            <div class="col-md-3 no-padder text-center">
                                Выходной
                            </div>
                        </div>
                        <div class="row m-t-xs">
                            <div class="col-md-5">
                                Понедельник
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="mondayFrom" class="mondayFrom w-85">
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="mondayTo" class="mondayTo w-85">
                            </div>
                            <div class="col-md-3 no-padder text-center">
                                <input type="checkbox" name="mondayHoliday" class="mondayHoliday w-85">
                            </div>
                        </div>
                        <div class="row m-t-xs">
                            <div class="col-md-5">
                                Вторник
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="thuesdayFrom" class=" thuesdayFrom w-85">
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="thuesdayTo" class="thuesdayTo w-85">
                            </div>
                            <div class="col-md-3 no-padder text-center">
                                <input type="checkbox" name="thuesdayHoliday" class="thuesdayHoliday w-85">
                            </div>
                        </div>
                        <div class="row m-t-xs">
                            <div class="col-md-5">
                                Среда
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="wednesdayFrom" class="wednesdayFrom w-85">
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="wednesdayTo" class="wednesdayTo w-85">
                            </div>
                            <div class="col-md-3 no-padder text-center">
                                <input type="checkbox" name="wednesdayHoliday" class="wednesdayHoliday w-85">
                            </div>
                        </div>
                        <div class="row m-t-xs">
                            <div class="col-md-5">
                                Четверг
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="thursdayFrom" class="thursdayFrom w-85">
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="thursdayTo" class="thursdayTo w-85">
                            </div>
                            <div class="col-md-3 no-padder text-center">
                                <input type="checkbox" name="thursdayHoliday" class="thursdayHoliday w-85">
                            </div>
                        </div>
                        <div class="row m-t-xs">
                            <div class="col-md-5">
                                Пятница
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="fridayFrom" class="fridayFrom w-85">
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="fridayTo" class="fridayTo w-85">
                            </div>
                            <div class="col-md-3 no-padder text-center">
                                <input type="checkbox" name="fridayHoliday" class="fridayHoliday w-85">
                            </div>
                        </div>
                        <div class="row m-t-xs">
                            <div class="col-md-5">
                                Суббота
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="saturdayFrom" class="saturdayFrom w-85">
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="saturdayTo" class="saturdayTo w-85">
                            </div>
                            <div class="col-md-3 no-padder text-center">
                                <input type="checkbox" name="saturdayHoliday" class="saturdayHoliday w-85">
                            </div>
                        </div>
                        <div class="row m-t-xs">
                            <div class="col-md-5">
                                Воскресенье
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="sundayFrom" class="sundayFrom w-85">
                            </div>
                            <div class="col-md-2 no-padder text-center">
                                <input type="text" name="sundayTo" class="sundayTo w-85">
                            </div>
                            <div class="col-md-3 no-padder text-center">
                                <input type="checkbox" name="sundayHoliday" class="sundayHoliday w-85">
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-12 m-b-20 m-t">
                        Телефон 
                        <input type="text" name="phoneShowroom1" class="phoneShowroom1 pull-right w-287p">
                    </div>
                    <div class="col-md-12 m-b-20">
                        Телефон 
                        <input type="text" name="phoneShowroom2" class="phoneShowroom2 pull-right w-287p">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success changeShowroomData">Изменить</a>
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

    var dataShowroom = 
        {           
            city : 'Москва',
            address: 'Красношкольаня набережная, д. 22',
            workHours: 
                {
                    mondayFrom: '10:00',
                    mondayTo: '20:30',
                    mondayHoliday: false,
                    thuesdayFrom: '10:00',
                    thuesdayTo: '20:30',
                    thuesdayHoliday: false,
                    wednesdayFrom: '10:00',
                    wednesdayTo: '20:30',
                    wednesdayHoliday: false,
                    thursdayFrom: '10:00',
                    thursdayTo: '20:30',
                    thursdayHoliday: false,
                    fridayFrom: '10:00',
                    fridayTo: '20:30',
                    fridayHoliday: false,
                    saturdayFrom: '',
                    saturdayTo: '',
                    saturdayHoliday: true,
                    sundayFrom: '',
                    sundayTo: '',
                    sundayHoliday: true
                },
            phoneShowroom1 : '+7111010100101',
            phoneShowroom2 : '+711101014444'
        }

    $('table').on('click','.editShowroomData',function(){
       // console.log($(this).parents('tr')[0].rowIndex);
        // ну тут подгружаем данные по шоуруму из БД   

        // город
        $('.showroomCity').val(dataShowroom.city);
        // адрес
        $('.shoowroomAddress').val(dataShowroom.address);
        // телефон 1
        $('.phoneShowroom1').val(dataShowroom.phoneShowroom1);
        // телефон 2
        $('.phoneShowroom2').val(dataShowroom.phoneShowroom2);
        // тут закинули инфо в часы работы выходные
        for (const key in dataShowroom.workHours) {
            if (dataShowroom.workHours.hasOwnProperty(key)) {
                const element = dataShowroom.workHours[key];
                if (key.includes('Holiday')) {
                    $(`.${ key }`).prop( "checked", element );
                } else {
                    $(`.${ key }`).val(element);
                }
            }
        }
        
       // и отображаем модальное окно
        $('#showroomData').modal()

    } );

</script>
