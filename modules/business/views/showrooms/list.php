<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Список шоу-румов</h3>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table id="table-requests" class="table table-users table-striped datagrid m-b-sm">
            <thead>
                <tr>
                    <th>
                        <?=THelper::t('country')?>
                    </th>
                    <th>
                        <?=THelper::t('city')?>
                    </th>
                    <th>
                        <?=THelper::t('user_login')?>
                    </th>
                    <th>
                      <?=THelper::t('user_fname_sname')?>
                    </th>
                    <th>
                      Контакты профиля
                    </th>
                    <th>
                      Данные шоу-рума
                    </th>
                    <th>
                      Логин для выплат
                    </th>
                    <th>
                      Оборот
                    </th>
                    <th>
                      Начислений
                    </th>
                    <th>
                      Статус
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
                    <td>10</td>
                    <td><a class="editShowroom" href="#" data-showroom="1"><i class="fa fa-pencil"></i></a></td>
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
                    <td><a class="editShowroom" href="#" data-showroom="2"><i class="fa fa-pencil"></i></a></td>
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
                    <td><a class="editShowroom" href="#" data-showroom="3"><i class="fa fa-pencil"></i></a></td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <input type="button" class="btn btn-success pull-right addShowroom m-sm" value="+">
        </div>
    </div>
</section>

<div class="panel panel-default showroomInfo">
  <div class="panel-body">
    <div class="row m-b-sm">
        <div class="col-md-4">
            <span class="inline p-t-3p">Подал заявку</span>
            <select name="account" class="form-control m-b-none showroomApplied pull-right w-50 messengerDiv h-27p">
                <option value="null">Кто подал заявку</option>
                <option value="firely">firely / Вильховая </option>
                <option value="main" >main / Черногубов</option>
                <option value="1">и т.д.</option>
            </select>
        </div>
        <div class="col-md-4 p-t-3p">
            <input type="checkbox"  class="anotherCheckbox" name="anotherCheckbox"> <span class="inline p-t-3p">Подключить другой логин для компенсации</span>
        </div>
        <div class="col-md-4">
            <input type="text" name="anotherLogin" class="anotherLogin">
            <input type="button" class="btn btn-sm btn-success checkLogin m-n" value="Проверить">
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 no-padder">
            <div class="col-md-6 no-padder">
                <div class="col-md-12 m-b-20">
                    Email для  оповещений 
                    <input type="text" name="emailConfirmation" class="emailConfirmation pull-right w-50">
                </div>
                <div class="col-md-12 m-b-20">
                    Skype 
                    <input type="text" name="skypeShowroom" class="skypeShowroom pull-right w-50">
                </div>
                <div class="col-md-12 m-b-20">
                    Телефон 
                    <input type="text" name="phoneUserShowroom" class="phoneUserShowroom pull-right w-50">
                </div>
                <div class="col-md-12 m-b-20">
                    <select name="messenger1" class="form-control m-b messenger1 w-45 messengerDiv m-b-none"> 
                        <option value="null">Мессенджер</option> 
                        <option value="1">Viber</option> 
                        <option value="2" >Whatsapp</option> 
                        <option value="3">Telegram</option>
                    </select>
                    <input type="text" name="messenger1login" class="messenger1login pull-right w-50">
                </div>
                <div class="col-md-12 m-b-20">
                    <select name="messenger2" class="form-control m-b messenger2 w-45 messengerDiv"> 
                        <option value="null">Мессенджер</option> 
                        <option value="1">Viber</option> 
                        <option value="2" >Whatsapp</option> 
                        <option value="3">Telegram</option>
                    </select>
                    <input type="text" name="messenger2login" class="messenger2login pull-right w-50">
                </div>
            </div>
            <div class="col-md-6">
                <p>Данные администратора шоу-рума</p>
                <textarea class="form-control shoowroomAdminText" rows="7" placeholder="Тут данные администратора..."></textarea>            
            </div>
            <div class="col-md-12 m-t-md">
                <div class="row m-b-sm">
                    <div class="col-md-6">
                        <span class="w-195p inline m-t--9">Стоисость доставки курьером </span>
                        <input type="text" name="delivery1" class="delivery1 pull-right w-69 text-center" placeholder="EUR">
                    </div>
                    <div class="col-md-6">
                        До скольки дней 
                        <input type="text" name="delivery1Days" class="delivery1Days w-69 m-l text-center" placeholder="дней">
                    </div>
                </div>
                <div class="row m-b-sm">
                    <div class="col-md-6">
                        <input type="text" name="delivery2Text" class="delivery2Text pull-left w-195p padder" placeholder="Название доставки">
                        <input type="text" name="delivery2" class="delivery2 pull-right w-69 text-center" placeholder="EUR">
                    </div>
                    <div class="col-md-6">
                        До скольки дней 
                        <input type="text" name="delivery2Days" class="delivery2Days w-69 m-l text-center" placeholder="дней">
                    </div>
                </div>
                <div class="row m-b-sm">
                    <div class="col-md-6">
                        <input type="text" name="delivery3Text" class="delivery3Text pull-left w-195p padder" placeholder="Название доставки">
                        <input type="text" name="delivery3" class="delivery3 pull-right w-69 text-center" placeholder="EUR">
                    </div>
                    <div class="col-md-6">
                        До скольки дней 
                        <input type="text" name="delivery3Days" class="delivery3Days w-69 m-l text-center" placeholder="дней">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="col-md-12 showroomWebData m-b-sm">
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
                    <input type="text" name="phoneShowroom1" class="phoneShowroom1 pull-right w-50">
                </div>
                <div class="col-md-12 m-b-20">
                    Телефон 
                    <input type="text" name="phoneShowroom2" class="phoneShowroom2 pull-right w-50">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 pull-right">
            <select name="stateShowroom" class="stateShowroom form-control m-b w-45 inline m-b-none"> 
                <option value="0">Заблокирован</option> 
                <option value="1" selected>Активен</option> 
            </select>
            <input type="button" class="btn btn-success saveShowroom pull-right" value="Сохранить">
        </div>
    </div>

  </div>
</div>

<script>

    var dataShowroom = 
        {
            applied : 'firely',
            email : 'ferthdf@gmail.com',
            skype: 'ferthdf',
            phone: '+741144655',
            loginForWindraw: 'ferthdf',
            messenger1: { 
                name : 'Viber',
                login: '+45454545'
            },
            messenger2: { 
                name : 'Whatsapp',
                login: '+478785454'
            },
            delivery1:
                {    
                    name: 'Доставка курьером',
                    cost: 35.15,
                    days: 12
                },
            delivery2: 
                {
                    name: 'Доставка 2',
                    cost: 15,
                    days: 22
                },
            delivery3: 
                {
                    name: 'Доставка 3',
                    cost: 15,
                    days: 22
                },
            adminData: 'Бла бла бла бал аб лаб ал а бал аб бал аб ла бал ал лаб ',
            state: 0,        
            webData : 
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
        };

    $('section').on('click','.addShowroom',function(){

        // добавление нового шоурума
        // очищаем поля и открываем окошко внизу для добавления шоурума
        // кто подал заявку
        $('.showroomApplied').val('');
        $('.emailConfirmation').val('');
        $('.skypeShowroom').val('');
        $('.phoneUserShowroom').val('');
        $('.messenger1login').val('');
        $('.messenger2login').val('');
        $('.shoowroomAdminText').val('');
        $('.delivery1').val('');
        $('.delivery1Days').val('');
        $('.delivery2Text').val('');
        $('.delivery2').val('');
        $('.delivery2Days').val('');
        $('.delivery3Text').val('');
        $('.delivery3').val('');
        $('.delivery3Days').val('');

        for (const key in dataShowroom.webData.workHours) {
            if (dataShowroom.webData.workHours.hasOwnProperty(key)) {
                const element = dataShowroom.webData.workHours[key];
                if (key.includes('Holiday')) {
                    $(`.${ key }`).prop( "checked", false );
                } else {
                    $(`.${ key }`).val('');
                }
            }
        }

        $('.showroomCity').val('');
        $('.shoowroomAddress').val('');
        $('.phoneShowroom1').val('');
        $('.phoneShowroom2').val('');
        $('.stateShowroom').val(1);
        

        $('.saveShowroom').val('Добавить');
        $('.showroomInfo').show();

    });
    
    $('table').on('click','.editShowroom',function(){
       // console.log($(this).parents('tr')[0].rowIndex);
        // ну тут подгружаем данные по шоуруму из БД       

        // кто подал заявку
        $('.showroomApplied').val(dataShowroom.applied);

        // емаил
        $('.emailConfirmation').val(dataShowroom.email);

        // скайп
        $('.skypeShowroom').val(dataShowroom.skype);

        // телефон
        $('.phoneUserShowroom').val(dataShowroom.phone);

        // мессенджеры
        $('.messenger1login').val(dataShowroom.messenger1.login);
        $('.messenger2login').val(dataShowroom.messenger2.login);

        $(".messenger1 option").filter(function() {
            return this.text == dataShowroom.messenger1.name; 
        }).attr('selected', true);
        $(".messenger2 option").filter(function() {
            return this.text == dataShowroom.messenger2.name; 
        }).attr('selected', true);

        // данные администратора
        $('.shoowroomAdminText').val(dataShowroom.adminData);

        // тут закинули инфо в доставку

        $('.delivery1').val(dataShowroom.delivery1.cost);
        $('.delivery1Days').val(dataShowroom.delivery1.days);

        $('.delivery2Text').val(dataShowroom.delivery2.name);
        $('.delivery2').val(dataShowroom.delivery2.cost);
        $('.delivery2Days').val(dataShowroom.delivery2.days);

        $('.delivery3Text').val(dataShowroom.delivery3.name);
        $('.delivery3').val(dataShowroom.delivery3.cost);
        $('.delivery3Days').val(dataShowroom.delivery3.days);

        // тут закинули инфо в часы работы выходные
        for (const key in dataShowroom.webData.workHours) {
            if (dataShowroom.webData.workHours.hasOwnProperty(key)) {
                const element = dataShowroom.webData.workHours[key];
                if (key.includes('Holiday')) {
                    $(`.${ key }`).prop( "checked", element );
                } else {
                    $(`.${ key }`).val(element);
                }
            }
        }

        // город
        $('.showroomCity').val(dataShowroom.webData.city);

        // адрес
        $('.shoowroomAddress').val(dataShowroom.webData.address);

        // телефон 1
        $('.phoneShowroom1').val(dataShowroom.webData.phoneShowroom1);

        // телефон 2
        $('.phoneShowroom2').val(dataShowroom.webData.phoneShowroom2);

        // Статус
        $('.stateShowroom').val(dataShowroom.state);
        

       // и отображаем её
        $('.saveShowroom').val('Изменить');
        $('.showroomInfo').show();

    } );

    $('.showroomInfo').on('click','.saveShowroom',function(){
        console.log('saveShowroom');
        // тут пушем новые данные в заявку в БД

        // и скрываем окно с данными заявки
        $('.showroomInfo').hide();
    });

</script>
