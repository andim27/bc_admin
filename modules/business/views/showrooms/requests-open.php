<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
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
                      <?=THelper::t('user_country_city')?>
                    </th>
                    <th>
                      Контакты профиля
                    </th>
                    <th>
                      Текст формы
                    </th>
                    <th>
                      <?=THelper::t('user_rank')?>
                    </th>
                    <th>
                      Условия
                    </th>
                    <th>
                      Статус заявки
                    </th>
                    <th>
                      
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
                    <td><a class="editRequest" href="#"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td><a class="editRequest" href="#"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td><a class="editRequest" href="#"><i class="fa fa-pencil"></i></a></td>
                </tr>

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
            <p class="m-t m-b">ФИО: <strong class="request-FIO"></strong></p>
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
        <div class="row m-t-lg">
            <div class="col-sm-5">
                <textarea class="form-control requestAdminText" rows="9" placeholder="Внутренние замечания администрации..."></textarea>

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
                <select name="account" class="form-control m-b requestStateSelect"> 
                    <option value="0">На рассмотрении</option> 
                    <option value="1">Отклонено</option> 
                    <option value="2">Одобрено</option>
                </select>
                <label class="control-label"><strong>Проверяющий</strong></label>
                <select name="account" class="form-control m-b requestVerifierSelect"> 
                    <option value="firely">Вильховая </option> 
                    <option value="main" >Черногубов</option> 
                    <option value="1">и т.д.</option>
                </select>

                <!-- ?= Select2::widget([
                    'name' => 'list_action',
                    'data' => $listAction,
                    'value' => (!empty($request['list_action']) ? $request['list_action'] : ''),
                    'options' => [
                        'placeholder' => 'Выберите действия',
                        'multiple' => true
                    ]
                    ]);
                ?>-->

                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
              <input type="button" class="btn btn-success pull-right saveRequest" value="Сохранить">
            </div>
        </div>
  </div>
</div>

<div class="modal fade" id="modalUpload">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">x</button>

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
                            <input type="file" class="filestyle filePath" data-buttonText="Выбрать файл"  data-iconName="fa fa-cloud-upload"
                                    data-classButton="btn btn-default m-b-5" data-classInput="form-control inline input-s">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="col-sm-8 col-sm-offset-2 form-group">
                            <a class="btn btn-danger" data-dismiss="modal">Отмена</a>
                            <a class="btn btn-success uploadFile" disabled>Загрузить</a>
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

    var dataReq = {
        metadata : {
            date : '26.12.1996',
            login : 'mai',
            FIO : 'Петрова Светлана',
            country : 'Россия',
            city : 'Москва',
            contacts : 'Россия петрозаводск +784444144',
            text : 'блабла бла бал ба лаб алаб ал ла абал ба ла ла бюал аб ла бал аб ал бал аб ла',
            stateCarrer: 'Специалист 2',
            conditions : 'НЕТ'
        },
        state: 1,
        verifierID: 'main',
        adminText: 'Примечание администрации',
        files : [
            {
                id : 'dsjfhjshfjhsdhf22',
                name : 'Договор лизинга',
                src : '/requestsFiles/mai/Договор1.pdf'
            },
            {
                id : 'dsjfhjshfjhsdhf21111',
                name : 'Договор лизинга 2',
                src : '/requestsFiles/mai/Договор2.pdf'
            }
        ],
        images : [
            '/images/backgrounds/1.jpg',
            '/images/backgrounds/2.jpg',
            '/images/backgrounds/3.jpg',
            '/images/backgrounds/4.jpg',
            '/images/backgrounds/5.jpg',
            '/images/backgrounds/6.jpg',
            '/images/backgrounds/7.jpg'
        ]
    };
    
    $('table').on('click','.editRequest',function(){
       // console.log($(this).parents('tr')[0].rowIndex);
        // ну тут подгружаем данные по заявке из БД
        

        // тут закинули инфо в мета
        for (const key in dataReq.metadata) {
            if (dataReq.metadata.hasOwnProperty(key)) {
                const element = dataReq.metadata[key];
                $(`.request-${ key }`).html(element);
            }
        }
        
        // тут собрали картиночный див        
        $('.imgRequestsContainer').empty();

        if (dataReq.images.length > 0 ) {
            for (const image of dataReq.images) {
                $('.imgRequestsContainer').append(`<a href="${ image }" data-lightbox="roadtrip"><img src="${ image }" class="imgRequests"></a>`);
            }
        }

        // тут собрали файлы
        refreshFilesTable(dataReq.files);

        // статус заявки
        $('.requestStateSelect').val(dataReq.state);

        // проверяющий
        $('.requestVerifierSelect').val(dataReq.verifierID);

        // текст примечания администрации
        $('.requestAdminText').val(dataReq.adminText);

       // и отображаем её
        $('.requestInfo').show();

    } );

    $('.requestInfo').on('click','.saveRequest',function(){
        console.log('saveRequest');

        console.log(dataReq);
        // тут пушем новые данные в заявку в БД

        // и скрываем окно с данными заявки
        $('.requestInfo').hide();
    });

    $('.modal').on('change','.fileName, .filePath',function(){
        if (($('.fileName').val() != '') && ($('.filePath').val() != '')) {
           $('.uploadFile').removeAttr("disabled");
        } else {
            $('.uploadFile').attr("disabled", true);
        }
    });

    $('.requestInfo').on('change','.requestStateSelect',function(){
      // поменяли статус заявки, т.е. подкидываем новый статус в наш датасет, чтобы при сохранении его изменить в БД
        dataReq.state = $( ".requestStateSelect" ).val();
    });

    $('.requestInfo').on('change','.requestAdminText',function(){
      // поменяли примечание администрации, т.е. подкидываем его в наш датасет, чтобы при сохранении его изменить в БД
        dataReq.adminText = $( ".requestAdminText" ).val();
    });

    $('.requestInfo').on('change','.requestVerifierSelect',function(){
      // поменяли проверяющего, нового записали в наш датасет, чтобы при сохранении его изменить в БД
        dataReq.verifierID = $( ".requestVerifierSelect" ).val();
    });

    $('.modal').on('click','.uploadFile',function(){
        // подгружаем файл на сервак


        // добавляем его в наш массив файлов
        let newFile = {};

        newFile.id = '1221' // ИД берём из БД
        newFile.name = $('.fileName').val();
        newFile.src = $('.filePath').val(); // или с БД

        dataReq.files.push(newFile);

        $('.modal').modal("hide");

         // рефрешим таблицу файлов
         refreshFilesTable(dataReq.files);

         // ну и почистили форму         
         $('.fileName').val('');
         $(":file").filestyle('clear');

    });


    $('.requestsFiles').on('click','.deleteFile',function(){
        // Удаляем файл с сервака


        // Удаляем файл с датасета ну или подгружаем с бд заново файлы в наш массив dataReq.files
        dataReq.files.splice([dataReq.files.findIndex(x => x.id === this.dataset.file)],1);

        // рефрешим таблицу файлов
        refreshFilesTable(dataReq.files)

    });


    function refreshFilesTable(files) {
        $('.requestsFiles>tbody').empty();
        if (files.length > 0 ) { 
            for (const file of files) {
                $('.requestsFiles>tbody').append(`
                    <tr>
                        <td>${ file.name }</td>
                        <td class="w-69"><a href="${ file.src }" class="btn btn-success btn-sm">Скачать</a></td>
                        <td class="w-69"><a href="#" data-file="${ file.id }" class="btn btn-danger btn-sm deleteFile">Удалить</a></td>
                    </tr>
                `);
            }            
        }
    }


</script>
