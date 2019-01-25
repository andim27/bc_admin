<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use app\models\api\ShowroomsRequestsOpen;
    use app\models\api\User;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Заявки на открытие</h3>
</div>
<section class="panel panel-default">
    <div class="table-responsive">
        <table id="table-requests" class="table table-users table-striped datagrid m-b-sm">
            <thead>
                <tr>
                    <th>
                      Дата заявки
                    </th>
                    <th>
                      <?=THelper::t('user_login')?>
                    </th>
                    <th>
                      <?=THelper::t('user_fname_sname')?>
                    </th>
                    <th>
                      <?=THelper::t('country')?>
                    </th>
                    <th>
                      <?=THelper::t('city')?>
                    </th>
                    <th>
                      Контакты профиля
                    </th>
                    <th>
                      Текст формы
                    </th>
                    <th>
                      Статус карьеры
                    </th>
                    <th>
                      Условия 1000/3
                    </th>
                    <th>
                      Статус заявки
                    </th>
                    <th>
                      
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($requestsOpen)){?>
                    <?php foreach($requestsOpen as $request){?>
                        <tr>
                            <td><?=$request->created_at?></td>
                            <td><?=$request->userLogin?></td>
                            <td><?=$request->userSecondName?> <?=$request->userFirstName?></td>
                            <td><?=$request->country?></td>
                            <td><?=$request->city?></td>
                            <td>
                                <?=$request->userCountry?>, <?=$request->userCity?><br>
                                <?=$request->userAddress?>
                            </td>
                            <td><?=$request->text?></td>
                            <td><?=$request->userRank?></td>
                            <td>???</td>
                            <td><?=ShowroomsRequestsOpen::getStatusRequestOpenValue($request->status)?></td>
                            <td>
                                <a class="editRequest" href="javascript:void(0);" data-id="<?=$request->id?>">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            </tbody>
        </table>
    </div>
</section>

<div class="panel panel-default requestInfo">
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 requestMeta">
                <p class="m-t m-b">Дата заявки: <strong class="request-date"></strong></p>
                <p class="m-t m-b">Логин: <strong class="request-login"></strong></p>
                <p class="m-t m-b">ФИО: <strong class="request-fio"></strong></p>
                <p class="m-t m-b">Страна: <strong class="request-country"></strong></p>
                <p class="m-t m-b">Город: <strong class="request-city"></strong></p>
                <p class="m-t m-b">Контакты профиля: <strong class="request-contacts"></strong></p>
                <p class="m-t m-b">Текст с формы: <strong class="request-text"></strong></p>
                <p class="m-t m-b">Статус карьеры (регионан.мен): <strong class="request-stateCarrer"></strong></p>
                <p class="m-t m-b">Условия 10000/3: <strong class="request-conditions"></strong></p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 imgRequestsContainer">
            </div>
        </div>

        <form action="" class="formUpdateRequest">

            <?=Html::hiddenInput('id','',['class'=>'request-id'])?>

            <div class="row m-t-lg">
                <div class="col-sm-5">
                    <textarea class="form-control request-comment" rows="9" name="comment" placeholder="Внутренние замечания администрации..."></textarea>
                </div>
                <div class="col-sm-4">
                    <div class="col-sm-12 text-center m-b">
                        <a href="#modalUpload" data-toggle="modal">
                            <i class="fa fa-cloud-upload text" title="Добавить файл"></i> Добавить файл
                        </a>
                    </div>
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
                <div class="col-sm-3">
                    <label class="control-label"><strong>Статус заявки</strong></label>
                    <?=Html::dropDownList('status',false,ShowroomsRequestsOpen::getStatusRequestOpen(),[
                        'class' => 'form-control m-b requestStateSelect'
                    ])?>

                    <label class="control-label"><strong>Проверяющий</strong></label>
                    <?=Html::dropDownList('userHowCheck',false,User::getListUserHighTopManager(),[
                        'class' => 'form-control m-b requestVerifierSelect'
                    ])?>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success pull-right updateRequest" value="Сохранить">
                </div>
            </div>
        </form>

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

<?php $this->registerCssFile('/css/lightbox.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/lightbox/lightbox.js', ['position' => yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('/js/bootstrap-filestyle.min.js', ['position' => yii\web\View::POS_END]); ?>

<script>

    
    $('table').on('click','.editRequest',function(){
        var blInfo = $('.requestInfo');

        clearRequestInfoForm();

        blInfo.show();

        $.ajax({
            url: '/ru/business/showrooms/get-requests-open',
            type: 'POST',
            data: {id:$(this).data('id')},
            beforeSend: function () {
                blInfo.find('.panel-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                blInfo.find('.loader').remove();
            },
            success: function(dataReq){

                console.log(dataReq);

                blInfo.find('.request-id').val(dataReq.id);
                blInfo.find('.request-date').html(dataReq.created_at);
                blInfo.find('.request-login').html(dataReq.login);
                blInfo.find('.request-fio').html(dataReq.userSecondName + ' ' + dataReq.userFirstName);
                blInfo.find('.request-country').html(dataReq.countryTitle);
                blInfo.find('.request-city').html(dataReq.city);
                blInfo.find('.request-contacts').html(dataReq.userCountry + ', ' + dataReq.userCity + ', ' + dataReq.userAddress);
                blInfo.find('.request-text').html(dataReq.text);
                blInfo.find('.request-stateCarrer').html(dataReq.userRank);
                blInfo.find('.request-conditions').html('????');
                blInfo.find('.request-comment').val(dataReq.comment);

                blInfo.find('.requestStateSelect').val(dataReq.status);
                blInfo.find('.requestVerifierSelect').val(dataReq.userHowCheckId);

                if (dataReq.imagesUser.length > 0 ) {
                    for (const image of dataReq.imagesUser) {
                        blInfo.find('.imgRequestsContainer').append(`<a href="${ image }" data-lightbox="roadtrip"><img src="${ image }" class="imgRequests"></a>`);
                    }
                }

                if($.isEmptyObject(dataReq.filesAdmin) === false){
                    $.each(dataReq.filesAdmin, function(index, value) {
                        blInfo.find('.requestsFiles>tbody').append(`
                            <tr>
                                <td>${ value.title }</td>
                                <td class="w-69"><a href="/ru/business/showrooms/get-file-request-open?id=${ index }&key=${ dataReq.id }" class="btn btn-success btn-sm">Скачать</a></td>
                                <td class="w-69"><button type="button" data-file-id="${ index }" data-request-id="${ dataReq.id }" class="btn btn-danger btn-sm deleteFile">Удалить</button></td>
                            </tr>
                        `);
                    });
                }
            }
        });
    } );

    $('.requestInfo').on('submit','.formUpdateRequest',function (e) {

        e.preventDefault();

        var form = $(this);
        var blInfo = form.closest('.requestInfo');
        var data = form.serialize();

        $.ajax({
            url: '/ru/business/showrooms/update-request-open',
            type: 'POST',
            data: data,
            beforeSend: function () {
                blInfo.find('.panel-body').append('<div class="loader"><div></div></div>');
            },
            complete: function () {
                blInfo.find('.loader').remove();
            },
            success: function(msg){
                console.log('+')
            }
        });
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

                blInfo('.requestsFiles>tbody').append(`
                            <tr>
                                <td>${ msg.title }</td>
                                <td class="w-69"><a href="/ru/business/showrooms/get-file-request-open?id=${ msg.id }&key=${ msg.key }" class="btn btn-success btn-sm">Скачать</a></td>
                                <td class="w-69"><button type="button" data-file-id="${ msg.key }" data-request-id="${ msg.id }" class="btn btn-danger btn-sm deleteFile">Удалить</button></td>
                            </tr>
                        `);

                form.trigger('reset');

                $('#modalUpload').modal("hide");
            }
        });


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

    function clearRequestInfoForm(){
        var blInfo = $('.requestInfo');

        blInfo.find('.request-id').val('');
        blInfo.find('.request-date').empty();
        blInfo.find('.request-login').empty();
        blInfo.find('.request-fio').empty();
        blInfo.find('.request-country').empty();
        blInfo.find('.request-city').empty();
        blInfo.find('.request-contacts').empty();
        blInfo.find('.request-text').empty();
        blInfo.find('.request-stateCarrer').empty();
        blInfo.find('.request-conditions').empty();
        blInfo.find('.requestStateSelect').val('');
        blInfo.find('.requestVerifierSelect').val('');
        blInfo.find('.request-comment').val('');
        blInfo.find('.imgRequestsContainer').empty();

        blInfo.find('.imgRequestsContainer').empty();
        blInfo.find('.requestsFiles>tbody').empty();
    }

</script>
