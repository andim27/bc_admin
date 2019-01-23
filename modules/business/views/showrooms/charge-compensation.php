<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Начисление компенсаций</h3>
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
                    <li class="active mainLi"><a href="#main" data-toggle="tab">Сводная</a></li>
                    <li class="historyLi"><a href="#history" data-toggle="tab">История</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="main"> 
                        <!-- Сводная -->
                        <div class="row">
                            <div class="col-md-12 m-b">
                                <input id="mainMonth" class="input-s datepicker-input inline input-showroom form-control text-center" type="text" value="04/2013" data-date-format="mm/yyyy" data-date-language="ru" data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months">
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
                                            Логин
                                        </th>
                                        <th>
                                            ФИО
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
                                        <th colspan="3">
                                            Действие
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
                                        <td>11</td>
                                        <td>12</td>
                                        <td class="text-center"><a href="#history" class="historyCompensation" data-toggle="tab">История</a></td>
                                        <td class="text-center"><a href="#topUpCompensation" data-toggle="modal" class="topUpCompensation">Пополнить</a></td>
                                        <td class="text-center"><a href="#chargeCompensation" data-toggle="modal" class="chargeCompensation">Списать</a></td>
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
                                        <td>11</td>
                                        <td>12</td>
                                        <td class="text-center"><a href="#history" class="historyCompensation" data-toggle="tab">История</a></td>
                                        <td class="text-center"><a href="#topUpCompensation" data-toggle="modal" class="topUpCompensation">Пополнить</a></td>
                                        <td class="text-center"><a href="#chargeCompensation" data-toggle="modal" class="chargeCompensation">Списать</a></td>
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
                                        <td>11</td>
                                        <td>12</td>
                                        <td class="text-center"><a href="#history" class="historyCompensation" data-toggle="tab">История</a></td>
                                        <td class="text-center"><a href="#topUpCompensation" data-toggle="modal" class="topUpCompensation">Пополнить</a></td>
                                        <td class="text-center"><a href="#chargeCompensation" data-toggle="modal" class="chargeCompensation">Списать</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="history">
                        <!-- История -->
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
                <div class="row">
                    <div class="col-md-12">
                       <p>Шоу-рум <span class="font-bold cityShowroom m-l m-r">Новосибирск</span> Логин <span class="font-bold loginShowroom  m-l m-r">main</span>
                       </p> 
                    </div>
                    <div class="col-md-12 m-b-sm">
                        <h4>Иванов Иван Иванович</h4>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <select name="compensationTypeSelect" class="compensationTypeSelect form-control m-b"> 
                            <option value="1">Безнал</option> 
                            <option value="2">Нал</option> 
                            <option value="3">Бонусы</option> 
                            <option value="4" selected>Тугрики</option>
                            <option value="5">Виртуальное "Спасибо"</option>
                            <option value="6">Хер вам а не пополнение</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="compensationTopUpAmount" id="compensationTopUpAmount" placeholder="Сумма">
                    </div>
                    <div class="col-md-12">
                        Комментарий
                        <textarea class="form-control compensationTopUpComment m-t m-b" name="compensationTopUpComment" id="compensationTopUpComment" rows="5" placeholder=""></textarea>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success addTopUpCompensation">Начислить</a>
                        </div>
                    </div>
                </div>
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
                <div class="row">
                    <div class="col-md-12">
                       <p>Шоу-рум <span class="font-bold cityShowroom m-l m-r">Новосибирск</span> Логин <span class="font-bold loginShowroom  m-l m-r">main</span>
                       </p> 
                    </div>
                    <div class="col-md-12 m-b-sm">
                        <h4>Иванов Иван Иванович</h4>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control m-b" name="compensationСhargeAmount" id="compensationСhargeAmount" placeholder="Сумма">
                    </div>
                    <div class="col-md-12">
                        Комментарий
                       <textarea class="form-control  m-t m-b" name="compensationChargeComment" id="compensationChargeComment" rows="5"></textarea>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success addChargeCompensation">Списать</a>
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

    $('table').on('click','.historyCompensation',function(){
        $('.mainLi, .historyLi').toggleClass('active');
    });

    $('.modal').on('click','.addChargeCompensation',function(){
        // сохраняем списание

    });

    $('.modal').on('click','.addTopUpCompensation',function(){
        // сохраняем пополнение
        
    });   
    

</script>
