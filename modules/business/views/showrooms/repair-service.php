<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Ремонт товаров (сервисный центр)</h3>
</div>
<section class="panel panel-default">

    <header class="panel-heading bg-light no-borders">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#main" data-toggle="tab">Оборудование в ремонте</a></li>
            <li><a href="#acceptEquipment" data-toggle="tab">Принять / Редактировать оборудование</a></li>
        </ul>
    </header>

    <div class="panel-body">

        <div class="tab-content">
            <div class="tab-pane active" id="main"> 

                <!-- Оборудование в ремонте -->
                <div class="table-responsive">
                    <table id="table-under-repair" class="table table-under-repair table-striped datagrid m-b-sm">
                        <thead>
                            <tr>
                                <th>
                                    №
                                </th>
                                <th>
                                    Страна
                                </th>
                                <th>
                                    Город
                                </th>
                                <th>
                                    ФИО
                                </th>
                                <th>
                                    Номер телефона
                                </th>
                                <th>
                                    Название продукта
                                </th>
                                <th>
                                    Серийный номер
                                </th>
                                <th>
                                    Гарантия
                                </th>
                                <th>
                                    Дата отправки в ремонт
                                </th>
                                <th>
                                    Дата принятия в ремонт
                                </th>
                                <th>
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Vasya ass</td>
                                <td>+79065711111</td>
                                <td>Life Expert</td>
                                <td>222224444 <a class="showEquipmentInfo m-l" href="#showEquipmentInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>Да</td>
                                <td>24.01.2019</td>
                                <td>28.01.2019</td>
                                <td>
                                    <a class="editRepairEquipment m-l" href="#" >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="deleteRepairEquipment m-l" href="#deleteRepairEquipment"  data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Vasya ass</td>
                                <td>+79065711111</td>
                                <td>Life Expert</td>
                                <td>222224444 <a class="showEquipmentInfo m-l" href="#showEquipmentInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>Да</td>
                                <td>24.01.2019</td>
                                <td>28.01.2019</td>
                                <td>
                                    <a class="editRepairEquipment m-l" href="#" >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="deleteRepairEquipment m-l" href="#deleteRepairEquipment"  data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>РФ</td>
                                <td>Уфа</td>
                                <td>Vasya ass</td>
                                <td>+79065711111</td>
                                <td>Life Expert</td>
                                <td>222224444 <a class="showEquipmentInfo m-l" href="#showEquipmentInfo" data-toggle="modal"><i class="fa fa-eye"></i></a></td>
                                <td>Да</td>
                                <td>24.01.2019</td>
                                <td>28.01.2019</td>
                                <td>
                                    <a class="editRepairEquipment m-l" href="#" >
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="deleteRepairEquipment m-l" href="#deleteRepairEquipment"  data-toggle="modal">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <input type="button" class="btn btn-success pull-right addAcceptEquipment m-sm" value="Принять оборудование">
                    </div>
                </div>

            </div>

            <div class="tab-pane" id="acceptEquipment"> 
                <div class="row m-b text-center">
                    <h3 class="acceptEquipmentHeader">Приём оборудования</h3>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t-xs">ФИО:</p>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <p class="m-t-xs">Телефон:</p>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="row m-t">
                    <div class="col-md-3">
                        <p class="m-t-xs">Дата:</p>
                    </div>
                    <div class="col-md-3">
                        <input id="issueFrom" class="input-s datepicker-input inline input-date-accept-equipment form-control text-center"
                            size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                    </div>
                </div>

                <div class="row  m-t">

                    <div class="col-md-3">
                        <p class="m-t-xs">Наименование оборудования:</p>
                    </div>
                    <div class="col-md-3">
                        <select name="acceptEquipmentItemSelect" class="acceptEquipmentItemSelect inline form-control">
                            <option value="null">Life balance</option>
                            <option value="1">Отгружено</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <p class="m-t-xs">Серийный номер:</p>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control">
                    </div>

                </div>

                <div class="row m-t">
                    <div class="col-md-3">
                        <p class="m-t">Описание проблемы работы прибора:</p>
                    </div>
                    <div class="col-md-8">
                        <textarea class="form-control acceptEquipmentComment m-t m-b" name="acceptEquipmentComment" id="acceptEquipmentComment" rows="2" placeholder=""></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <p class="m-t">Комментарий сервисного центра:</p>
                    </div>
                    <div class="col-md-8">
                        <textarea class="form-control acceptEquipmentServiceComment m-t m-b" name="acceptEquipmentServiceComment" id="acceptEquipmentServiceComment" rows="2" placeholder=""></textarea>
                    </div>
                </div>

                <div class="row m-b">
                    <div class="col-md-3">
                        <p>Не гарантийный случай:</p>
                    </div>
                    <div class="col-md-3">
                        <input type="checkbox" >
                    </div>

                    <div class="col-md-2 text-center m-t-sm">
                        <a href="javascript:void();" class="btnOpenModalAddFile">
                            <i class="fa fa-cloud-upload text" title="Добавить файл"></i> Добавить файл
                        </a>
                    </div>
                    <div class="col-md-4">
                            <table class="table datagrid m-b-sm requestsFiles">
                                <thead>
                                <tr>
                                    <th colspan="3">Прикреплённые файлы</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger acceptEquipmentSaveCancel">Отмена</a>
                            <a class="btn btn-success acceptEquipmentSave">Сохранить</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

<div class="modal fade" id="showEquipmentInfo">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <div class="row m-t m-b">
                    <div class="col-md-4">
                        <p>№ заявки:</p>
                    </div>
                    <div class="col-md-8">
                        <span class="font-bold showEquipmentInfoIndex">1</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-4">
                        <p>Наименование прибора:</p>
                    </div>
                    <div class="col-md-8">
                        <span class="font-bold showEquipmentInfoItemName">Life Balance</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-4">
                        <p>Дата приёма:</p>
                    </div>
                    <div class="col-md-8">
                        <span class="font-bold showEquipmentInfoDateAccepted">21.01.2019</span>
                    </div>
                </div>

                <div class="row m-t m-b-lg">
                    <div class="col-md-12">
                        <p>Описание проблемы работы прибора:</p>
                    </div>
                    <div class="col-md-12">
                        <span class="font-bold showEquipmentInfoProblem">Не включаеться. Нет индикации.</span>
                    </div>
                </div>

                <div class="row m-t m-b-lg">
                    <div class="col-md-12">
                        <p>Комментарии сервисного центра:</p>
                    </div>
                    <div class="col-md-12">
                        <span class="font-bold showEquipmentServiceComment">Не включаеться. Нет индикации.</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-4">
                        <p>Прикреплённые файлы:</p>
                    </div>
                    <div class="col-md-8">
                        <ul class="list-unstyled">
                            <li>
                                сф. 22.01.19  
                                <a class="m-l" href="#">
                                    <i class="fa fa-cloud-download"></i>
                                </a>
                            </li>
                            <li>
                                сф. 22.01.19 
                                <a class="m-l" href="#">
                                    <i class="fa fa-cloud-download"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-success" data-dismiss="modal">Закрыть</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteRepairEquipment">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                <div class="row m-t m-b">
                    <div class="col-md-4">
                        <p>№ заявки:</p>
                    </div>
                    <div class="col-md-8">
                        <span class="font-bold showEquipmentInfoIndex">1</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-4">
                        <p>Наименование прибора:</p>
                    </div>
                    <div class="col-md-8">
                        <span class="font-bold showEquipmentInfoItemName">Life Balance</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-4">
                        <p>Дата приёма:</p>
                    </div>
                    <div class="col-md-8">
                        <span class="font-bold showEquipmentInfoDateAccepted">21.01.2019</span>
                    </div>
                </div>

                <div class="row m-t m-b-lg">
                    <div class="col-md-12">
                        <p>Описание проблемы работы прибора:</p>
                    </div>
                    <div class="col-md-12">
                        <span class="font-bold showEquipmentInfoProblem">Не включаеться. Нет индикации.</span>
                    </div>
                </div>

                <div class="row m-t m-b-lg">
                    <div class="col-md-12">
                        <p>Комментарии сервисного центра:</p>
                    </div>
                    <div class="col-md-12">
                        <span class="font-bold showEquipmentServiceComment">Не включаеться. Нет индикации.</span>
                    </div>
                </div>

                <div class="row m-t m-b">
                    <div class="col-md-4">
                        <p>Прикреплённые файлы:</p>
                    </div>
                    <div class="col-md-8">
                        <ul class="list-unstyled">
                            <li>
                                сф. 22.01.19  
                                <a class="m-l" href="#">
                                    <i class="fa fa-cloud-download"></i>
                                </a>
                            </li>
                            <li>
                                сф. 22.01.19 
                                <a class="m-l" href="#">
                                    <i class="fa fa-cloud-download"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success deleteEquipment">Удалить</a>
                        </div>
                    </div>
                </div>

                 <div class="row m-t">
                    <div class="col-md-12 text-center">
                        <p class="text-danger">*Удаление произойдёт только после подтверждения администратора</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpload">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">x</button>

                <form name="formUploadFile" class="formUploadFile">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="col-sm-8 col-sm-offset-2 form-group">
                                <label>Название файла</label>
                                <input type="text" class="form-control fileName" name="fileName" value="" required="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="form-group">
                                <input type="file" class="filestyle filePath" name="fileData" data-buttonText="Выбрать файл"  data-iconName="fa fa-cloud-upload"
                                       data-classButton="btn btn-default m-b-5" data-classInput="form-control inline input-s"
                                       accept="application/pdf">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="col-sm-8 col-sm-offset-2 form-group">
                                <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                                <button type="submit" class="btn btn-success uploadFile" disabled>Загрузить</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->registerCssFile('/js/datepicker/datepicker.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/bootstrap-filestyle.min.js', ['position' => yii\web\View::POS_END]); ?>

<script>
    
    $('#editAcceptedEquipment').on('shown.bs.modal', function () {
        // вызываеться перед открытием модального окна редактирования неприкреплённого заказа - выдёргиваем заказ из БД и подгружаем в окно

    })

     $('.panel-body').on('click', '.addAcceptEquipment', function () {
        // плюсик - приём оборудования в ремонт - создаём новую заявку


        $('.acceptEquipmentHeader').html('Приём оборудования');
        $('a[href="#acceptEquipment"]').tab('show');
    });

    $('.panel-body').on('click', '.editRepairEquipment', function () {
        // Нажали на кнопку отредактировать заявку

        // Тут выдёргиваем данные из БД по этой заявки , подкидываем их в панель #acceptEquipment и показываем эту панель


        $('.acceptEquipmentHeader').html('Редактирование оборудования');
        $('a[href="#acceptEquipment"]').tab('show');
    });

    
    
    $('#modalUpload').on('change','.fileName, .filePath',function(){
        if (($('.fileName').val() != '') && ($('.filePath').val() != '')) {
            $('.uploadFile').removeAttr("disabled");
        } else {
            $('.uploadFile').attr("disabled", true);
        }
    });

    $('#modalUpload').on('submit','.formUploadFile',function(e){
        e.preventDefault();

        var form = $(this);


        var modal = form.closest('#modalUpload');
        var blInfo = $('.requestInfo');

        var data = new FormData($(this)[0]);
        data.append('id', $('.requestInfo .request-id').val());

        $.ajax({
            url: '/ru/business/showrooms/add-file-request-open',
            type: 'POST',

            data: data,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            beforeSend: function () {
                modal.find('.panel-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                modal.find('.loader').remove();
            },
            success: function(msg){

                console.log(msg);

                if(msg.error){

                } else {
                    blInfo.find('.requestsFiles>tbody').append(`
                            <tr>
                                <td>${ msg.title }</td>
                                <td class="w-69"><a href="/ru/business/showrooms/get-file-request-open?id=${ msg.id }&key=${ msg.key }" class="btn btn-success btn-sm">Скачать</a></td>
                                <td class="w-69"><button type="button" data-file-id="${ msg.key }" data-request-id="${ msg.id }" class="btn btn-danger btn-sm deleteFile">Удалить</button></td>
                            </tr>
                        `);

                    $('#modalUpload').modal("hide");
                }
            }
        });


    });

    $('.btnOpenModalAddFile').on('click',function () {
        $('#modalUpload').find('.formUploadFile').trigger('reset');
        $('#modalUpload').modal('show');
    });

    $('.requestsFiles').on('click','.deleteFile',function(){
        var btn = $(this);
        var bl = btn.closest('tr');
        var blInfo = $('.requestInfo');

        $.ajax({
            url: '/ru/business/showrooms/delete-file-request-open',
            type: 'GET',
            data: {id:btn.data('request-id'),key:btn.data('file-id')},
            beforeSend: function () {
                blInfo.find('.panel-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                blInfo.find('.loader').remove();
            },
            success: function(msg){
                bl.remove();
            }
        });
    });

</script>