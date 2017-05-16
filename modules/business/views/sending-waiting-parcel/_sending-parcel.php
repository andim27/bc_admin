<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>

<section class="panel panel-default">
    <div class="row">
        <div class="col-md-offset-9 col-md-3 form-group">
            <?=Html::a('<i class="fa fa-plus"></i>',['/business/sending-waiting-parcel/add-edit-parcel'],['class'=>'btn btn-default btn-block','data-toggle'=>'ajaxModal'])?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-translations table-striped datagrid m-b-sm">
            <thead>
            <tr>
                <th>№</th>
                <th>Дата добавления</th>
                <th>Кто отправил</th>
                <th>Откуда отправка</th>
                <th>Состав посылки</th>
                <th>Количество</th>
                <th>Куда отправленно</th>
                <th>Кто получает</th>
                <th>Чем отправленно</th>
                <th>Документы</th>
                <th>Состояние</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</section>
