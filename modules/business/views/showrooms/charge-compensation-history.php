<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\models\api\Showrooms;
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
                            <table id="table-profit" class="table table-users table-striped datagrid m-b-sm">
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
                                                <td><?=$itemCompensation['country']?></td>
                                                <td><?=$itemCompensation['city']?></td>
                                                <td><?=$itemCompensation['login']?></td>
                                                <td><?=$itemCompensation['fullName']?></td>
                                                <td><?=$itemCompensation['dateCreate']?></td>
                                                <td><?=$itemCompensation['paidOffBankTransfer']?></td>
                                                <td><?=$itemCompensation['paidOffBC']?></td>
                                                <td><?=$itemCompensation['remainder']?></td>
                                                <td><?=$itemCompensation['comment']?></td>
                                                <td><?=$itemCompensation['historyEdit']?></td>
                                                <td>
                                                    <a class="editHistory" href="#historyEditCompensation" data-toggle="modal" data-compensation-id="<?=$kCompensation?>">
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

<div class="modal fade" id="historyEditCompensation">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Редактирование</h4>
            </div>
            <div class="modal-body">
<!--                <div class="row">-->
<!--                    <div class="col-md-12">-->
<!--                       <p>Шоу-рум <span class="font-bold cityShowroom m-l m-r">Новосибирск</span> Логин <span class="font-bold loginShowroom  m-l m-r">main</span>-->
<!--                       </p> -->
<!--                    </div>-->
<!--                    <div class="col-md-12 m-b-sm">-->
<!--                        <h4>Иванов Иван Иванович</h4>-->
<!--                    </div>-->
<!--                </div>-->
<!--                -->
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <select name="compensationHistoryTypeSelect" class="compensationHistoryTypeSelect form-control m-b"> -->
<!--                            <option value="1">Безнал</option> -->
<!--                            <option value="2">Нал</option> -->
<!--                            <option value="3">Бонусы</option> -->
<!--                            <option value="4" selected>Тугрики</option>-->
<!--                            <option value="5">Виртуальное "Спасибо"</option>-->
<!--                            <option value="6">Хер вам а не пополнение</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <input type="text" class="form-control" name="compensationHistoryEditAmount" id="compensationHistoryEditAmount" placeholder="Сумма">-->
<!--                    </div>-->
<!--                    <div class="col-md-12">-->
<!--                        Комментарий-->
<!--                        <textarea class="form-control compensationHistoryEditComment m-t m-b" name="compensationHistoryEditComment" id="compensationHistoryEditComment" rows="5" placeholder=""></textarea>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!---->
<!--                <div class="row">-->
<!--                    <div class="col-md-12 text-center">-->
<!--                        <div class="col-sm-8 col-sm-offset-2 form-group">-->
<!--                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>-->
<!--                            <a class="btn btn-success editHistoryCompensation">Сохранить</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</div>

<?php $this->registerCssFile('/js/datepicker/datepicker.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js', ['position' => yii\web\View::POS_END]); ?>

<script>
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

    $('document').on('click','.editHistory',function(){

    });

</script>
