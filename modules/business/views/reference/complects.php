<?php
use app\components\THelper;
use yii\helpers\Html;
use app\assets\GoodsAsset;
GoodsAsset::register($this);
?>

<style>
    .modal-dialog-product {
        width: 800px;!important;
        padding-top: 30px;
        padding-bottom: 30px;
    }
</style>
<div class="m-b-md">
    <h3 class="m-b-none">Complects</h3>
</div>
<section class="vbox">
    <header class="header bg-white b-b clearfix">
        <div class="row m-t-sm">
            <div class="col-sm-8 m-b-xs">

                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-default" title="Refresh">
                        <i class="fa fa-refresh"></i>
                    </button>
                </div>
                <a href="modal.html" data-toggle="ajaxModal" class="btn btn-sm btn-default">
                    <i class="fa fa-plus"></i> Create</a>
            </div>
            <div class="col-sm-4 m-b-xs">
                <div class="input-group">
                    <input class="input-sm form-control" placeholder="Search" type="text">
                    <span class="input-group-btn">
                                                    <button class="btn btn-sm btn-default" type="button">Go!</button>
                                                </span>
                </div>
            </div>
        </div>
    </header>
    <section class="scrollable wrapper w-f">
        <section class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped m-b-none">
                    <thead>
                    <tr>
                        <th class="th-sortable" data-toggle="class" width="60">ID
                            <span class="th-sort">
                                                                <i class="fa fa-sort-down text"></i>
                                                                <i class="fa fa-sort-up text-active"></i>
                                                                <i class="fa fa-sort"></i>
                                                            </span>

                        </th>
                        <th width="20"></th>
                        <th class="th-sortable" data-toggle="class">Название Комплекта
                            <span class="th-sort">
                                                                <i class="fa fa-sort-down text"></i>
                                                                <i class="fa fa-sort-up text-active"></i>
                                                                <i class="fa fa-sort"></i>
                                                            </span>
                        </th>
                        <th class="th-sortable" data-toggle="class" width="80">Цена
                            <span class="th-sort">
                                                                <i class="fa fa-sort-down text"></i>
                                                                <i class="fa fa-sort-up text-active"></i>
                                                                <i class="fa fa-sort"></i>
                                                            </span>
                        </th>
                        <th class="th-sortable" data-toggle="class" width="80">Бонус
                            <span class="th-sort">
                                                                <i class="fa fa-sort-down text"></i>
                                                                <i class="fa fa-sort-up text-active"></i>
                                                                <i class="fa fa-sort"></i>
                                                            </span>
                        </th>
                        <th>Баллы</th>
                        <th>Акции</th>
                        <th>Последняя продажа</th>
                        <th>Тип</th>
                        <th width="30"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            1
                        </td>
                        <td>
                            <a href="#modal" data-toggle="modal">
                                <i class="fa fa-search-plus"></i>
                            </a>
                        </td>
                        <td>VipCoin Комплект 1</td>
                        <td>150</td>
                        <td>25</td>
                        <td>30</td>
                        <td>936</td>
                        <td class="text-center">21/05/2018</td>
                        <td>2</td>
                        <td>
                            <i class="fa fa-check text-success text"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2
                        </td>
                        <td>
                            <a href="#modal" data-toggle="modal">
                                <i class="fa fa-search-plus"></i>
                            </a>
                        </td>
                        <td>Business Pack "VIP" (Вип) - Wellness Life Watch 2pcs + Life Animal</td>
                        <td>1005</td>
                        <td>150</td>
                        <td>150</td>
                        <td>20000</td>
                        <td class="text-center">21/05/2018</td>
                        <td>5</td>
                        <td>
                            <i class="fa fa-times text-danger text"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3
                        </td>
                        <td>
                            <a href="#modal" data-toggle="modal">
                                <i class="fa fa-search-plus"></i>
                            </a>
                        </td>
                        <td>Business Pack "Super" (Вип) - Wellness Life Watch 2pcs + Life Animal</td>
                        <td>1500</td>
                        <td>205</td>
                        <td>300</td>
                        <td>936</td>
                        <td class="text-center">21/05/2018</td>
                        <td>2</td>
                        <td>
                            <i class="fa fa-check text-success text"></i>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </section>
    </section>
    <footer class="footer bg-white b-t">
        <div class="row text-center-xs">
            <div class="col-md-6 hidden-sm">
                <p class="text-muted m-t">Показано 1-6 из 6</p>
            </div>
            <div class="col-md-6 col-sm-12 text-right text-center-xs">
                <ul class="pagination pagination-sm m-t-sm m-b-none">
                    <li>
                        <a href="#">
                            <i class="fa fa-chevron-left"></i>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#">1</a>
                    </li>
                    <li>
                        <a href="#">2</a>
                    </li>
                    <li>
                        <a href="#">3</a>
                    </li>
                    <li>
                        <a href="#">4</a>
                    </li>
                    <li>
                        <a href="#">5</a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
</section>



<div class="modal fade" id="modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-product">
        <div class="modal-content">
            <form role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Редактирование комплекта</h4>
                </div>
                <div class="modal-body">
                    <p class="m-b text-center font-bold">Business Pack Vip</p>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Название</label>
                            <div class="input-group">
                                <input class="form-control" value="Business Pack Vip" type="text">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default" tabindex="-1">RU</button>
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="#">RU</a>
                                        </li>
                                        <li>
                                            <a href="#">EN</a>
                                        </li>
                                        <li>
                                            <a href="#">TR</a>
                                        </li>
                                        <li>
                                            <a href="#">ES</a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- /btn-group -->
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>ИД комплекта</label>
                            <input class="form-control m-b" id="id" placeholder="ИД комплекта" value="12" type="text">
                            <label>Розничная цена</label>
                            <input class="form-control m-b" id="price" placeholder="Введите розничную цену (Euro)" value="150" type="text">
                            <label>Прямая премия</label>
                            <input class="form-control m-b" id="premia" placeholder="Прямая премия" value="150" type="text">


                        </div>
                        <div class="form-group col-md-6 text-center">

                            <label>Изображение</label><br>
                            <div class="row">
                                <img class="col-md-6 m100 fnone" src="images/pribor.png" alt="">
                            </div>

                            <input class="filestyle hidden" data-icon="false" data-classbutton="btn btn-default" data-classinput="form-control inline input-s" id="filestyle-0" style="position: fixed; left: -500px;" type="file">
                            <div class="bootstrap-filestyle" style="display: inline;">
                                <label for="filestyle-0" class="btn btn-default"><span>Выберите изображение</span></label>
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <label class="col-sm-7 control-label m-b">Разные премии по статусам</label>
                        <div class="col-sm-5 m-b">
                            <label class="switch">
                                <input id="difPremia" type="checkbox">
                                <span></span>
                            </label>
                        </div>

                        <div id="difShow" class="hidden">

                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Консультант</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Менеджер</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Региональный менеджер</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Топ-менеджер</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Региональный топ-менеджер</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Международный топ-менеджер</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Директор</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Региональный директор</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Международный директор</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Вицепрезидент</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Региональный вицепрезидент</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Международный вицепрезидент</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 m-b plnone">
                            <label class="col-sm-7 control-label">Продлевает активность BS (месяцев)</label>
                            <div class="col-sm-5">
                                <div id="MySpinner" class="spinner input-group" data-min="0" data-max="12">
                                    <input class="form-control spinner-input" value="0" name="spinner" maxlength="2" type="text">
                                    <div class="btn-group btn-group-vertical input-group-btn">
                                        <button type="button" class="btn btn-default spinner-up">
                                            <i class="fa fa-chevron-up text-muted"></i>
                                        </button>
                                        <button type="button" class="btn btn-default spinner-down">
                                            <i class="fa fa-chevron-down text-muted"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-6 m-b plnone">
                            <label class="col-sm-7 control-label">Время/Дата<br> последней продажи</label>
                            <div class="col-sm-5">
                                <p class="font-bold">14:15:18 15/05/2018</p>
                            </div>
                        </div>
                    </div>

                    <div class="row m-b">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Описание комплекта</label>
                            <div class="col-sm-10">
                                <div class="btn-toolbar m-b-sm btn-editor" id="editorButtons" data-role="editor-toolbar" data-target="#editor">
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font">
                                            <i class="fa fa-font"></i>
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu"> <li><a data-edit="fontName Serif" style="font-family:'Serif'">Serif</a></li><li><a data-edit="fontName Sans" style="font-family:'Sans'">Sans</a></li><li><a data-edit="fontName Arial" style="font-family:'Arial'">Arial</a></li><li><a data-edit="fontName Arial Black" style="font-family:'Arial Black'">Arial Black</a></li><li><a data-edit="fontName Courier" style="font-family:'Courier'">Courier</a></li><li><a data-edit="fontName Courier New" style="font-family:'Courier New'">Courier New</a></li><li><a data-edit="fontName Comic Sans MS" style="font-family:'Comic Sans MS'">Comic Sans MS</a></li><li><a data-edit="fontName Helvetica" style="font-family:'Helvetica'">Helvetica</a></li><li><a data-edit="fontName Impact" style="font-family:'Impact'">Impact</a></li><li><a data-edit="fontName Lucida Grande" style="font-family:'Lucida Grande'">Lucida Grande</a></li><li><a data-edit="fontName Lucida Sans" style="font-family:'Lucida Sans'">Lucida Sans</a></li><li><a data-edit="fontName Tahoma" style="font-family:'Tahoma'">Tahoma</a></li><li><a data-edit="fontName Times" style="font-family:'Times'">Times</a></li><li><a data-edit="fontName Times New Roman" style="font-family:'Times New Roman'">Times New Roman</a></li><li><a data-edit="fontName Verdana" style="font-family:'Verdana'">Verdana</a></li></ul>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font Size">
                                            <i class="fa fa-text-height"></i>&nbsp;
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a data-edit="fontSize 5">
                                                    <font size="5">Huge</font>
                                                </a>
                                            </li>
                                            <li>
                                                <a data-edit="fontSize 3">
                                                    <font size="3">Normal</font>
                                                </a>
                                            </li>
                                            <li>
                                                <a data-edit="fontSize 1">
                                                    <font size="1">Small</font>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm btn-info" data-edit="bold" title="" data-original-title="Bold (Ctrl/Cmd+B)">
                                            <i class="fa fa-bold"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="italic" title="" data-original-title="Italic (Ctrl/Cmd+I)">
                                            <i class="fa fa-italic"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="strikethrough" title="" data-original-title="Strikethrough">
                                            <i class="fa fa-strikethrough"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="underline" title="" data-original-title="Underline (Ctrl/Cmd+U)">
                                            <i class="fa fa-underline"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm" data-edit="insertunorderedlist" title="" data-original-title="Bullet list">
                                            <i class="fa fa-list-ul"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="insertorderedlist" title="" data-original-title="Number list">
                                            <i class="fa fa-list-ol"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="outdent" title="" data-original-title="Reduce indent (Shift+Tab)">
                                            <i class="fa fa-dedent"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="indent" title="" data-original-title="Indent (Tab)">
                                            <i class="fa fa-indent"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm btn-info" data-edit="justifyleft" title="" data-original-title="Align Left (Ctrl/Cmd+L)">
                                            <i class="fa fa-align-left"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="justifycenter" title="" data-original-title="Center (Ctrl/Cmd+E)">
                                            <i class="fa fa-align-center"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="justifyright" title="" data-original-title="Align Right (Ctrl/Cmd+R)">
                                            <i class="fa fa-align-right"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="justifyfull" title="" data-original-title="Justify (Ctrl/Cmd+J)">
                                            <i class="fa fa-align-justify"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Hyperlink">
                                            <i class="fa fa-link"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <div class="input-group m-l-xs m-r-xs">
                                                <input class="form-control input-sm" placeholder="URL" data-edit="createLink" type="text">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-default btn-sm" type="button">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-default btn-sm" data-edit="unlink" title="" data-original-title="Remove Hyperlink">
                                            <i class="fa fa-cut"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm" title="" id="pictureBtn" data-original-title="Insert picture (or just drag &amp; drop)">
                                            <i class="fa fa-picture-o"></i>
                                        </a>
                                        <input data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" style="opacity: 0; position: absolute; top: 0px; left: 0px; width: 0px; height: 0px;" type="file"> </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm" data-edit="undo" title="" data-original-title="Undo (Ctrl/Cmd+Z)">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="redo" title="" data-original-title="Redo (Ctrl/Cmd+Y)">
                                            <i class="fa fa-repeat"></i>
                                        </a>
                                    </div>
                                    <input class="form-control-trans pull-left" data-edit="inserttext" id="voiceBtn" x-webkit-speech="" style="width: 25px; height: 28px; display: none;" type="text"> </div>


                                <div id="editor" class="form-control" style="overflow:scroll;height:150px;max-height:150px" contenteditable="true">
                                    Описание товара
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m">
                        <label class="col-sm-12 m-t control-label font-bold">Состав комплекта</label>



                        <div class="table-responsive m-t">
                            <table class="table table-striped m-b-none">
                                <thead>
                                <tr>
                                    <th data-toggle="class">Название товара</th>
                                    <th data-toggle="class" width="80">Количество</th>
                                    <th width="30"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Wellness Life Watch</td>
                                    <td>2</td>
                                    <td>
                                        <i class="fa fa-times text-danger text"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Wellness Expert</td>
                                    <td>1</td>
                                    <td>
                                        <i class="fa fa-times text-danger text"></i>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <label class="col-sm-12 m-t control-label font-bold">Добавить товар</label>
                        <div id="showComplex" class=" form-group col-sm-12">
                            <div class="form-group">
                                <div class="col-sm-8 col-md-5 plnone">
                                    <div class="select2-container select2-container-active" id="s2id_select2-option" style="width:260px"><a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1">   <span class="select2-chosen">Wellness Watch</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow"><b></b></span></a><input class="select2-focusser select2-offscreen" id="s2id_autogen1" type="text"></div><select id="select2-option" style="width:260px" tabindex="-1" class="select2-offscreen">
                                        <optgroup label="Wellness">
                                            <option value="AK">Wellness Watch</option>
                                            <option value="HI">Wellness Expert</option>
                                        </optgroup>
                                        <optgroup label="VipVip">
                                            <option value="CA">1 месяц</option>
                                            <option value="NV">12 месяцев</option>
                                        </optgroup>
                                        <optgroup label="VipCoin">
                                            <option value="AZ">100 долей</option>
                                            <option value="CO">1200 долей</option>
                                            <option value="ID">36000 долей</option>
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="col-sm-2">
                                    <input class="form-control " value="0" maxlength="2" type="text">

                                </div>
                                <div class="col-sm-2">
                                    <a href="#" class="btn btn-dark btn-sm btn-icon addItemSet" id="addComplex">+</a>
                                </div>


                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 m-b plnone">

                            <label class="col-sm-7 control-label">Однократная покупка</label>
                            <div class="col-sm-5">
                                <label class="switch">
                                    <input id="loop" type="checkbox">
                                    <span></span>
                                </label>
                            </div>

                        </div>
                        <div class="form-group col-sm-6 m-b plnone">

                            <label class="col-sm-7 control-label">Балловая стоимость</label>
                            <div class="col-sm-5">
                                <input class="form-control" id="bPrice" placeholder="Балловая стоимость" value="150" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row m-b">

                        <div class="form-group col-sm-6 m-b plnone">

                            <label class="col-sm-7 control-label">Активный комплект</label>
                            <div class="col-sm-5">
                                <label class="switch">
                                    <input id="active" checked="" type="checkbox">
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group col-sm-6 m-b plnone">

                            <label class="col-sm-7 control-label">НДС (%)</label>
                            <div class="col-sm-5">
                                <input class="form-control" id="NDS" placeholder="НДС (%)" value="20" type="text">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-info" data-loading-text="Обновление...">Сохранить изменения</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    <script>
        $(function() {
            $('#difPremia').change(function() {

                $('#difShow').toggleClass('hidden')

            })
        })
        $(function() {
            $('#complex').change(function() {

                $('#showComplex').toggleClass('hidden')

            })
        })
        $(function() {
            $('span.removeItem').click(function() {

                $("#complexItems").parent().parent().parent().remove();

            })
        })
        $(document).on('click','.removeItem',function () {
            if (confirm('Вы уверены что хотете удалить позицию?')) {
                $(this).closest('.row').remove();
            }
        });

        $(function() {
            $('#addComplex').click(function() {

                $('#complexItems').append('<div class="row"><div class="m-b-sm col-md-6"><div class="input-group"><span class="input-group-addon input-sm removeItem"><i class="fa fa-trash-o"></i></span><input type="text" class="form-control input-sm" disabled="disabled" value="'+$('#select2-option option:selected').text()+'"><input type="hidden" name="setName[]" value="'+$('#select2-option option:selected').text()+'"><input type="hidden" name="setId[]" value="59620f57dca78747631d3c62"></div></div><div class="m-b-sm col-md-6"><input type="number" class="form-control" name="setPrice[]" value="0" min="0" step="0.01"></div></div>');
            })
        })
    </script>

</div>

<div id="select2-drop-mask" class="select2-drop-mask" style="display: none;"></div>

<div class="select2-drop select2-display-none select2-with-searchbox select2-drop-active" style="top: 695.817px; left: 615px; width: 260px; display: none;">   <div class="select2-search">       <input autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input select2-focused" type="text">   </div>   <ul class="select2-results"></ul></div>

<div class="modal" id="ajaxModal" style="display: none;" aria-hidden="true"><div class="modal-dialog">
        <div class="modal-content">
            <form role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Добавление товара</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Название</label>
                            <input class="form-control" placeholder="Введите название" value="" type="text">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Код товара</label>
                            <input class="form-control m-b" id="id" placeholder="Введите Код товара" value="" type="text">
                            <label>Розничная цена</label>
                            <input class="form-control m-b" id="price" placeholder="Введите розничную цену (Euro)" value="" type="text">
                            <label>Прямая премия</label>
                            <input class="form-control m-b" id="premia" placeholder="Прямая премия" value="" type="text">

                            <label class="col-sm-7 control-label m-b">Разные премии по статусам</label>
                            <div class="col-sm-5 m-b">
                                <label class="switch">
                                    <input id="difPremia" checked="" type="checkbox">
                                    <span></span>
                                </label>
                            </div>

                            <div id="difShow">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Новичок</label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-sm-6 control-label">Боец</label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label">Ветеран</label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" placeholder="Премия" type="text"> </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group col-md-6 text-center">

                            <label>Изображение</label><br>
                            <div class="row">
                                <img class="col-md-6 m100 fnone" src="images/pribor.png" alt="">
                            </div>

                            <input class="filestyle" data-icon="false" data-classbutton="btn btn-default" data-classinput="form-control inline input-s" id="filestyle-0" style="position: fixed; left: -500px;" type="file">
                            <div class="bootstrap-filestyle" style="display: inline;">
                                <label for="filestyle-0" class="btn btn-default"><span>Выберите изображение</span></label>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 m-b plnone">
                            <label class="col-sm-7 control-label">Продлевает активность BS (месяцев)</label>
                            <div class="col-sm-5">
                                <div id="MySpinner" class="spinner input-group" data-min="0" data-max="12">
                                    <input class="form-control spinner-input" value="0" name="spinner" maxlength="2" type="text">
                                    <div class="btn-group btn-group-vertical input-group-btn">
                                        <button type="button" class="btn btn-default spinner-up">
                                            <i class="fa fa-chevron-up text-muted"></i>
                                        </button>
                                        <button type="button" class="btn btn-default spinner-down">
                                            <i class="fa fa-chevron-down text-muted"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-b">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Описание товара</label>
                            <div class="col-sm-10">
                                <div class="btn-toolbar m-b-sm btn-editor" data-role="editor-toolbar" data-target="#editor">
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="Font">
                                            <i class="fa fa-font"></i>
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu"> </ul>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="Font Size">
                                            <i class="fa fa-text-height"></i>&nbsp;
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a data-edit="fontSize 5">
                                                    <font size="5">Huge</font>
                                                </a>
                                            </li>
                                            <li>
                                                <a data-edit="fontSize 3">
                                                    <font size="3">Normal</font>
                                                </a>
                                            </li>
                                            <li>
                                                <a data-edit="fontSize 1">
                                                    <font size="1">Small</font>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm" data-edit="bold" title="Bold (Ctrl/Cmd+B)">
                                            <i class="fa fa-bold"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="italic" title="Italic (Ctrl/Cmd+I)">
                                            <i class="fa fa-italic"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="strikethrough" title="Strikethrough">
                                            <i class="fa fa-strikethrough"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="underline" title="Underline (Ctrl/Cmd+U)">
                                            <i class="fa fa-underline"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm" data-edit="insertunorderedlist" title="Bullet list">
                                            <i class="fa fa-list-ul"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="insertorderedlist" title="Number list">
                                            <i class="fa fa-list-ol"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="outdent" title="Reduce indent (Shift+Tab)">
                                            <i class="fa fa-dedent"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="indent" title="Indent (Tab)">
                                            <i class="fa fa-indent"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)">
                                            <i class="fa fa-align-left"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)">
                                            <i class="fa fa-align-center"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)">
                                            <i class="fa fa-align-right"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)">
                                            <i class="fa fa-align-justify"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="Hyperlink">
                                            <i class="fa fa-link"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <div class="input-group m-l-xs m-r-xs">
                                                <input class="form-control input-sm" placeholder="URL" data-edit="createLink" type="text">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-default btn-sm" type="button">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-default btn-sm" data-edit="unlink" title="Remove Hyperlink">
                                            <i class="fa fa-cut"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm" title="Insert picture (or just drag &amp; drop)" id="pictureBtn">
                                            <i class="fa fa-picture-o"></i>
                                        </a>
                                        <input data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" type="file"> </div>
                                    <div class="btn-group">
                                        <a class="btn btn-default btn-sm" data-edit="undo" title="Undo (Ctrl/Cmd+Z)">
                                            <i class="fa fa-undo"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm" data-edit="redo" title="Redo (Ctrl/Cmd+Y)">
                                            <i class="fa fa-repeat"></i>
                                        </a>
                                    </div>
                                    <input class="form-control-trans pull-left" data-edit="inserttext" id="voiceBtn" x-webkit-speech="" style="width:25px;height:28px;" type="text"> </div>
                                <div id="editor" class="form-control" style="overflow:scroll;height:150px;max-height:150px">
                                    Описание товара</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 m-b plnone">

                            <label class="col-sm-7 control-label">Однократная покупка</label>
                            <div class="col-sm-5">
                                <label class="switch">
                                    <input id="loop" type="checkbox">
                                    <span></span>
                                </label>
                            </div>

                        </div>
                        <div class="form-group col-sm-6 m-b plnone">

                            <label class="col-sm-7 control-label">Балловая стоимость</label>
                            <div class="col-sm-5">
                                <input class="form-control" id="bPrice" placeholder="Балловая стоимость" value="" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row m-b">

                        <div class="form-group col-sm-6 m-b">

                            <div class="row form-group">
                                <label class="col-sm-7 control-label">Составной товар</label>
                                <div class="col-sm-5">
                                    <label class="switch">
                                        <input id="complex" type="checkbox">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row form-group">

                                <div id="showComplex" class="hidden form-group col-sm-12">
                                    <div class="form-group">
                                        <div class="m-b col-sm-10 plnone">
                                            <select id="select2-option" style="width:260px">
                                                <optgroup label="Wellness">
                                                    <option value="AK">Alaska</option>
                                                    <option value="HI">Hawaii</option>
                                                </optgroup>
                                                <optgroup label="VipVip">
                                                    <option value="CA">California</option>
                                                    <option value="NV">Nevada</option>
                                                    <option value="OR">Oregon</option>
                                                    <option value="WA">Washington</option>
                                                </optgroup>
                                                <optgroup label="VipCoin">
                                                    <option value="AZ">Arizona</option>
                                                    <option value="CO">Colorado</option>
                                                    <option value="ID">Idaho</option>
                                                    <option value="MT">Montana</option>
                                                    <option value="NE">Nebraska</option>
                                                </optgroup>
                                            </select>
                                        </div>

                                        <div class="m-b col-sm-2">
                                            <a href="#" class="btn btn-danger btn-rounded" id="addComplex">+</a>
                                        </div>
                                        <div class="row">
                                            <ul id="complexItems">

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="form-group col-sm-6 m-b plnone">

                            <label class="col-sm-7 control-label">Активный товар</label>
                            <div class="col-sm-5">
                                <label class="switch">
                                    <input id="active" checked="" type="checkbox">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-info" data-loading-text="Обновление...">Сохранить изменения</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog --></div>