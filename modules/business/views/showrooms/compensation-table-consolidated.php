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
            <?=Html::dropDownList('',$filter['showroomId'],$listShowroomsForSelect,[
                'class'     => 'filterInfoSelect form-control m',
                'prompt'    => 'Список активных шоурумов',
                'data'      => [
                    'filter'    => 'showroomId'
                ]
            ])?>
        </div>
        <div class="col-md-6">
            <div class="m">
                <span class="m-r">С</span>
                <input id="mainFrom" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['dateFrom']?>" data-date-format="yyyy-mm" data-filter="dateFrom"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months"
                       >
                <span class="m-r m-l">ПО</span>
                <input id="mainTo" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['dateTo']?>" data-date-format="yyyy-mm" data-filter="dateTo"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="javascript:void(0)">Сводная</a></li>
                    <li><a href="/ru/business/showrooms/compensation-table-accruals" >Начисления</a></li>
                    <li><a href="/ru/business/showrooms/compensation-table-purchases" >Покупки</a></li>
                    <li><a href="/ru/business/showrooms/compensation-table-on-balance" >Товар на балансе</a></li>
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
                                    <?php if(!empty($compensationConsolidate)){ ?>
                                        <?php foreach ($compensationConsolidate as $kCompensationСonsolidate=>$itemCompensationСonsolidate) { ?>
                                            <tr>
                                                <td><?=$itemCompensationСonsolidate['country']?></td>
                                                <td><?=$itemCompensationСonsolidate['city']?></td>
                                                <td><?=$itemCompensationСonsolidate['turnoverTotal']?></td>
                                                <td><?=$itemCompensationСonsolidate['profit']?></td>
                                                <td><?=$itemCompensationСonsolidate['paidOffBankTransfer']?></td>
                                                <td><?=$itemCompensationСonsolidate['paidOffBC']?></td>
                                                <td><?=round($itemCompensationСonsolidate['remainder'],2)?></td>
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

    $('#table-main').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        lengthChange: false,
        info: false
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

    $('.filterInfoSelect').on('change',function () {
        var link = window.location.href;

        link = updateQueryStringParameter(link,$(this).data('filter'),$(this).val());

        document.location.href = link;
    });

    $('.filterInfoDate').datepicker().on('changeDate', function (e) {
        var link = window.location.href;
        var date = new Date(e.date);
        var newDate = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2);
        var newFilter = e.currentTarget.dataset.filter;

        $('.filterInfoDate').each(function () {
            link = updateQueryStringParameter(link,$(this).data('filter'),$(this).datepicker({ dateFormat: 'yy-mm' }).val());
        });

        link = updateQueryStringParameter(link,newFilter,newDate);

        document.location.href = link;

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

</script>
