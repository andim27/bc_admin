<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use app\models\api\Showrooms;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Таблица компенсаций</h3>
</div>
<section class="panel panel-default">
    <div class="row">
        <div class="col-md-6">
            <?=Html::dropDownList('',$filter['showroomId'],Showrooms::getListForFilter(),[
                'class'     => 'filterInfo form-control m',
                'prompt'    => 'Список активных шоурумов',
                'data'      => [
                    'filter'    => 'showroomId'
                ]
            ])?>
        </div>
        <div class="col-md-6">
            <div class="m">
                <span class="m-r">С</span>
                <input id="mainFrom" class="input-s datepicker-input inline input-showroom form-control text-center filterInfo" size="16" type="text" value="<?=$filter['dateFrom']?>" data-date-format="yyyy-mm-dd" data-filter="dateFrom">
                <span class="m-r m-l">ПО</span>
                <input id="mainTo" class="input-s datepicker-input inline input-showroom form-control text-center filterInfo" size="16" type="text" value="<?=$filter['dateTo']?>" data-date-format="yyyy-mm-dd" data-filter="dateTo">
            </div>
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
                                    <?php if(!empty($compensationСonsolidate)){ ?>
                                        <?php foreach ($compensationСonsolidate as $kCompensationСonsolidate=>$itemCompensationСonsolidate) { ?>
                                            <tr>
                                                <td><?=$itemCompensationСonsolidate['country']?></td>
                                                <td><?=$itemCompensationСonsolidate['city']?></td>
                                                <td><?=$itemCompensationСonsolidate['turnoverTotal']?></td>
                                                <td><?=$itemCompensationСonsolidate['turnoverWebWellness']?></td>
                                                <td><?=$itemCompensationСonsolidate['turnoverVipCoin']?></td>
                                                <td><?=$itemCompensationСonsolidate['turnoverVipVip']?></td>
                                                <td><?=$itemCompensationСonsolidate['profit']?></td>
                                                <td><?=$itemCompensationСonsolidate['paidOffBankTransfer']?></td>
                                                <td><?=$itemCompensationСonsolidate['paidOffBC']?></td>
                                                <td><?=$itemCompensationСonsolidate['remainder']?></td>
                                                <td class="text-center">
                                                    <a href="#" class="editShowroomData" data-showroom="<?=$kCompensationСonsolidate?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="profit">
                        <!-- Начисления -->
                        <div class="row">
                            <div class="col-md-12 m-b">
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

                <div class="blError"></div>

                <form class="showroomForm">

                    <input type="hidden" name="Showroom[id]" value="">
                    <input type="hidden" name="Showroom[cityId]" value="">
                    <input type="hidden" name="Showroom[countryId]" value="">

                    <div class="col-md-12 m-b-sm">
                        <div class="col-md-12 m-t-sm">
                            <p>
                                Данные ШОУ-РУМА отображаемые на сайтах
                            </p>
                            <p>
                                Адрес в городе <b class="showroomCity">Москва</b>:
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
                                <input type="hidden" name="Showroom[worktime][6][title]" value="sunday" class="worktimeTitle">
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
                            <input type="text" name="Showroom[phoneShowroom][]" class="pull-right w-287p">
                        </div>
                        <div class="col-md-12 m-b-20 phoneShowroom">
                            Телефон
                            <input type="text" name="Showroom[phoneShowroom][]" class="pull-right w-287p">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="col-sm-8 col-sm-offset-2 form-group">
                                <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                                <button type="submit" class="btn btn-success changeShowroomData">Изменить</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>


<?php $this->registerCssFile('/js/datepicker/datepicker.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js', ['position' => yii\web\View::POS_END]); ?>

<script>

    $('.filterInfo').on('change',function () {
        var link = window.location.href;

        $('.filterInfo').each(function () {
            link = updateQueryStringParameter(link,$(this).data('filter'),$(this).val());
        });

        document.location.href = link;
    });

    $('table').on('click','.editShowroomData',function(){

        blShowroom = $('.showroomForm');

        clearShowroomForm();

        $.ajax({
            url: '/ru/business/showrooms/get-showroom',
            type: 'POST',
            data: {id:$(this).data('showroom')},
            success: function(msg){
                blShowroom.find('input[name="Showroom[id]"]').val(msg.id);
                blShowroom.find('input[name="Showroom[cityId]"]').val(msg.cityId);
                blShowroom.find('input[name="Showroom[countryId]"]').val(msg.countryId);
                blShowroom.find('.showroomCity').text(msg.cityName.ru);
                blShowroom.find('.shoowroomAddress').val(msg.showroomAddress);
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

                $('#showroomData').modal();
            }
        });



    } );

    $('.showroomForm').on('submit',function (e) {

        e.preventDefault();

        var form = $(this);
        var data = form.serialize();

        var blInfo = form.closest('.showroomWebData');


        $.ajax({
            url: '/ru/business/showrooms/add-edit-showroom',
            type: 'POST',
            data: data,
            beforeSend: function () {
                blInfo.append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                blInfo.find('.loader').remove();
            },
            success: function(msg) {
                blInfo.find('.blError').html(
                    '<div class="alert alert-'+msg.typeAlert+' fade in">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                    msg.message +
                    '</div>'
                );
            }
        });
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

    function clearShowroomForm() {
        var form = $('.showroomForm');

        form.find('input[name="Showroom[id]"]').val('');
        form.find('input[name="Showroom[cityId]"]').val('');
        form.find('input[name="Showroom[countryId]"]').val('');

        form.find('.worktimeShowroom').each(function () {
            $(this).find('.worktimeTimeFrom').val('');
            $(this).find('.worktimeTimeTo').val('');
            $(this).find('.worktimeTimeHoliday').prop( "checked", false );
        });

        form.find('.phoneShowroom').each(function (indx) {
            $(this).find('input').val('');
        });

        form.find('.showroomCity').text('');
        form.find('.shoowroomAddress').val('');
    }

</script>
