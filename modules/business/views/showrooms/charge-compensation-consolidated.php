<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\models\api\Showrooms;
    use app\models\ShowroomsCompensation;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Начисление компенсаций</h3>
</div>
<section class="panel panel-default">
    <div class="row">
        <div class="col-md-6">
            <?=Html::dropDownList('',$filter['showroomId'],Showrooms::getListForFilter(),[
                'class'     => 'filterInfoSelect form-control m',
                'prompt'    => 'Список активных шоурумов',
                'data'      => [
                    'filter'    => 'showroomId'
                ]
            ])?>
        </div>
        <div class="col-md-6">
            <div class="m">
                <input id="mainMonth" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                   type="text" value="<?=$filter['date']?>" data-date-format="yyyy-mm" data-date-language="ru"
                   data-date-viewMode="months" data-date-minViewMode="months"
                   data-date-maxViewMode="months" data-filter="date">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active mainLi"><a href="javascript:void(0);" data-toggle="tab">Сводная</a></li>
                    <li class="historyLi"><a href="/ru/business/showrooms/charge-compensation-history">История</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="main">
                        <div class="table-responsive overflowXScroll">
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
                                            Логин
                                        </th>
                                        <th>
                                            ФИО
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
                                        <th>
                                            Действие
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($showrooms)){ ?>
                                        <?php foreach ($showrooms as $kShowroom=>$itemShowroom) { ?>
                                            <tr>
                                                <td class="showroomCountry"><?=$itemShowroom['country']?></td>
                                                <td class="showroomCity"><?=$itemShowroom['city']?></td>
                                                <td class="userLogin"><?=$itemShowroom['login']?></td>
                                                <td class="userFullName"><?=$itemShowroom['fullName']?></td>
                                                <td><?=$itemShowroom['turnoverTotal']?></td>
                                                <td><?=$itemShowroom['profit']?></td>
                                                <td><?=$itemShowroom['paidOffBankTransfer']?></td>
                                                <td><?=$itemShowroom['paidOffBC']?></td>
                                                <td><?=round($itemShowroom['remainder'],2)?></td>
                                                <td class="text-center">
                                                    <a href="/ru/business/showrooms/charge-compensation-history?showroomId=<?=$kShowroom?>" class="historyCompensation">История</a>
                                                    <a href="#topUpCompensation" data-toggle="modal" class="topUpCompensation" data-showroom-id="<?=$kShowroom?>" data-user-id="<?=$itemShowroom['userId']?>">Пополнить</a>
                                                    <a href="#chargeCompensation" data-toggle="modal" class="chargeCompensation" data-showroom-id="<?=$kShowroom?>" data-user-id="<?=$itemShowroom['userId']?>">Списать</a>
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

<div class="modal fade" id="topUpCompensation">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Пополнение</h4>
            </div>
            <div class="modal-body">
                <form class="refillShowroomForm" name="refillShowroomForm" method="POST" action="/business/showrooms/make-compensation">
                    <input type="hidden" name="ShowroomsCompensation[showroomId]">
                    <input type="hidden" name="ShowroomsCompensation[userId]">
                    <input type="hidden" name="ShowroomsCompensation[typeOperation]" value="<?=ShowroomsCompensation::TYPE_OPERATION_REFILL?>">
                    <div class="row">
                        <div class="col-md-12">
                           <p>
                               Шоу-рум <span class="font-bold m-l m-r refillShowroom"></span>
                               Логин <span class="font-bold m-l m-r refillLogin"></span>
                           </p>
                        </div>
                        <div class="col-md-12 m-b-sm">
                            <h4 class="refillFullName"></h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?=Html::dropDownList('ShowroomsCompensation[typeRefill]',
                                false,
                                ShowroomsCompensation::getTypeRefill(),[
                                'class'     => 'compensationTypeSelect form-control m-b',
                                'required'  => true,
                                'prompt'    => 'Выберите тип пополнения'
                            ])?>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control" name="ShowroomsCompensation[amount]" id="compensationTopUpAmount" placeholder="Сумма" required="required" min="1">
                        </div>
                        <div class="col-md-12">
                            Комментарий
                            <textarea class="form-control compensationTopUpComment m-t m-b" name="ShowroomsCompensation[comment]" id="compensationTopUpComment" rows="5" placeholder=""></textarea>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="col-sm-8 col-sm-offset-2 form-group">
                                <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                                <button type="submit" class="btn btn-success">Начислить</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="chargeCompensation">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Списание</h4>
            </div>
            <div class="modal-body">
                <form class="chargeOffShowroomForm" name="chargeOffShowroomForm" method="POST" action="/business/showrooms/make-compensation">
                    <input type="hidden" name="ShowroomsCompensation[showroomId]">
                    <input type="hidden" name="ShowroomsCompensation[userId]">
                    <input type="hidden" name="ShowroomsCompensation[typeOperation]" value="<?=ShowroomsCompensation::TYPE_OPERATION_CHARGE_OFF?>">
                    <div class="row">
                        <div class="col-md-12">
                           <p>
                               Шоу-рум <span class="font-bold m-l m-r chargeOffShowroom"></span>
                               Логин <span class="font-bold m-l m-r chargeOffLogin"></span>
                           </p>
                        </div>
                        <div class="col-md-12 m-b-sm">
                            <h4 class="chargeOffFullName"></h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="number" class="form-control m-b" name="ShowroomsCompensation[amount]"  id="compensationСhargeAmount" placeholder="Сумма" required="required" min="1">
                        </div>
                        <div class="col-md-12">
                            Комментарий
                           <textarea class="form-control  m-t m-b" name="ShowroomsCompensation[comment]" id="compensationChargeComment" rows="5" required="required"></textarea>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="col-sm-8 col-sm-offset-2 form-group">
                                <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                                <button type="submit" class="btn btn-success">Списать</button>
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

    $(document).on('click','.chargeCompensation',function(){
        var blShowroom = $(this).closest('tr');

        var showroomId = $(this).data('showroom-id');
        var userId = $(this).data('userId');
        var country = blShowroom.find('.showroomCountry').text();
        var city = blShowroom.find('.showroomCity').text();
        var login = blShowroom.find('.userLogin').text();
        var fullName = blShowroom.find('.fullName').text();

        var blForm = $('.chargeOffShowroomForm');

        blForm.find('[name="ShowroomsCompensation[showroomId]"]').val(showroomId);
        blForm.find('[name="ShowroomsCompensation[userId]"]').val(userId);

        blForm.find('.chargeOffShowroom').text(country + ', ' + city);
        blForm.find('.chargeOffLogin').text(login);
        blForm.find('.chargeOffFullName').text(fullName);

        blForm.find('[name="ShowroomsCompensation[amount]"]').val(0);
        blForm.find('[name="ShowroomsCompensation[comment]"]').val('');
    });

    $(document).on('click','.topUpCompensation',function(){
        var blShowroom = $(this).closest('tr');

        var showroomId = $(this).data('showroom-id');
        var userId = $(this).data('userId');
        var country = blShowroom.find('.showroomCountry').text();
        var city = blShowroom.find('.showroomCity').text();
        var login = blShowroom.find('.userLogin').text();
        var fullName = blShowroom.find('.fullName').text();

        var blForm = $('.refillShowroomForm');

        blForm.find('[name="ShowroomsCompensation[showroomId]"]').val(showroomId);
        blForm.find('[name="ShowroomsCompensation[userId]"]').val(userId);

        blForm.find('.refillShowroom').text(country + ', ' + city);
        blForm.find('.refillLogin').text(login);
        blForm.find('.refillFullName').text(fullName);

        blForm.find('[name="ShowroomsCompensation[typeRefill]"]').val('');
        blForm.find('[name="ShowroomsCompensation[amount]"]').val(0);
        blForm.find('[name="ShowroomsCompensation[comment]"]').val('');

    });

</script>
