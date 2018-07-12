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
    .modal-head {
        color: #fff !important;
        background-color: #1b6d85;
        border-color: #00c7f7;
    }
    .categorySelected {
        color: #fff !important;
        /*background-color: #00b2de !important;*/
        background-color: #1b6d85;
        border-color: #1f2a34!important;
    }
    .nav > li > a:hover, .nav > li > a:focus {
        text-decoration: none;
        background-color: #1b6d85;
        color:white;
</style>
<div class="m-b-md">
    <h3 class="m-b-none"><?=THelper::t('goods') ?></h3>
<!--    --><?php //foreach ($goods as $item) { ?>
<!--    <p>-->
<!--        <pre style="width:100%;overflow: auto">-->
<!--        --><?//=json_encode($item); ?>
<!--        </pre>-->
<!--    </p>-->
<!--    --><?php //}?>
</div>
<section class="hbox stretch">
    <aside class="aside-md bg-white b-r" id="subNav">
        <div class="wrapper b-b header">
            Категории
            <button id="add-category-btn" type="button" class="btn btn-link" style="margin-top: 0px;"><i class="fa fa-plus"></i></button>
            <button id="edit-category-btn" type="button" class="btn btn-link" style="display:none;margin-top: 0px;margin-left:10px"><i class="fa fa-edit"></i></button>
<!--            <button type="button" class="btn btn-default btn-sm" id="createBtn"> <i class="fa fa-plus"></i></button>-->
        </div>

        <ul class="nav">
            <?php foreach ($cat_items as $item) { ?>

                <li id="cat-menu-<?=$item['rec_id'] ?>" class="b-b b-light  <?= ($item['id'] ==0)?'categorySelected':'' ?> " onclick="categorySelect(this,'<?=$item['rec_id'] ?>')">
                    <a href="#">
                        <i class="fa fa-chevron-right pull-right m-t-xs text-xs icon-muted"></i><?=$item['name'] ?></a>
                </li>
            <?php }?>

        </ul>
<!--        <pre>-->
<!--            --><?//=json_encode($cat_items); ?>
<!--        </pre>-->
    </aside>
    <aside>
        <section class="vbox">
            <header class="header bg-white b-b clearfix">
                <div class="row m-t-sm">
                    <div class="col-sm-8 m-b-xs">
                        <a href="#subNav" data-toggle="class:hide" class="btn btn-sm btn-default active">
                            <i class="fa fa-caret-right text fa-lg"></i>
                            <i class="fa fa-caret-left text-active fa-lg"></i>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-default" title="Refresh">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div>
<!--                        <a id="#createBtn" href="#" class="btn btn-sm btn-default">-->
<!--                            <i class="fa fa-plus"></i> Create</a>-->
                        <button type="button" class="btn btn-default btn-sm" id="createBtn"> <i class="fa fa-plus"></i>Create</button>
                    </div>
                    <div class="col-sm-4 m-b-xs">
<!--                        <div class="input-group">-->
<!--                            <input class="input-sm form-control" placeholder="Search" type="text">-->
<!--                            <span class="input-group-btn">-->
<!--                                                    <button class="btn btn-sm btn-default" type="button">Go!</button>-->
<!--                            </span>-->
<!--                        </div>-->
                    </div>
                </div>
            </header>
            <section class="scrollable wrapper w-f">
                <section class="panel panel-default">



                <!--                    Products table from BD-->
                 <div class="table-responsive">
                    <table id="goods-table" class="table table-striped m-b-none">
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
                            <th class="th-sortable" data-toggle="class">Название
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
                        <?php
                        $index=1;
                        foreach ($goods as $item) { ?>
                        <tr>
                            <td>
                                <span title="<?=$item['_id']; ?>">
                                    <?=$index++; ?>
                                </span>

                            </td>
                            <td>
                                <?php $p_id=$item['_id'];?>
                                <a href="#" onclick='showEditProduct("<?=$p_id; ?>")' data-toggle="modal" data-productid="<?=$item['_id'] ?>">
                                    <i class="fa fa-search-plus"></i>
                                </a>
                            </td>
                            <td><?=$item['productName'] ?></td>
                            <td><?=$item['price'] ?></td>
                            <td><?=$item['bonusMoney'] ?></td>
                            <td><?=$item['bonusPoints'] ?></td>
                            <td><?=$item['bonusStocks'] ?></td>
                            <td class="text-center">
                                <?=@gmdate('d.m.Y', $item['updated_at']) ?>

                            </td>
                            <td>
                                <?=$item['type'] ?>
                            </td>

                            <td>
                                <i class="fa fa-check text-success text"></i>
                            </td>
                            <?php } ?>
                        </tr>






                        </tbody>
                    </table>
                </div>
                </section>
            </section>
            <footer class="footer bg-white b-t">
                <div class="row text-center-xs">
<!--                    <div class="col-md-6 hidden-sm">-->
<!--                        <p class="text-muted m-t">Показано 1-6 из 6</p>-->
<!--                    </div>-->
<!--                    <div class="col-md-6 col-sm-12 text-right text-center-xs">-->
<!--                        <ul class="pagination pagination-sm m-t-sm m-b-none">-->
<!--                            <li>-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-chevron-left"></i>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="active">-->
<!--                                <a href="#">1</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="#">2</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="#">3</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="#">4</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="#">5</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="#">-->
<!--                                    <i class="fa fa-chevron-right"></i>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </div>-->
                </div>
            </footer>
        </section>
    </aside>
</section>


<div class="modal fade" id="editProductModal" style="display: none;" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-product">
        <div id="edit-product-content" class="modal-content">
<!--            <form role="form">-->
<!--                <div class="modal-header">-->
<!--                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
<!--                    <h4 class="modal-title">Редактирование товара: id=<span id="p-id"></span></h4>-->
<!---->
<!--                </div>-->
<!--                <div class="modal-body">-->
<!--                    <p class="m-b text-center font-bold">VipCoin активация бизнес-места на 12 месяцев (VipCoin activation of business cell for 12 month)</p>-->
<!--                    <div class="row">-->
<!--                        <div class="form-group col-md-12">-->
<!---->
<!--                            <label>Название</label>-->
<!--                            <div class="input-group">-->
<!--                                <input class="form-control" value="VipCoin активация бизнес-места на 12 месяцев" type="text">-->
<!--                                <div class="input-group-btn">-->
<!--                                    <button type="button" class="btn btn-default" tabindex="-1">RU</button>-->
<!--                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">-->
<!--                                        <span class="caret"></span>-->
<!--                                    </button>-->
<!--                                    <ul class="dropdown-menu pull-right">-->
<!--                                        <li>-->
<!--                                            <a href="#">RU</a>-->
<!--                                        </li>-->
<!--                                        <li>-->
<!--                                            <a href="#">EN</a>-->
<!--                                        </li>-->
<!--                                        <li>-->
<!--                                            <a href="#">TR</a>-->
<!--                                        </li>-->
<!--                                        <li>-->
<!--                                            <a href="#">ES</a>-->
<!--                                        </li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                                <!-- /btn-group -->-->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="row">-->
<!--                        <div class="form-group col-md-6">-->
<!--                            <label for="sel1">Категория товара:</label>-->
<!--                            <select class="form-control" id="cat-list">-->
<!--                                --><?php //foreach ($cat_items as $item) { ?>
<!--                                    --><?php //if ($item['rec_id'] !=0) { ?>
<!--                                        <option value="--><?//=$item['rec_id'] ?><!--">--><?//=$item['name'] ?><!--</option>-->
<!--                                    --><?php //} ?>
<!--                                --><?php //} ?>
<!---->
<!--                            </select>-->
<!--                            <label>Код товара</label>-->
<!--                            <input class="form-control m-b" id="id" placeholder="Введите Код товара" value="12" type="text">-->
<!---->
<!---->
<!--                            <label>ID товара в магазине</label>-->
<!--                            <input class="form-control m-b" id="inShop" placeholder="ID товара в магазине" value="77" type="text">-->
<!--                            <label>Розничная цена</label>-->
<!--                            <input class="form-control m-b" id="price" placeholder="Введите розничную цену (Euro)" value="150" type="text">-->
<!--                            <label>Прямая премия</label>-->
<!--                            <input class="form-control m-b" id="premia" placeholder="Прямая премия" value="150" type="text">-->
<!---->
<!---->
<!--                        </div>-->
<!--                        <div class="form-group col-md-6 text-center">-->
<!---->
<!--                            <label>Изображение</label><br>-->
<!--                            <div class="row">-->
<!--                                <img class="col-md-6 m100 fnone" src="images/pribor.png" alt="">-->
<!--                            </div>-->
<!---->
<!--                            <input class="filestyle hidden" data-icon="false" data-classbutton="btn btn-default" data-classinput="form-control inline input-s" id="filestyle-0" style="position: fixed; left: -500px;" type="file">-->
<!--                            <div class="bootstrap-filestyle" style="display: inline;">-->
<!--                                <label for="filestyle-0" class="btn btn-default"><span>Выберите изображение</span></label>-->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="row">-->
<!---->
<!--                        <label class="col-sm-7 control-label m-b">Разные премии по статусам</label>-->
<!--                        <div class="col-sm-5 m-b">-->
<!--                            <label class="switch">-->
<!--                                <input id="difPremia" type="checkbox">-->
<!--                                <span></span>-->
<!--                            </label>-->
<!--                        </div>-->
<!---->
<!--                        <div id="difShow" class="">-->
<!---->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Консультант</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Менеджер</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Региональный менеджер</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Топ-менеджер</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Региональный топ-менеджер</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Международный топ-менеджер</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Директор</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Региональный директор</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Международный директор</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Вицепрезидент</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Региональный вицепрезидент</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Международный вицепрезидент</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" placeholder="Премия" type="text"> </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!---->
<!--                    <div class="row">-->
<!--                        <div class="form-group col-sm-6 m-b plnone">-->
<!--                            <label class="col-sm-7 control-label">Продлевает активность BS (месяцев)</label>-->
<!--                            <div class="col-sm-5">-->
<!--                                <div id="MySpinner" class="spinner input-group" data-min="0" data-max="12">-->
<!--                                    <input class="form-control spinner-input" value="0" name="spinner" maxlength="2" type="text">-->
<!--                                    <div class="btn-group btn-group-vertical input-group-btn">-->
<!--                                        <button type="button" class="btn btn-default spinner-up">-->
<!--                                            <i class="fa fa-chevron-up text-muted"></i>-->
<!--                                        </button>-->
<!--                                        <button type="button" class="btn btn-default spinner-down">-->
<!--                                            <i class="fa fa-chevron-down text-muted"></i>-->
<!--                                        </button>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="form-group col-sm-6 m-b plnone">-->
<!--                            <label class="col-sm-7 control-label">Время/Дата<br> последней продажи</label>-->
<!--                            <div class="col-sm-5">-->
<!--                                <p class="font-bold">14:15:18 15/05/2018</p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="row m-b">-->
<!--                        <div class="form-group">-->
<!--                            <label class="col-sm-2 control-label">Описание товара</label>-->
<!--                            <div class="col-sm-10">-->
<!--                                <div class="btn-toolbar m-b-sm btn-editor" id="editorButtons" data-role="editor-toolbar" data-target="#editor">-->
<!--                                    <div class="btn-group">-->
<!--                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font">-->
<!--                                            <i class="fa fa-font"></i>-->
<!--                                            <b class="caret"></b>-->
<!--                                        </a>-->
<!--                                        <ul class="dropdown-menu"> <li><a data-edit="fontName Serif" style="font-family:'Serif'">Serif</a></li><li><a data-edit="fontName Sans" style="font-family:'Sans'">Sans</a></li><li><a data-edit="fontName Arial" style="font-family:'Arial'">Arial</a></li><li><a data-edit="fontName Arial Black" style="font-family:'Arial Black'">Arial Black</a></li><li><a data-edit="fontName Courier" style="font-family:'Courier'">Courier</a></li><li><a data-edit="fontName Courier New" style="font-family:'Courier New'">Courier New</a></li><li><a data-edit="fontName Comic Sans MS" style="font-family:'Comic Sans MS'">Comic Sans MS</a></li><li><a data-edit="fontName Helvetica" style="font-family:'Helvetica'">Helvetica</a></li><li><a data-edit="fontName Impact" style="font-family:'Impact'">Impact</a></li><li><a data-edit="fontName Lucida Grande" style="font-family:'Lucida Grande'">Lucida Grande</a></li><li><a data-edit="fontName Lucida Sans" style="font-family:'Lucida Sans'">Lucida Sans</a></li><li><a data-edit="fontName Tahoma" style="font-family:'Tahoma'">Tahoma</a></li><li><a data-edit="fontName Times" style="font-family:'Times'">Times</a></li><li><a data-edit="fontName Times New Roman" style="font-family:'Times New Roman'">Times New Roman</a></li><li><a data-edit="fontName Verdana" style="font-family:'Verdana'">Verdana</a></li></ul>-->
<!--                                    </div>-->
<!--                                    <div class="btn-group">-->
<!--                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Font Size">-->
<!--                                            <i class="fa fa-text-height"></i>&nbsp;-->
<!--                                            <b class="caret"></b>-->
<!--                                        </a>-->
<!--                                        <ul class="dropdown-menu">-->
<!--                                            <li>-->
<!--                                                <a data-edit="fontSize 5">-->
<!--                                                    <font size="5">Huge</font>-->
<!--                                                </a>-->
<!--                                            </li>-->
<!--                                            <li>-->
<!--                                                <a data-edit="fontSize 3">-->
<!--                                                    <font size="3">Normal</font>-->
<!--                                                </a>-->
<!--                                            </li>-->
<!--                                            <li>-->
<!--                                                <a data-edit="fontSize 1">-->
<!--                                                    <font size="1">Small</font>-->
<!--                                                </a>-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </div>-->
<!--                                    <div class="btn-group">-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="bold" title="" data-original-title="Bold (Ctrl/Cmd+B)">-->
<!--                                            <i class="fa fa-bold"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="italic" title="" data-original-title="Italic (Ctrl/Cmd+I)">-->
<!--                                            <i class="fa fa-italic"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="strikethrough" title="" data-original-title="Strikethrough">-->
<!--                                            <i class="fa fa-strikethrough"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="underline" title="" data-original-title="Underline (Ctrl/Cmd+U)">-->
<!--                                            <i class="fa fa-underline"></i>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="btn-group">-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="insertunorderedlist" title="" data-original-title="Bullet list">-->
<!--                                            <i class="fa fa-list-ul"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="insertorderedlist" title="" data-original-title="Number list">-->
<!--                                            <i class="fa fa-list-ol"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="outdent" title="" data-original-title="Reduce indent (Shift+Tab)">-->
<!--                                            <i class="fa fa-dedent"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="indent" title="" data-original-title="Indent (Tab)">-->
<!--                                            <i class="fa fa-indent"></i>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="btn-group">-->
<!--                                        <a class="btn btn-default btn-sm btn-info" data-edit="justifyleft" title="" data-original-title="Align Left (Ctrl/Cmd+L)">-->
<!--                                            <i class="fa fa-align-left"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="justifycenter" title="" data-original-title="Center (Ctrl/Cmd+E)">-->
<!--                                            <i class="fa fa-align-center"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="justifyright" title="" data-original-title="Align Right (Ctrl/Cmd+R)">-->
<!--                                            <i class="fa fa-align-right"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="justifyfull" title="" data-original-title="Justify (Ctrl/Cmd+J)">-->
<!--                                            <i class="fa fa-align-justify"></i>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="btn-group">-->
<!--                                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" title="" data-original-title="Hyperlink">-->
<!--                                            <i class="fa fa-link"></i>-->
<!--                                        </a>-->
<!--                                        <div class="dropdown-menu">-->
<!--                                            <div class="input-group m-l-xs m-r-xs">-->
<!--                                                <input class="form-control input-sm" placeholder="URL" data-edit="createLink" type="text">-->
<!--                                                <div class="input-group-btn">-->
<!--                                                    <button class="btn btn-default btn-sm" type="button">Add</button>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="unlink" title="" data-original-title="Remove Hyperlink">-->
<!--                                            <i class="fa fa-cut"></i>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="btn-group">-->
<!--                                        <a class="btn btn-default btn-sm" title="" id="pictureBtn" data-original-title="Insert picture (or just drag &amp; drop)">-->
<!--                                            <i class="fa fa-picture-o"></i>-->
<!--                                        </a>-->
<!--                                        <input data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" style="opacity: 0; position: absolute; top: 0px; left: 0px; width: 0px; height: 0px;" type="file"> </div>-->
<!--                                    <div class="btn-group">-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="undo" title="" data-original-title="Undo (Ctrl/Cmd+Z)">-->
<!--                                            <i class="fa fa-undo"></i>-->
<!--                                        </a>-->
<!--                                        <a class="btn btn-default btn-sm" data-edit="redo" title="" data-original-title="Redo (Ctrl/Cmd+Y)">-->
<!--                                            <i class="fa fa-repeat"></i>-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <input class="form-control-trans pull-left" data-edit="inserttext" id="voiceBtn" x-webkit-speech="" style="width: 25px; height: 28px; display: none;" type="text"> </div>-->
<!---->
<!---->
<!--                                <div id="editor" class="form-control" style="overflow:scroll;height:150px;max-height:150px" contenteditable="true">-->
<!--                                    Описание товара-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="row">-->
<!--                        <div class="form-group col-sm-6 m-b plnone">-->
<!---->
<!--                            <label class="col-sm-7 control-label">Однократная покупка</label>-->
<!--                            <div class="col-sm-5">-->
<!--                                <label class="switch">-->
<!--                                    <input id="loop" type="checkbox">-->
<!--                                    <span></span>-->
<!--                                </label>-->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!--                        <div class="form-group col-sm-6 m-b plnone">-->
<!---->
<!--                            <label class="col-sm-7 control-label">Балловая стоимость</label>-->
<!--                            <div class="col-sm-5">-->
<!--                                <input class="form-control" id="bPrice" placeholder="Балловая стоимость" value="150" type="text">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                    <div class="row m-b">-->
<!---->
<!---->
<!--                        <div class="form-group col-sm-6 m-b plnone">-->
<!---->
<!--                            <label class="col-sm-7 control-label">Активный товар</label>-->
<!--                            <div class="col-sm-5">-->
<!--                                <label class="switch">-->
<!--                                    <input id="active" checked="" type="checkbox">-->
<!--                                    <span></span>-->
<!--                                </label>-->
<!--                            </div>-->
<!--                        </div>-->
<!---->
<!--                        <div class="form-group col-sm-6 m-b plnone">-->
<!---->
<!--                            <label class="col-sm-7 control-label">НДС (%)</label>-->
<!--                            <div class="col-sm-5">-->
<!--                                <input class="form-control" id="NDS" placeholder="НДС (%)" value="20" type="text">-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                </div>-->
<!--                <div class="modal-footer">-->
<!--                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>-->
<!--                    <button type="submit" class="btn btn-info" data-loading-text="Обновление...">Сохранить изменения</button>-->
<!--                </div>-->
<!--            </form>-->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->


</div>

<!--<div class="modal" id="ajaxModal" style="display: none;" aria-hidden="true" >-->
<div class="modal" id="ajaxModal" style="display: none;" aria-hidden="true"  role="dialog">
    <div class="modal-dialog">
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

<!--B:Category modal-->
<div id="categoryModal" class="modal fade pos-ask-modal" role="dialog" data-action="add">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-head">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Категории</h4>
            </div>
            <div class="modal-body">
                <p><strong id="category-action-title"></strong></p>
                <div class="form-group">
                    <label for="text"><?= THelper::t('title_name') ?></label>
                    <input type="text" class="form-control" id="category-name" />
                    <input type="hidden"  id="category-id" >
                    <input type="hidden"  id="category-action">
                </div>
                <div class="text-center" id="server-message"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  onclick="saveCategory()"><?= THelper::t('save') ?></button>
                <button type="button" class="btn btn-default" onclick="$('#categoryModal').modal('hide')"><?= THelper::t('cancel') ?></button>
            </div>
        </div>

    </div>
</div>
<!--E:Category modal-->
<script>
    category_items=<?=json_encode($cat_items) ?>;
    cur_category_id=0;
    cur_product_id=0;
    cur_category_name='all';
    function categorySelect(elem,rec_id) {
        for (let i=0;i<category_items.length;i++) {
            var rec_id_str=category_items[i].rec_id;
            $('#cat-menu-'+rec_id_str).removeClass('categorySelected');
            if (category_items[i].rec_id == rec_id) {
                //$(elem).addClass('categorySelected');
                cur_category_id=rec_id;
                cur_category_name=category_items[i].name;
                //setGoodsByCategory(cur_category_id);
            }
        }
        if (rec_id != 0) {
            $('#edit-category-btn').show();
        } else {
            $('#edit-category-btn').hide();
        }

    }
    function setGoodsByCategory(i) {
        var table = $('#goods-table').DataTable();
        var filteredData = table
            .column( 2 )
            .data()
            .filter( function ( value, index ) {
                return value.substr(0,3) == 'Vip' ? true : false;
            } );
        table.rows.add(filteredData);
        console.log('filteredData ',filteredData);
    }
    function setProductLang(lang) {
        $('#product-lang').attr('cur-lang',lang).html(lang.toUpperCase())
        $('#product-desc-lang').html(lang.toUpperCase());
        getNameDescLang(lang);
    }
    function getNameDescLang(lang) {
        var url="/<?=Yii::$app->language?>/business/reference/name-desc-lang";
        $.post(url,{'product-lang':lang,'p_id':cur_product_id}).done(function (data) {
            if (data.success == true) {
                var name_lang=data.name_lang;
                var desc_lang=data.desc_lang;
                //if (name_lang != '') {
                    $('#product-name').val(name_lang);
                //}
                //if (desc_lang != '') {
                    $('#editor').html(desc_lang);
                //}
            }
        })
    }
    function saveCategory() {
        $('#server-message').removeClass('bg-danger');
        var url="/<?=Yii::$app->language?>/business/reference/category-change";
        var c_action=$('#category-action').val();
        var c_name  =$('#category-name').val();
        $.post(url,{'category-action':c_action,'category-name': c_name,'category-id':cur_category_id}).done(function(data) {
                        mes = data.message;
                        if (data.success == true) {
                            $('#server-message').removeClass('alert-danger').addClass('alert alert-success').html(mes);
                        } else {
                            $('#server-message').removeClass('alert-success').addClass('alert alert-danger').html(mes);
                        }
                        setTimeout(function () {
                            $('#categoryModal').modal('hide');
                            window.location.reload();
                        },2200)
                   });


    }
    function getProductEditContent() {
        var url="/<?=Yii::$app->language?>/business/reference/product-edit";
        $.post(url,{'product-action':'edit','p_id':cur_product_id}).done(function(data){
            //--add form content--
            $("#edit-product-content").html(data);
        });
    }
    function showEditProduct(p_id) {
        console.log(p_id);
        cur_product_id=p_id;
        $("#edit-product-content").html('');
        $("#p-id").html(p_id);
        $("#editProductModal").modal();
    }
    function saveProduct() {
        //alert('Save!');
        //$('#save-product-btn').hide();
        var url="/<?=Yii::$app->language?>/business/reference/product-edit";
        var product_data={
            'product-action':$('#product-action').val(),
            'p_id':cur_product_id,
            'product-lang':$('#product-lang').attr('cur-lang'),
            'product-name':$('#product-name').val(),
            'product-category':$('#product-category').val(),
            'product-type':$('#product-type').val(),
            'product-id':$('#product-id').val(),
            'product-idInMarket':$('#product-idInMarket').val(),
            'product-price':$('#product-price').val(),
            'product-premia-direct':$('#product-premia-direct').val(),
            'product-bonus-start':$('#product-bonus-start').val(),
            'product-bonus-standart':$('#product-bonus-standart').val(),
            'product-bonus-vip':$('#product-bonus-vip').val(),
            'product-bonus-investor':$('#product-bonus-investor').val(),
            'product-bonus-investor-2':$('#product-bonus-investor-2').val(),
            'product-bonus-investor-3':$('#product-bonus-investor-3').val(),
            'product-expirationPeriod-value':$('#product-expirationPeriod-value').val(),
            'product-single-purchase':$('#product-single-purchase').is(':checked')?1:0,
            'product-active':$('#product-active').is(':checked')?1:0,
            'product-tax-nds':$('#product-tax-nds').val(),
            'product-description':$('#editor').html(),
        };
        $.ajax( {
            url: url,
            type: 'POST',
            data: product_data,//new FormData( document.querySelector("form") ),
            success: function (data) {
                mes = data.message;
                if (data.success == true) {
                    $('#server-message').removeClass('alert-danger').addClass('alert alert-success').html(mes);
                } else {
                    $('#server-message').removeClass('alert-success').addClass('alert alert-danger').html(mes);
                }
                $('#save-product-btn').button('reset');
                //$('.progress').hide();
             }
        },
        );
        //e.preventDefault();
    }
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

    $(function() {

        $('#add-category-btn').click(function () {
            //$('#categoryModal').attr('data-action','add');
            $('#category-action-title').html('Add');
            $('#category-action').val('add');
            $('#category-name').html('');
            $('#server-message').removeClass('alert alert-danger alert-success').html('');
            $('#categoryModal').attr('data-action','add').show().modal();
        });
        $('#edit-category-btn').click(function () {
            //$('#categoryModal').attr('data-action','edit');
            $('#category-action-title').html('Edit');
            $('#category-action').val('edit');
            $('#category-name').val(cur_category_name);
            $('#server-message').removeClass('alert alert-danger alert-success').html('');
            $('#categoryModal').attr('data-action','edit').show().modal();
        });
        $('#categoryModal').on('show.bs.modal', function (e) {
            // do something...
            var $target = $(e.target);

            var dataValue = $target.data('action');

            console.log(dataValue);
        });
        $('#editProductModal').on('show.bs.modal', function (e) {
            // do something...
            var $target = $(e.target);
            getProductEditContent();
            //var dataValue = $target.data('productid');

            //console.log('productid='+dataValue);
        });

    })
    $(function() {
        $('#createBtn').click(function () {
            //alert('Create');
            $('#ajaxModal').show().modal();
        })
    })
    $(function() {
        $('#goods-table').DataTable({
            language: TRANSLATION,
            sDom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        });
    } );
</script>