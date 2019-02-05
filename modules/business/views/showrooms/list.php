<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use app\models\api\Showrooms;

    use app\components\AlertWidget;

    /** @var $itemShoroom \app\models\api\Showrooms */

    $alert = Yii::$app->session->getFlash('alert', '', true);
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
                <?php if(!empty($showrooms)){?>
                    <?php foreach ($showrooms as $itemShowroom) {?>
                        <tr>
                            <td><?=$itemShowroom->countryName->ru?></td>
                            <td><?=$itemShowroom->cityName->ru?></td>
                            <td><?=$itemShowroom->userLoginFiledRequest?></td>
                            <td><?=$itemShowroom->userSecondNameFiledRequest?> <?=$itemShowroom->userFirstNameFiledRequest?></td>
                            <td><?=$itemShowroom->userPhoneFiledRequest?>, <?=$itemShowroom->userAddressFiledRequest?></td>
                            <td>
                                <?php if(!empty($itemShowroom->showroomPhone)){?>
                                    <?=implode(',<br>',$itemShowroom->showroomPhone)?>
                                <?php } ?>,<br>
                                <?=$itemShowroom->showroomAddress?>,<br>
                                <?php if(!empty($itemShowroom->showroomWorkTime)){?>
                                    <?php foreach($itemShowroom->showroomWorkTime as $workTimeItem){?>
                                        <?=$workTimeItem->title?>:
                                        <?php if(!empty($workTimeItem->from) && !empty($workTimeItem->to)){?>
                                            <?=$workTimeItem->from?> <?=$workTimeItem->to?>
                                        <?php } elseif (!empty($workTimeItem->holiday)) {?>
                                            выходной
                                        <?php } ?>
                                        <br>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td><?=$itemShowroom->userLoginOtherLogin?></td>
                            <td>???</td>
                            <td>???</td>
                            <td><?=$itemShowroom->status?></td>
                            <td>
                                <a class="editShowroom" href="javascript:void(0);" data-showroom="<?=$itemShowroom->id?>">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php }?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <input type="button" class="btn btn-success pull-right addShowroom m-sm" value="+">
        </div>
    </div>
</section>

<?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>


<div class="row">
    <div class="col-md-12 blError">

    </div>
</div>

<div class="panel panel-default showroomInfo">
    <div class="panel-body">
        <form class="showroomForm" name="showroomForm" method="POST" action="/business/showrooms/add-edit-showroom">

            <input type="hidden" name="Showroom[id]" value="">

            <input type="hidden" name="Showroom[cityId]" value="">
            <input type="hidden" name="Showroom[countryId]" value="">

            <div class="row m-b-sm">
                <div class="col-md-4">
                    <span class="inline p-t-3p">Подал заявку</span>
                    <select name="Showroom[userId]" class="form-control m-b-none showroomApplied pull-right w-50 messengerDiv h-27p" required="required">
                        <option>Кто подал заявку</option>
                    </select>
                </div>
                <div class="col-md-4 p-t-3p">
                    <input type="checkbox" class="anotherCheckbox">
                    <span class="inline p-t-3p">Подключить другой логин для компенсации</span>
                </div>
                <div class="col-md-4">
                    <input type="text" name="Showroom[otherLogin]" class="anotherLogin" disabled="disabled">
                    <input type="button" class="btn btn-sm btn-success checkLogin m-n" value="Проверить" disabled="disabled">
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 no-padder">
                    <div class="col-md-6 no-padder">
                        <div class="col-md-12 m-b-20">
                            Email для  оповещений
                            <input type="text" name="Showroom[email]" class="emailConfirmation pull-right w-50">
                        </div>
                        <div class="col-md-12 m-b-20">
                            Skype
                            <input type="text" name="Showroom[skype]" class="skypeShowroom pull-right w-50">
                        </div>
                        <div class="col-md-12 m-b-20">
                            Телефон
                            <input type="text" name="Showroom[phone]" class="phoneUserShowroom pull-right w-50">
                        </div>
                        <div class="col-md-12 m-b-20">
                            <select name="Showroom[messenger][0][title]" class="form-control m-b messenger1 w-45 messengerDiv m-b-none">
                                <option value="null">Мессенджер</option>
                                <option value="1">Viber</option>
                                <option value="2" >Whatsapp</option>
                                <option value="3">Telegram</option>
                            </select>
                            <input type="text" name="Showroom[messenger][0][value]" class="messenger1login pull-right w-50">
                        </div>
                        <div class="col-md-12 m-b-20">
                            <select name="Showroom[messenger][1][title]" class="form-control m-b messenger2 w-45 messengerDiv">
                                <option value="null">Мессенджер</option>
                                <option value="1">Viber</option>
                                <option value="2" >Whatsapp</option>
                                <option value="3">Telegram</option>
                            </select>
                            <input type="text" name="Showroom[messenger][1][value]" class="messenger2login pull-right w-50">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p>Данные администратора шоу-рума</p>
                        <textarea name="Showroom[dataAdmin]" class="form-control shoowroomAdminText" rows="7" placeholder="Тут данные администратора..."></textarea>
                    </div>
                    <div class="col-md-12 m-t-md">
                        <div class="row m-b-sm">
                            <input type="hidden" name="Showroom[delivery][0][title]" value="Курьером">
                            <div class="col-md-6">
                                <span class="w-195p inline m-t--9">Стоисость доставки курьером </span>
                                <input type="text" name="Showroom[delivery][0][price]" class="delivery1 pull-right w-69 text-center" placeholder="EUR">
                            </div>
                            <div class="col-md-6">
                                До скольки дней
                                <input type="text" name="Showroom[delivery][0][day]" class="delivery1Days w-69 m-l text-center" placeholder="дней">
                            </div>
                        </div>
                        <div class="row m-b-sm">
                            <div class="col-md-6">
                                <input type="text" name="Showroom[delivery][1][title]" class="delivery2Text pull-left w-195p padder" placeholder="Название доставки">
                                <input type="text" name="Showroom[delivery][1][price]" class="delivery2 pull-right w-69 text-center" placeholder="EUR">
                            </div>
                            <div class="col-md-6">
                                До скольки дней
                                <input type="text" name="Showroom[delivery][1][day]" class="delivery2Days w-69 m-l text-center" placeholder="дней">
                            </div>
                        </div>
                        <div class="row m-b-sm">
                            <div class="col-md-6">
                                <input type="text" name="Showroom[delivery][2][title]" class="delivery3Text pull-left w-195p padder" placeholder="Название доставки">
                                <input type="text" name="Showroom[delivery][2][price]" class="delivery3 pull-right w-69 text-center" placeholder="EUR">
                            </div>
                            <div class="col-md-6">
                                До скольки дней
                                <input type="text" name="Showroom[delivery][2][day]" class="delivery3Days w-69 m-l text-center" placeholder="дней">
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
                                Адрес в городе <b class="showroomCity"></b>:
                            </p>
                            <input type="text" name="Showroom[address]" class="shoowroomAddress w-100">
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
                                <input type="hidden" name="Showroom[worktime][0][title]" value="monday">
                                <div class="col-md-5">
                                    Понедельник
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][0][from]" class="mondayFrom w-85">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][0][to]" class="mondayTo w-85">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][0][holiday]" class="mondayHoliday w-85">
                                </div>
                            </div>
                            <div class="row m-t-xs">
                                <input type="hidden" name="Showroom[worktime][1][title]" value="thuesday">
                                <div class="col-md-5">
                                    Вторник
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][1][from]" class=" thuesdayFrom w-85">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][1][to]" class="thuesdayTo w-85">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][1][holiday]" class="thuesdayHoliday w-85">
                                </div>
                            </div>
                            <div class="row m-t-xs">
                                <input type="hidden" name="Showroom[worktime][2][title]" value="wednesday">
                                <div class="col-md-5">
                                    Среда
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][2][from]" class="wednesdayFrom w-85">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][2][to]" class="wednesdayTo w-85">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][2][holiday]" class="wednesdayHoliday w-85">
                                </div>
                            </div>
                            <div class="row m-t-xs">
                                <input type="hidden" name="Showroom[worktime][3][title]" value="thursday">
                                <div class="col-md-5">
                                    Четверг
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][3][from]" class="thursdayFrom w-85">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][3][to]" class="thursdayTo w-85">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][3][holiday]" class="thursdayHoliday w-85">
                                </div>
                            </div>
                            <div class="row m-t-xs">
                                <input type="hidden" name="Showroom[worktime][4][title]" value="friday">
                                <div class="col-md-5">
                                    Пятница
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][4][from]" class="fridayFrom w-85">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][4][to]" class="fridayTo w-85">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][4][holiday]" class="fridayHoliday w-85">
                                </div>
                            </div>
                            <div class="row m-t-xs">
                                <input type="hidden" name="Showroom[worktime][5][title]" value="saturday">
                                <div class="col-md-5">
                                    Суббота
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][5][from]" class="saturdayFrom w-85">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][5][to]" class="saturdayTo w-85">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][5][holiday]" class="saturdayHoliday w-85">
                                </div>
                            </div>
                            <div class="row m-t-xs">
                                <input type="hidden" name="Showroom[worktime][6][title]" value="sunday">
                                <div class="col-md-5">
                                    Воскресенье
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][6][from]" class="sundayFrom w-85">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][6][to]" class="sundayTo w-85">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][6][holiday]" class="sundayHoliday w-85">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 m-b-20 m-t">
                            Телефон
                            <input type="text" name="Showroom[phoneShowroom][]" class="phoneShowroom1 pull-right w-50">
                        </div>
                        <div class="col-md-12 m-b-20">
                            Телефон
                            <input type="text" name="Showroom[phoneShowroom][]" class="phoneShowroom2 pull-right w-50">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
        <div class="col-sm-4 pull-right">
            <?=Html::dropDownList('Showroom[status]',false,Showrooms::getStatus(),[
                'class' => 'form-control m-b w-45 inline m-b-none stateShowroom'
            ])?>

            <input type="submit" class="btn btn-success pull-right" value="Сохранить">
        </div>
    </div>
        </form>
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

    $('.addShowroom').on('click',function(){

        clearShowroomForm();

        var listRequest = getListRequest();

        $('.showroomInfo').find('.showroomApplied').html('<option>Кто подал заявку</option>');
        $.each(listRequest, function(key, item) {
            $('.showroomInfo')
                .find('.showroomApplied')
                .append('<option value="'+key+'" data-city-id="'+item.cityId+'" data-city-title="'+item.cityTitle+'" data-country-id="'+item.countryId+'" >' +
                    item.userLogin+' ('+item.userSecondName+' '+item.userFirstName+')</option>');
        });

        $('.showroomInfo').show();

    });

    $('.showroomInfo').on('change','.showroomApplied',function () {
        var showroomInfo = $('.showroomInfo');

        var cityId = $(this).find(':selected').data('city-id');
        var cityTitle = $(this).find(':selected').data('city-title');
        var countryId = $(this).find(':selected').data('country-id');

        if(cityTitle){
            showroomInfo.find('.showroomCity').text(cityTitle);
            showroomInfo.find('input[name="Showroom[cityId]"]').val(cityId);
            showroomInfo.find('input[name="Showroom[countryId]"]').val(countryId);
        } else {
            showroomInfo.find('.showroomCity').text('');
            showroomInfo.find('input[name="Showroom[cityId]"]').val('');
            showroomInfo.find('input[name="Showroom[countryId]"]').val('');
        }
    });

    $('table').on('click','.editShowroom',function(){

        blShowroom = $('.showroomInfo')

        $.ajax({
            url: '/ru/business/showrooms/get-showroom',
            type: 'POST',
            data: {id:$(this).data('showroom')},
            success: function(msg){

                clearShowroomForm();

                $('.showroomInfo')
                    .find('.showroomApplied')
                    .html('<option value="'+key+'" data-city-id="'+item.cityId+'" data-city-title="'+item.cityTitle+'" data-country-id="'+item.countryId+'" >' +
                        item.userLogin+' ('+item.userSecondName+' '+item.userFirstName+')</option>');

                console.log(msg);


                $('.showroomInfo').show();
            }
        });


       //  // console.log($(this).parents('tr')[0].rowIndex);
       //  // ну тут подгружаем данные по шоуруму из БД
       //
       //  // кто подал заявку
       //  $('.showroomApplied').val(dataShowroom.applied);
       //
       //  // емаил
       //  $('.emailConfirmation').val(dataShowroom.email);
       //
       //  // скайп
       //  $('.skypeShowroom').val(dataShowroom.skype);
       //
       //  // телефон
       //  $('.phoneUserShowroom').val(dataShowroom.phone);
       //
       //  // мессенджеры
       //  $('.messenger1login').val(dataShowroom.messenger1.login);
       //  $('.messenger2login').val(dataShowroom.messenger2.login);
       //
       //  $(".messenger1 option").filter(function() {
       //      return this.text == dataShowroom.messenger1.name;
       //  }).attr('selected', true);
       //  $(".messenger2 option").filter(function() {
       //      return this.text == dataShowroom.messenger2.name;
       //  }).attr('selected', true);
       //
       //  // данные администратора
       //  $('.shoowroomAdminText').val(dataShowroom.adminData);
       //
       //  // тут закинули инфо в доставку
       //
       //  $('.delivery1').val(dataShowroom.delivery1.cost);
       //  $('.delivery1Days').val(dataShowroom.delivery1.days);
       //
       //  $('.delivery2Text').val(dataShowroom.delivery2.name);
       //  $('.delivery2').val(dataShowroom.delivery2.cost);
       //  $('.delivery2Days').val(dataShowroom.delivery2.days);
       //
       //  $('.delivery3Text').val(dataShowroom.delivery3.name);
       //  $('.delivery3').val(dataShowroom.delivery3.cost);
       //  $('.delivery3Days').val(dataShowroom.delivery3.days);
       //
       //  // тут закинули инфо в часы работы выходные
       //  for (const key in dataShowroom.webData.workHours) {
       //      if (dataShowroom.webData.workHours.hasOwnProperty(key)) {
       //          const element = dataShowroom.webData.workHours[key];
       //          if (key.includes('Holiday')) {
       //              $(`.${ key }`).prop( "checked", element );
       //          } else {
       //              $(`.${ key }`).val(element);
       //          }
       //      }
       //  }
       //
       //  // город
       //  $('.showroomCity').val(dataShowroom.webData.city);
       //
       //  // адрес
       //  $('.shoowroomAddress').val(dataShowroom.webData.address);
       //
       //  // телефон 1
       //  $('.phoneShowroom1').val(dataShowroom.webData.phoneShowroom1);
       //
       //  // телефон 2
       //  $('.phoneShowroom2').val(dataShowroom.webData.phoneShowroom2);
       //
       //  // Статус
       //  $('.stateShowroom').val(dataShowroom.state);
       //
       //
       // // и отображаем её
       //  $('.saveShowroom').val('Изменить');
       //

    } );

    $('.showroomInfo').on('click','.anotherCheckbox',function () {
        showHideBlOtherLogin($(this));
    });

    $('.showroomInfo').on('click','.checkLogin',function () {
        var blInfo = $('.showroomInfo');
        var blError = $('.blError');

        $.ajax({
            url: '/ru/business/user/check-user-login',
            type: 'POST',
            data: {login:blInfo.find('.anotherLogin').val()},
            beforeSend: function () {
                blInfo.append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                blInfo.find('.loader').remove();
            },
            success: function(msg){
                console.log(msg)

                if(msg === '1'){
                    blError.html(
                        '<div class="alert alert-success fade in">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                        '<strong>Такой пользователь существует</strong>' +
                        '</div>');
                } else {
                    blError.html(
                        '<div class="alert alert-danger fade in">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                        '<strong>Такой пользователь не существует</strong>' +
                        '</div>');
                }
            }
        });
    });

    function getListRequest() {
        var list;

        $.ajax({
            url: '/ru/business/showrooms/get-success-request-open',
            type: 'GET',
            async:false,
            success: function(msg){
                list = msg;
            }
        });

        return list;

    }

    function clearShowroomForm() {
        var form = $('.showroomInfo');

        form.find('.showroomApplied').html('');
        form.find('.emailConfirmation').val('');
        form.find('.skypeShowroom').val('');
        form.find('.phoneUserShowroom').val('');
        form.find('.messenger1login').val('');
        form.find('.messenger2login').val('');
        form.find('.shoowroomAdminText').val('');
        form.find('.delivery1').val('');
        form.find('.delivery1Days').val('');
        form.find('.delivery2Text').val('');
        form.find('.delivery2').val('');
        form.find('.delivery2Days').val('');
        form.find('.delivery3Text').val('');
        form.find('.delivery3').val('');
        form.find('.delivery3Days').val('');

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

        form.find('.showroomCity').val('');
        form.find('.shoowroomAddress').val('');
        form.find('.phoneShowroom1').val('');
        form.find('.phoneShowroom2').val('');
        form.find('.stateShowroom').val(1);


        form.find('.saveShowroom').val('Добавить');
    }

    function showHideBlOtherLogin(bl) {
        if (bl.is(":checked")) {
            $('.anotherLogin, .checkLogin').prop('disabled', false);
        } else {
            $('.anotherLogin, .checkLogin').prop('disabled', true);
        }
    }
</script>
