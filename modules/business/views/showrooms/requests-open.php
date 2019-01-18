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

            </tbody>
        </table>
    </div>
</section>

<div class="panel panel-default reauestInfo">
  <div class="panel-body">
      <div class="row">
          <div class="col-sm-12">
            <p class="m-t m-b">Дата заявки: <strong>26th Mar 2013</strong></p>
            <p class="m-t m-b">Логин: <strong>mai</strong></p>
            <p class="m-t m-b">ФИО: <strong>Петрова Светлана</strong></p>
            <p class="m-t m-b">Страна: <strong>Россия</strong></p>
            <p class="m-t m-b">Город: <strong>Москва</strong></p>
            <p class="m-t m-b">Контакты профиля: <strong>Россия петрозаводск +784444144</strong></p>
            <p class="m-t m-b">Текст с формы: <strong>блабла бла бал ба лаб алаб ал ла абал ба ла ла бюал аб ла бал аб ал бал аб ла</strong></p>
            <p class="m-t m-b">Статус карьеры (регионан.мен): <strong>Специалист</strong></p>
            <p class="m-t m-b">Условия 10000/3: <strong>НЕТ</strong></p>
          </div>
        </div>

        <div class="row">
            <div class="col-sm-12 imgRequestsContainer">
                <a href="/images/backgrounds/1.jpg" data-lightbox="roadtrip"><img src="/images/backgrounds/1.jpg" class="imgRequests"></a>
                <a href="/images/backgrounds/2.jpg" data-lightbox="roadtrip"><img src="/images/backgrounds/2.jpg" class="imgRequests"></a>
                <a href="/images/backgrounds/3.jpg" data-lightbox="roadtrip"><img src="/images/backgrounds/3.jpg" class="imgRequests"></a>
                <a href="/images/backgrounds/4.jpg" data-lightbox="roadtrip"><img src="/images/backgrounds/4.jpg" class="imgRequests"></a>
                <a href="/images/backgrounds/5.jpg" data-lightbox="roadtrip"><img src="/images/backgrounds/5.jpg" class="imgRequests"></a>
                <a href="/images/backgrounds/6.jpg" data-lightbox="roadtrip"><img src="/images/backgrounds/6.jpg" class="imgRequests"></a>
                <a href="/images/backgrounds/7.jpg" data-lightbox="roadtrip"><img src="/images/backgrounds/7.jpg" class="imgRequests"></a>
            </div>
        </div>
        <div class="row m-t-lg">
            <div class="col-sm-5">
                <textarea class="form-control" rows="9" placeholder="Внутренние замечания администрации..."></textarea>

            </div>
            <div class="col-sm-4">
              <div class="col-sm-12 text-center m-b">
                  <button class="btn btn-default center"><i class="fa fa-cloud-upload text"></i> Добавить файл</button>
              </div>
              <table class="table datagrid m-b-sm requestsFiles">
                  <thead>
                  <tr>
                      <th>Название файла</th>
                      <th class="w-69"></th>
                      <th class="w-69"></th>
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
                <select name="account" class="form-control m-b"> 
                    <option>На рассмотрении</option> 
                    <option>Отклонено</option> 
                    <option>Одобрено</option>
                </select>
                <label class="control-label"><strong>Проверяющий</strong></label>
                <select name="account" class="form-control m-b"> 
                    <option>Вильховая </option> 
                    <option>Черногубов</option> 
                    <option>и т.д.</option>
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
              <input type="button" class="btn btn-success pull-right" value="Сохранить">
            </div>
        </div>
  </div>
</div>

<?php $this->registerCssFile('/css/lightbox.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/lightbox/lightbox.js', ['position' => yii\web\View::POS_END]); ?>

<script>

$(document).ready(function() {

} );

    var table = $('.table-users');

    table = table.dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/' + LANG + '/business/user'
        },
        "columns": [
            {"data": "created"},
            {"data": "username"},
            {"data": "full_name"},
            {"data": "country_city"},
            {"data": "phoneNumber"},
            {"data": "phoneNumber"},
            {"data": "rank"},
            {"data": "phoneNumber"},
            {"data": "phoneNumber"},
            {
              "data": null,
              "defaultContent": '<a href="#"><i class="fa fa-pencil"></i></a>'
            }
        ],
        "order": [[ 0, "asc" ]]
    })

</script>
