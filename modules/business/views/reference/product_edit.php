<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
?>

<!--<form role="form">-->
<?php

?>
<?php
 $formProduct = ActiveForm::begin([
    'action' => '/' . Yii::$app->language . '/business/reference/product-edit',
    'options' => ['name' => 'formEditProduct', 'id'=>'formEditProduct', 'data-pjax' => '1','enctype' => 'multipart/form-data']
]); ?>
<form id="formEditProduct" enctype="multipart/form-data" method="post">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Редактирование товара: id=<span id="p-id"><?=$product->_id; ?></span></h4>
<!--<pre>-->
<!--    --><?//= var_dump($product) ?>
<!--</pre>-->
    </div>
    <div class="modal-body">
        <p class="m-b text-center font-bold"><?=$product->productName; ?></p>
        <div class="row">
            <div class="form-group col-md-12">

                <label>Название</label>
                <div class="input-group">
                    <input type="hidden" id="product-action" value="save">
                    <input id="product-name" class="form-control" value="<?=htmlspecialchars($product->productName); ?>" size=80 type="text">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default" id="product-name-save" >Save</button>
                        <button type="button" class="btn btn-default" id="product-lang" cur-lang="ru" tabindex="-1">RU</button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="#" onclick="setProductLang('ru')" >RU</a>
                            </li>
                            <li>
                                <a href="#" onclick="setProductLang('en')">EN</a>
                            </li>
                            <li>
                                <a href="#" onclick="setProductLang('tr')">TR</a>
                            </li>
                            <li>
                                <a href="#" onclick="setProductLang('es')">ES</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /btn-group -->
                </div>

            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                <label for="sel1">Категория товара:</label>
                <select class="form-control" id="product-category">
                    <?php foreach ($cat_items as $item) { ?>
                        <?php if ((!Empty($product->category_id))&&($item['rec_id'] == $product->category_id)) { ?>
                            <option selected value="<?=$item['rec_id'] ?>"><?=$item['name'] ?></option>
                        <?php } else { ?>
                            <option value="<?=$item['rec_id'] ?>"><?=$item['name'] ?></option>
                            <?php } ?>
                    <?php } ?>

                </select>
                <label for="sel1">Тип товара:</label>

                    <select class="form-control" id="product-type">
                        <?php foreach ($product_type_items as $item) { ?>
                            <?php if ((!Empty($product->productType))&&($item['id'] == $product->productType)) { ?>
                                <option selected value="<?=$item['id'] ?>"><?=$item['name'] ?></option>
                            <?php } else { ?>
                                <option value="<?=$item['id'] ?>"><?=$item['name'] ?></option>
                            <?php } ?>
                        <?php } ?>

                    </select>
                    <button id="add-category-btn" type="button" class="btn btn-link" style="display:none;float:right;margin-top: 0px;"><i class="fa fa-plus"></i></button>

                <label>Код товара</label>
                <input class="form-control m-b" id="product-id" onblur="checkProduct()"  placeholder="Введите Код товара" value="<?=$product->product ?>" type="text">


                <label>ID товара в магазине</label>
                <input class="form-control m-b" id="product-idInMarket" placeholder="ID товара в магазине" value="<?=$product->idInMarket ?>" type="text">
                <label>Розничная цена</label>
                <input class="form-control m-b" id="product-price" placeholder="Введите розничную цену (Euro)" value="<?=$product->price ?>" type="text">
                <label>Прямая премия</label>
                <input class="form-control m-b" id="product-premia-direct" placeholder="Прямая премия" value="<?=$product->bonusMoney ?>" type="text">


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
                    <input id="difPremia" type="checkbox" checked>
                    <span></span>
                </label>
            </div>

            <div id="difShow" class="">

                <div class="form-group col-sm-6">
                    <label class="col-sm-6 control-label">Начальный</label>
                    <div class="col-sm-6">
                        <input class="form-control m-b" id="product-bonus-start" placeholder="Премия" type="text" value="<?=empty($product['bonusMoneys']['elementary'])?0:$product['bonusMoneys']['elementary'];  ?>"> </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-6 control-label">Стандартный</label>
                    <div class="col-sm-6">
                        <input class="form-control m-b" id="product-bonus-standart" placeholder="Премия" type="text" value="<?=empty($product['bonusMoneys']['standart'])?0:$product['bonusMoneys']['standart'];  ?>"> </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-6 control-label">Vip</label>
                    <div class="col-sm-6">
                        <input class="form-control m-b" id="product-bonus-vip" placeholder="Премия" type="text" value="<?=empty($product['bonusMoneys']['vip'])?0:$product['bonusMoneys']['vip'];  ?>" > </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-6 control-label">VIP (Инвестор)</label>
                    <div class="col-sm-6">
                        <input class="form-control m-b" id="product-bonus-investor" placeholder="Премия" type="text" value="<?=empty($product['bonusMoneys']['investor'])?0:$product['bonusMoneys']['investor'];  ?>" > </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-6 control-label">VIP (Инвестор2)</label>
                    <div class="col-sm-6">
                        <input class="form-control m-b" id="product-bonus-investor-2" placeholder="Премия" type="text" value="<?=empty($product['bonusMoneys']['investor_2'])?0:$product['bonusMoneys']['investor_2'];  ?>" > </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="col-sm-6 control-label">VIP (Инвестор3)</label>
                    <div class="col-sm-6">
                        <input class="form-control m-b" id="product-bonus-investor-3" placeholder="Премия" type="text" value="<?=empty($product['bonusMoneys']['investor_3'])?0:$product['bonusMoneys']['investor_3'];  ?>" > </div>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="form-group col-sm-6 m-b plnone">
                <label class="col-sm-7 control-label">Продлевает активность BS (месяцев)</label>
                <div class="col-sm-5">
                    <div id="MySpinner" class="spinner input-group" data-min="0" data-max="12">
                        <input class="form-control spinner-input" id="product-expirationPeriod-value" value="<?=$product['expirationPeriod']['value'] ?>" name="spinner" maxlength="2" type="text">
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
                <label class="col-sm-2 control-label">Описание товара (<span id="product-desc-lang" >RU</span>)</label>
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
                            <a class="btn btn-default btn-sm" data-edit="bold" title="" data-original-title="Bold (Ctrl/Cmd+B)">
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
                        <?=@$product['productDescription']['ru']; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6 m-b plnone">

                <label class="col-sm-7 control-label">Однократная покупка</label>
                <div class="col-sm-5">
                    <label class="switch">
                        <input id="product-single-purchase" <?=empty($product->singlePurchase)?'':(($product->singlePurchase)==1)?'checked':''  ?> type="checkbox">
                        <span></span>
                    </label>
                </div>

            </div>
            <div class="form-group col-sm-6 m-b plnone">

                <label class="col-sm-7 control-label">Балловая стоимость</label>
                <div class="col-sm-5">
                    <input class="form-control" id="bPrice" placeholder="Балловая стоимость" value="<?=$product->bonusPoints ?>" type="text">
                </div>
            </div>
        </div>

        <div class="row m-b">


            <div class="form-group col-sm-6 m-b plnone">

                <label class="col-sm-7 control-label">Активный товар</label>
                <div class="col-sm-5">
                    <label class="switch">
                        <input id="product-active" <?=empty($product->productActive)?'':(($product->productActive)==1)?'checked':''  ?> type="checkbox">
                        <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group col-sm-6 m-b plnone">

                <label class="col-sm-7 control-label">НДС (%)</label>
                <div class="col-sm-5">
                    <input class="form-control" id="product-tax-nds" placeholder="НДС (%)" value="<?=$product->productTaxNds ?>" type="text">
                </div>
            </div>
        </div>
        <div class="text-center" id="server-message"></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-info" id="save-product-btn" onclick="saveProduct();" data-loading-text="Обновление...">Сохранить изменения</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
    </div>
<!--</form>-->
<?php
ActiveForm::end();
//Pjax::end();
?>
<script>
    $(function() {
        $('#difPremia').change(function() {

            $('#difShow').toggleClass('hidden')

        })
        $('#editor').wysiwyg({

        });
        //-------------------

    })
</script>