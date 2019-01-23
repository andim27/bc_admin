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
                        <a href="#" data-toggle="modal" data-target="#loadFile">
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
                        <tr>
                            <td>Договор.pdf</td>
                            <td><input type="button" class="btn btn-success btn-sm" value="Скачать"></td>
                            <td><input type="button" class="btn btn-danger btn-sm" value="Удалить"></td>
                        </tr>
                        <tr>
                            <td>Договор 2.pdf</td>
                            <td><input type="button" class="btn btn-success btn-sm" value="Скачать"></td>
                            <td><input type="button" class="btn btn-danger btn-sm" value="Удалить"></td>
                        </tr>
                        <tr>
                            <td>Договор 2.pdf</td>
                            <td><input type="button" class="btn btn-success btn-sm" value="Скачать"></td>
                            <td><input type="button" class="btn btn-danger btn-sm" value="Удалить"></td>
                        </tr>

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

<div class="modal fade" id="loadFile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal">x</button>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Название файла</label>
                        <input type="text" class="form-control" name="product" value="" required="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <input type="file" class="filestyle" data-buttonText="Выбрать файл"  data-iconName="fa fa-cloud-upload"
                                data-classButton="btn btn-default m-b-5" data-classInput="form-control inline input-s">
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
</div>

<?php $this->registerCssFile('/css/lightbox.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/lightbox/lightbox.js', ['position' => yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('/js/bootstrap-filestyle.min.js', ['position' => yii\web\View::POS_END]); ?>

<script>

    // var dataReq = {
    //     metadata : {
    //         date : '26.12.1996',
    //         login : 'mai',
    //         FIO : 'Петрова Светлана',
    //         country : 'Россия',
    //         city : 'Москва',
    //         contacts : 'Россия петрозаводск +784444144',
    //         text : 'блабла бла бал ба лаб алаб ал ла абал ба ла ла бюал аб ла бал аб ал бал аб ла',
    //         stateCarrer: 'Специалист 2',
    //         conditions : 'НЕТ'
    //     },
    //     state: 1,
    //     verifierID: 'main',
    //     files : [
    //         {
    //             id : 'dsjfhjshfjhsdhf22',
    //             name : 'Договор лизинга',
    //             src : '/requestsFiles/mai/Договор1.pdf'
    //         },
    //         {
    //             id : 'dsjfhjshfjhsdhf21111',
    //             name : 'Договор лизинга 2',
    //             src : '/requestsFiles/mai/Договор2.pdf'
    //         }
    //     ],
    //     images : [
    //         '/images/backgrounds/1.jpg',
    //         '/images/backgrounds/2.jpg',
    //         '/images/backgrounds/3.jpg',
    //         '/images/backgrounds/4.jpg',
    //         '/images/backgrounds/5.jpg',
    //         '/images/backgrounds/6.jpg',
    //         '/images/backgrounds/7.jpg'
    //     ]
    // };
    
    $('table').on('click','.editRequest',function(){
        var blInfo = $('.requestInfo');


        blInfo.find('.request-id').html('');
        blInfo.find('.request-date').html('');
        blInfo.find('.request-login').html('');
        blInfo.find('.request-fio').html('');
        blInfo.find('.request-country').html('');
        blInfo.find('.request-city').html('');
        blInfo.find('.request-contacts').html('');
        blInfo.find('.request-text').html('');
        blInfo.find('.request-stateCarrer').html('');
        blInfo.find('.request-conditions').html('');
        blInfo.find('.requestStateSelect').val('');
        blInfo.find('.requestVerifierSelect').val('');
        blInfo.find('.request-comment').val('');
        blInfo.find('.imgRequestsContainer').html('');
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

                // тут собрали картиночный див
                $('.imgRequestsContainer').empty();
                if (dataReq.imagesUser.length > 0 ) {
                    for (const image of dataReq.imagesUser) {
                        blInfo.find('.imgRequestsContainer').append(`<a href="${ image }" data-lightbox="roadtrip"><img src="${ image }" class="imgRequests"></a>`);
                    }
                }


                //
                // // тут собрали файлы
                // $('.requestsFiles>tbody').empty();
                // if (dataReq.filesAdmin.length > 0 ) {
                //     for (const file of dataReq.filesAdmin) {
                //         $('.requestsFiles>tbody').append(`
                //             <tr>
                //                 <td>${ file.name }</td>
                //                 <td class="w-69"><a href="${ file.src }" class="btn btn-success btn-sm">Скачать</a></td>
                //                 <td class="w-69"><a href="#" data-file="${ file.id }" class="btn btn-danger btn-sm deleteFile">Удалить</a></td>
                //             </tr>
                //         `);
                //     }
                // }
            }
        });



    } );

    $('.requestInfo').on('submit','.formUpdateRequest',function (e) {

        e.preventDefault();

        var form = $(this);
        var blInfo = form.closest('.requestInfo');
        var data = form.serialize();

        $.ajax({
            url: '/ru/business/showrooms/update-requests-open',
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
</script>
