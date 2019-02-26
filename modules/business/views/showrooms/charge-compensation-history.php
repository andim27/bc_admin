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
                <span class="m-r">С</span>
                <input id="mainFrom" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['dateFrom']?>" data-date-format="yyyy-mm" data-filter="dateFrom"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months" >
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
                    <li class="mainLi"><a href="/ru/business/showrooms/charge-compensation-consolidated">Сводная</a></li>
                    <li class="active historyLi"><a href="javascript:void(0);">История</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="history">
                        <!-- История -->
                        <div class="row">
                            <div class="col-md-12 m-b">

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table-main" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Страна
                                        </th>
                                        <th>
                                            Город
                                        </th>
                                        <th>
                                            Логин
                                        </th>
                                        <th>
                                            ФИО
                                        </th>
                                        <th>
                                            Дата выплаты
                                        </th>
                                        <th>
                                            Выплачено безналом
                                        </th>
                                        <th>
                                            Скидка на лиц.сч.
                                        </th>
                                        <th>
                                            Списание
                                        </th>
                                        <th>
                                            Остаток
                                        </th>
                                        <th>
                                            Комментарий
                                        </th>
                                        <th>
                                            Отредактировано
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($compensation)){ ?>
                                        <?php foreach ($compensation as $kCompensation=>$itemCompensation) { ?>
                                            <tr>
                                                <td class="showroomCountry"><?=$itemCompensation['country']?></td>
                                                <td class="showroomCity"><?=$itemCompensation['city']?></td>
                                                <td class="userLogin"><?=$itemCompensation['login']?></td>
                                                <td class="fullName"><?=$itemCompensation['fullName']?></td>
                                                <td>
                                                    <?=$itemCompensation['dateCreate']?>

                                                    <?=(!empty($itemCompensation['historyEdit']) ? '<div class="tdHistory">' .$itemCompensation['historyEdit']['dateCreate'].'</div>' : '')?>
                                                </td>
                                                <td>
                                                    <?=$itemCompensation['paidOffBankTransfer']?>

                                                    <?=(!empty($itemCompensation['historyEdit']) ? '<div class="tdHistory">' .$itemCompensation['historyEdit']['paidOffBankTransfer'].'</div>' : '')?>
                                                </td>
                                                <td>
                                                    <?=$itemCompensation['paidOffBC']?>

                                                    <?=(!empty($itemCompensation['historyEdit']) ? '<div class="tdHistory">' .$itemCompensation['historyEdit']['paidOffBC'].'</div>' : '')?>
                                                </td>
                                                <td>
                                                    <?=$itemCompensation['chargeOff']?>

                                                    <?=(!empty($itemCompensation['historyEdit']) ? '<div class="tdHistory">' .$itemCompensation['historyEdit']['chargeOff'].'</div>' : '')?>
                                                </td>
                                                <td>
                                                    <?=$itemCompensation['remainder']?>

                                                    <?=(!empty($itemCompensation['historyEdit']) ? '<div class="tdHistory">' .$itemCompensation['historyEdit']['remainder'].'</div>' : '')?>
                                                </td>
                                                <td><?=$itemCompensation['comment']?></td>
                                                <td>
                                                    <?=(!empty($itemCompensation['historyEdit']['fullNameEditUser']) ? $itemCompensation['historyEdit']['fullNameEditUser'] : '');?>
                                                </td>
                                                <td>
                                                    <?=Html::a(
                                                        '<i class="fa fa-pencil"></i>',
                                                        ($itemCompensation['typeOperation'] == 'refill' ? '#editCompensationRefill' : '#editCompensationChargeOff'),
                                                        [
                                                            'class' => 'editHistory',
                                                            'data'  => [
                                                                'toggle' => 'modal',
                                                                'compensation-id' => $kCompensation,
                                                                'compensation-type' => $itemCompensation['typeOperation']
                                                            ]
                                                        ])?>
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

<div class="modal fade" id="editCompensationRefill">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Изменение пополнения</h4>
            </div>
            <div class="modal-body">
                <form class="editRefillForm" name="editRefillForm" method="POST" action="/business/showrooms/edit-compensation">
                    <input type="hidden" name="ShowroomsCompensation[_id]">
                    <input type="hidden" name="ShowroomsCompensation[typeOperation]" value="<?=ShowroomsCompensation::TYPE_OPERATION_REFILL?>">
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                Шоу-рум <span class="font-bold m-l m-r editInfoShowroom"></span>
                                Логин <span class="font-bold m-l m-r editInfoLogin"></span>
                            </p>
                        </div>
                        <div class="col-md-12 m-b-sm">
                            <h4 class="editInfoFullName"></h4>
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

<div class="modal fade" id="editCompensationChargeOff">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Изменение списания</h4>
            </div>
            <div class="modal-body">
                <form class="editChargeOffForm" name="editChargeOffForm" method="POST" action="/business/showrooms/edit-compensation">
                    <input type="hidden" name="ShowroomsCompensation[_id]">
                    <input type="hidden" name="ShowroomsCompensation[typeOperation]" value="<?=ShowroomsCompensation::TYPE_OPERATION_CHARGE_OFF?>">
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                Шоу-рум <span class="font-bold m-l m-r editInfoShowroom"></span>
                                Логин <span class="font-bold m-l m-r editInfoLogin"></span>
                            </p>
                        </div>
                        <div class="col-md-12 m-b-sm">
                            <h4 class="editInfoFullName"></h4>
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

    $(document).on('click','.editHistory',function(){
        var blShowroom = $(this).closest('tr');
        var transactionId = $(this).data('compensation-id');
        var transactionType = $(this).data('compensation-type');
        var country = blShowroom.find('.showroomCountry').text();
        var city = blShowroom.find('.showroomCity').text();
        var login = blShowroom.find('.userLogin').text();
        var fullName = blShowroom.find('.fullName').text();

        if(transactionType == 'refill'){
            var blForm = $('.editRefillForm');

            blForm.find('[name="ShowroomsCompensation[typeRefill]"]').val('');
        } else {
            var blForm = $('.editChargeOffForm');
        }


        blForm.find('[name="ShowroomsCompensation[_id]"]').val(transactionId);

        blForm.find('.editInfoShowroom').text(country + ', ' + city);
        blForm.find('.editInfoLogin').text(login);
        blForm.find('.editInfoFullName').text(fullName);

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