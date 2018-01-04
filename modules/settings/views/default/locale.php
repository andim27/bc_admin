<?php define("ROOT_T", "http://" . $_SERVER['SERVER_NAME']);?>
<link rel="stylesheet" href="<?=ROOT_T?>/js/datatables/datatables.css" type="text/css">
<ul class="breadcrumb no-border no-radius b-b b-light pull-in">
    <li><a href="/"><i class="fa fa-home"></i> Главная</a></li>
    <li><a href="/settings">Настройки</a></li>
    <li class="active">Языковые стандарты</li>
</ul>
<section class="panel panel-default">
    <header class="panel-heading bg-light">
        <ul class="nav nav-tabs ">
            <li class="active"><a href="#lang_list" data-toggle="tab">Список языков</a></li>
            <li class=""><a href="#translate_list" data-toggle="tab">Перевод</a></li>
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content">
            <div class="tab-pane active" id="lang_list">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        DataGrid
                        <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                    </header>
                    <div class="table-responsive">
                        <table id="lang_list_table" class="table table-striped m-b-none" data-ride="datatables">
                            <thead>
                            <tr>
                                <th style="display: none;">id</th>
                                <th width="40%">Название языка</th>
                                <th width="45%">Сокращение</th>
                                <th width="15%">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </section>
                <a href="#" class="btn btn-s-md btn-danger pull-right">Добавить</a>
            </div>
            <div class="tab-pane" id="translate_list">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        <select class="btn dropdown-toggle selectpicker btn-default">
                            <option>English language</option>
                            <option>Українська мова</option>
                            <option>Русский язык</option>
                        </select>
                        <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i>
                    </header>
                    <div class="table-responsive">
                        <table class="table table-striped m-b-none" data-ride="datatables2">
                            <thead>
                            <tr>
                                <th width="40%">Слово или фраза</th>
                                <th width="45%">Перевод</th>
                                <th width="15%">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>

</section>

