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
                            <td><?=(!empty($itemShowroom->userLoginOtherLogin) ? $itemShowroom->userLoginOtherLogin : $itemShowroom->userLoginFiledRequest)?></td>
                            <td>???</td>
                            <td>???</td>
                            <td><?=Showrooms::getStatusValue($itemShowroom->status)?></td>
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
                        <div class="col-md-12 m-b-20 blMessenger">
                            <select name="Showroom[messenger][0][title]" class="form-control m-b messenger1 w-45 m-b-none messengerDiv">
                                <option value="null">Мессенджер</option>
                                <option value="1">Viber</option>
                                <option value="2" >Whatsapp</option>
                                <option value="3">Telegram</option>
                            </select>
                            <input type="text" name="Showroom[messenger][0][value]" class="messenger1login pull-right w-50">
                        </div>
                        <div class="col-md-12 m-b-20 blMessenger">
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
                        <div class="row m-b-sm blDelivery" >
                            <input type="hidden" name="Showroom[delivery][0][title]" value="Курьером" class="deliveryTitle">
                            <div class="col-md-6">
                                <span class="w-195p inline m-t--9">Стоисость доставки курьером </span>
                                <input type="text" name="Showroom[delivery][0][price]" class="deliveryPrice pull-right w-69 text-center" placeholder="EUR">
                            </div>
                            <div class="col-md-6">
                                До скольки дней
                                <input type="text" name="Showroom[delivery][0][day]" class="deliveryDays w-69 m-l text-center" placeholder="дней">
                            </div>
                        </div>
                        <div class="row m-b-sm blDelivery">
                            <div class="col-md-6">
                                <input type="text" name="Showroom[delivery][1][title]" class="deliveryTitle pull-left w-195p padder" placeholder="Название доставки">
                                <input type="text" name="Showroom[delivery][1][price]" class="deliveryPrice pull-right w-69 text-center" placeholder="EUR">
                            </div>
                            <div class="col-md-6">
                                До скольки дней
                                <input type="text" name="Showroom[delivery][1][day]" class="deliveryDays w-69 m-l text-center" placeholder="дней">
                            </div>
                        </div>
                        <div class="row m-b-sm blDelivery">
                            <div class="col-md-6">
                                <input type="text" name="Showroom[delivery][2][title]" class="deliveryTitle pull-left w-195p padder" placeholder="Название доставки">
                                <input type="text" name="Showroom[delivery][2][price]" class="deliveryPrice pull-right w-69 text-center" placeholder="EUR">
                            </div>
                            <div class="col-md-6">
                                До скольки дней
                                <input type="text" name="Showroom[delivery][2][day]" class="deliveryDays w-69 m-l text-center" placeholder="дней">
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
                            <div class="row m-t-xs worktimeShowroom">
                                <input type="hidden" name="Showroom[worktime][0][title]" value="monday" class="worktimeTitle">
                                <div class="col-md-5">
                                    Понедельник
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][0][from]" class="mondayFrom w-85 worktimeTimeFrom">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][0][to]" class="mondayTo w-85 worktimeTimeTo">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][0][holiday]" class="mondayHoliday w-85 worktimeTimeHoliday">
                                </div>
                            </div>
                            <div class="row m-t-xs worktimeShowroom">
                                <input type="hidden" name="Showroom[worktime][1][title]" value="thuesday" class="worktimeTitle">
                                <div class="col-md-5">
                                    Вторник
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][1][from]" class=" thuesdayFrom w-85 worktimeTimeFrom">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][1][to]" class="thuesdayTo w-85 worktimeTimeTo">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][1][holiday]" class="thuesdayHoliday w-85 worktimeTimeHoliday">
                                </div>
                            </div>
                            <div class="row m-t-xs worktimeShowroom">
                                <input type="hidden" name="Showroom[worktime][2][title]" value="wednesday" class="worktimeTitle">
                                <div class="col-md-5">
                                    Среда
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][2][from]" class="wednesdayFrom w-85 worktimeTimeFrom">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][2][to]" class="wednesdayTo w-85 worktimeTimeTo">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][2][holiday]" class="wednesdayHoliday w-85 worktimeTimeHoliday">
                                </div>
                            </div>
                            <div class="row m-t-xs worktimeShowroom">
                                <input type="hidden" name="Showroom[worktime][3][title]" value="thursday" class="worktimeTitle">
                                <div class="col-md-5">
                                    Четверг
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][3][from]" class="thursdayFrom w-85 worktimeTimeFrom">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][3][to]" class="thursdayTo w-85 worktimeTimeTo">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][3][holiday]" class="thursdayHoliday w-85 worktimeTimeHoliday">
                                </div>
                            </div>
                            <div class="row m-t-xs worktimeShowroom">
                                <input type="hidden" name="Showroom[worktime][4][title]" value="friday" class="worktimeTitle">
                                <div class="col-md-5">
                                    Пятница
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][4][from]" class="fridayFrom w-85 worktimeTimeFrom">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][4][to]" class="fridayTo w-85 worktimeTimeTo">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][4][holiday]" class="fridayHoliday w-85 worktimeTimeHoliday">
                                </div>
                            </div>
                            <div class="row m-t-xs worktimeShowroom">
                                <input type="hidden" name="Showroom[worktime][5][title]" value="saturday" class="worktimeTitle">
                                <div class="col-md-5">
                                    Суббота
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][5][from]" class="saturdayFrom w-85 worktimeTimeFrom">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][5][to]" class="saturdayTo w-85 worktimeTimeTo">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][5][holiday]" class="saturdayHoliday w-85 worktimeTimeHoliday">
                                </div>
                            </div>
                            <div class="row m-t-xs worktimeShowroom">
                                <input type="hidden" name="Showroom[worktime][6][title]" value="sunday" class="worktimeTitle worktimeTimeHoliday">
                                <div class="col-md-5">
                                    Воскресенье
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][6][from]" class="sundayFrom w-85 worktimeTimeFrom">
                                </div>
                                <div class="col-md-2 no-padder text-center">
                                    <input type="text" name="Showroom[worktime][6][to]" class="sundayTo w-85 worktimeTimeTo">
                                </div>
                                <div class="col-md-3 no-padder text-center">
                                    <input type="checkbox" name="Showroom[worktime][6][holiday]" class="sundayHoliday w-85 worktimeTimeHoliday">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 m-b-20 m-t phoneShowroom">
                            Телефон
                            <input type="text" name="Showroom[phoneShowroom][]" class="pull-right w-50">
                        </div>
                        <div class="col-md-12 m-b-20 phoneShowroom">
                            Телефон
                            <input type="text" name="Showroom[phoneShowroom][]" class="pull-right w-50">
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

                blShowroom
                    .find('.showroomApplied')
                    .html('<option value="'+msg.userIdFiledRequest+'" data-city-id="'+msg.cityId+'" data-city-title="'+msg.cityName.ru+'" data-country-id="'+msg.countryId+'" >' +
                        msg.userLoginFiledRequest+' ('+msg.userSecondNameFiledRequest+' '+msg.userFirstNameFiledRequest+')</option>');

                blShowroom.find('input[name="Showroom[id]"]').val(msg.id);
                blShowroom.find('input[name="Showroom[cityId]"]').val(msg.cityId);
                blShowroom.find('input[name="Showroom[countryId]"]').val(msg.countryId);

                blShowroom.find('.emailConfirmation').val(msg.email);
                blShowroom.find('.skypeShowroom').val(msg.skype);
                blShowroom.find('.phoneUserShowroom').val(msg.phone);

                blShowroom.find('.blMessenger').each(function (indx) {
                    if(typeof(msg.messenger[indx]) != "undefined"){
                        $(this).find('select').val(msg.messenger[indx].title);
                        $(this).find('input').val(msg.messenger[indx].value);
                    }
                });

                if(msg.userLoginOtherLogin){
                    blShowroom.find('.anotherLogin').val(msg.userLoginOtherLogin).prop("disabled", false);
                    blShowroom.find('.checkLogin').prop("disabled", false);
                    blShowroom.find('.anotherCheckbox').prop( "checked", true );
                }

                blShowroom.find('.blDelivery').each(function (indx) {
                    if(typeof(msg.delivery[indx]) != "undefined"){
                        $(this).find('.deliveryTitle').val(msg.delivery[indx].title);
                        $(this).find('.deliveryPrice').val(msg.delivery[indx].price);
                        $(this).find('.deliveryDays').val(msg.delivery[indx].day);
                    }
                });

                blShowroom.find('.shoowroomAdminText').val(msg.dataAdmin);
                blShowroom.find('.shoowroomAddress').val(msg.showroomAddress);

                blShowroom.find('.stateShowroom').val(msg.status);

                blShowroom.find('.phoneShowroom').each(function (indx) {
                    if(typeof(msg.showroomPhone[indx]) != "undefined"){
                        $(this).find('input').val(msg.showroomPhone[indx]);
                    }
                });

                blShowroom.find('.worktimeShowroom').each(function (indx) {
                    if(typeof(msg.showroomWorkTime[indx]) != "undefined"){
                        $(this).find('.worktimeTimeFrom').val(msg.showroomWorkTime[indx].from);
                        $(this).find('.worktimeTimeTo').val(msg.showroomWorkTime[indx].to);
                        if(msg.showroomWorkTime[indx].holiday === 'on'){
                            $(this).find('.worktimeTimeHoliday').prop( "checked", true );
                        }
                    }
                });

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

        form.find('input[name="Showroom[id]"]').val('');
        form.find('input[name="Showroom[cityId]"]').val('');
        form.find('input[name="Showroom[countryId]"]').val('');

        form.find('.showroomApplied').html('');
        form.find('.emailConfirmation').val('');
        form.find('.skypeShowroom').val('');
        form.find('.phoneUserShowroom').val('');
        form.find('.messenger1login').val('');
        form.find('.messenger2login').val('');
        form.find('.shoowroomAdminText').val('');

        form.find('.worktimeShowroom').each(function (indx) {
            $(this).find('.worktimeTimeFrom').val('');
            $(this).find('.worktimeTimeTo').val('');
            $(this).find('.worktimeTimeHoliday').prop( "checked", false );
        });

        form.find('.blMessenger').each(function (indx) {
            $(this).find('select').val('');
            $(this).find('input').val('');
        });

        form.find('.blDelivery').each(function (indx) {
            $(this).find('.deliveryTitle').val('');
            $(this).find('.deliveryPrice').val('');
            $(this).find('.deliveryDays').val('');
        });

        form.find('.phoneShowroom').each(function (indx) {
            $(this).find('input').val('');
        });

        form.find('.anotherLogin').val('').prop("disabled", true);
        form.find('.checkLogin').prop("disabled", true);
        form.find('.anotherCheckbox').prop( "checked", false );

        form.find('.showroomCity').val('');
        form.find('.shoowroomAddress').val('');
        form.find('.stateShowroom').val('');
        form.find('.saveShowroom').val('Добавить');
    }

    function showHideBlOtherLogin(bl) {
        $('.anotherLogin').val('');
        if (bl.is(":checked")) {
            $('.anotherLogin, .checkLogin').prop('disabled', false);
        } else {
            $('.anotherLogin, .checkLogin').prop('disabled', true);
        }
    }
</script>
