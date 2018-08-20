<?php
use app\components\THelper;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
?>

<!--<form role="form">-->
<?php

?>
<?php
 $formProduct = ActiveForm::begin([
    'action' => '/' . Yii::$app->language . '/business/reference/product-edit',
    'options' => ['name' => 'formEditProduct', 'id'=>'formEditProduct', 'data-pjax' => '1','enctype' => 'multipart/form-data']
]); ?>
<style>
    .active {
        background-color: lightcyan;
    }
    .bonus-row {
        padding-top: 15px;
    }
    .sub-field {
        background-color: lightcyan;padding-left: 5px;padding-right:5px;margin-right: 15px
    }
</style>
<form id="formEditProduct" enctype="multipart/form-data" method="post">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><?=($product_action=="edit")?THelper::t('product_edit'):THelper::t('product_add') ?>: id=<span id="p-id"><?=$product->_id; ?></span></h4>

    </div>
    <div class="modal-body">
        <p class="m-b text-center font-bold"><?=$product->productName; ?></p>
        <div class="row">
            <div class="form-group col-md-12">

                <label id='label-product-name' ><?= THelper::t('name_product') ?></label>
                <div class="input-group">
                    <input type="hidden" id="product-action" value="save">
                    <input id="product-name-main" class="form-control" style='display:none' value="<?=htmlspecialchars($product->productName); ?>" size=80 type="text">
                    <input id="product-name" class="form-control" value="<?=htmlspecialchars(empty($product['productNameLangs'][Yii::$app->language])?'???':$product['productNameLangs'][Yii::$app->language]); ?>" size=80 type="text">
                    <div class="input-group-btn">
                       <!-- <button type="button" class="btn btn-default" id="product-name-save" >Save</button>-->
                        <button type="button" class="btn btn-default" id="product-lang" cur-lang="ru" tabindex="-1"><?=strtoupper(Yii::$app->language); ?></button>
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
                <div class="checkbox" style="padding-left:0px">
                    <label><input id="product-natural"  type="checkbox" <?=(!(empty($product->productNatural))&&($product->productNatural ==1))?'checked':'' ?> value="0"><?= THelper::t('product_physical') ?></label>
                </div>
<!--                <label for="sel1">--><?//= THelper::t('product_category') ?><!--:</label>-->
<!--                <select class="form-control" id="product-category">-->
<!--                    --><?php //foreach ($cat_items as $item) { ?>
<!--                        --><?php //if ((!Empty($product->category_id))&&($item['rec_id'] == (string)$product->category_id)) { ?>
<!--                            <option selected value="--><?//=$item['rec_id'] ?><!--">--><?//=$item['name'] ?><!--</option>-->
<!--                        --><?php //} else { ?>
<!--                            <option value="--><?//=$item['rec_id'] ?><!--">--><?//=$item['name'] ?><!--</option>-->
<!--                            --><?php //} ?>
<!--                    --><?php //} ?>
<!---->
<!--                </select>-->
                <!--  ------- b:multy category -------->
                <div id="product-categories-wraper" style="width:100%">
                    <label for="product-multy-category"><?= THelper::t('product_categories') ?>:</label><br>
                    <select class="form-control"  id="product-categories" multiple="multiple" >
                        <?php foreach ($cat_items as $item) { ?>
                            <?php if ($item['name'] != '??') { ?>
                                <?php if (!empty($product['categories']))  {?>
                                     <option <?= (in_array($item['rec_id'], $product['categories']))?'selected':'' ?> value="<?=$item['rec_id'] ?>"><?=$item['name'] ?></option>
                                 <?php } else {?>
                                     <option  value="<?=$item['rec_id'] ?>"><?=$item['name'] ?></option>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>

                    </select><br>
                </div>
                <!--  ------- e:multy category -------->
                <label for="sel1"><?= THelper::t('product_type') ?>:</label>

                    <select class="form-control" id="product-type">
                        <?php foreach ($product_type_items as $item) { ?>
                            <?php if ((!Empty($product->productType))&&($item['id'] == $product->productType)) { ?>
                                <option selected value="<?=$item['id'] ?>"><?=$item['name'] ?></option>
                            <?php } else { ?>
                                <option value="<?=$item['id'] ?>"><?=$item['name'] ?></option>
                            <?php } ?>
                        <?php } ?>

                    </select>
                <div class="row" id="product-complect-block" <?=($product->productType <= 1)?'style="display:none"':'' ?> >
                    <div class="col-md-offset-1" style="background-color: lightcyan;padding-left: 5px;padding-right:5px;margin-right: 15px">
                        <table width="100%">
                            <th>N</th>
                            <th>Товар</th>
                            <th>Кол-во</th>
                            <th></th>
                            <tbody id="complect-items">
                            <?php foreach ($complect_items as $item) { ?>
                                <tr id="complect-item-<?=$item['rec_id'] ?>">
                                    <td><strong><?=$item['id'] ?>)</strong></td>
                                    <td><?=$item['name'] ?></td>
                                    <td><?=$item['cnt'] ?></td>
                                    <td> <a href="#" onclick="deleteComplectItem('<?=$item['rec_id'] ?>')">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <hr>
                        <div class="row">
                            <div class="col-md-7">
                                <select id="complect-new-name" class="form-control ">
                                    <?php foreach ($complect_goods_add_items as $item) { ?>
                                        <option value="<?=$item['id'] ?>"><?=$item['name'] ?></option>
                                    <?php } ?>

                                </select>
                            </div>
                            <div class="col-md-3">
                                <input id="complect-new-cnt"  class="form-control " value="1" maxlength="2" type="text">
                            </div>
                            <div class="col-md-2">
                                <a href="#" class="btn btn-dark btn-sm btn-icon addItemSet" onclick="addComplectItem($('#complect-new-name').val(),$('#complect-new-name option:selected').text(),$('#complect-new-cnt').val())">+</a>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <label id="product-code-title"><?= THelper::t('product_code') ?></label>
                <input class="form-control m-b" id="product-id" onchange="checkProduct()"  placeholder="Введите Код товара" value="<?=$product->product ?>" type="text">


                <label><?= THelper::t('product_id_in_shop') ?></label>
                <input class="form-control m-b" id="product-idInMarket" placeholder="ID товара в магазине" value="<?=$product->idInMarket ?>" type="text">
                <label><?= THelper::t('shop_product_price') ?>
                    <a href="#" onclick="showHistory('price');">
                        <span class="glyphicon glyphicon-time"></span>
                    </a>
                </label>
                <input class="form-control m-b" id="product-price" placeholder="Введите розничную цену (Euro)" value="<?=$product->price ?>" type="text">
                <div id="product-history-add-price" class="col-md-offset-1 sub-field" style="display:none">
                    <span class="center-block  text-center text-info"><?= THelper::t('add_on_date') ?>:</span>
                    <table class="table" width="100%">
                        <tr>
                            <td width="33%">
                                <input id="history-add-price" class="form-control" size=7 type="text" />
                            </td>
                            <td width="33%">
                                <input id="history-add-date" class="form-control" style="width:140px" type="date" />
                            </td>
                            <td>
                                <a  onclick="saveAddHistory('price');" title="Сохранить">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </a>
                                <a  onclick="clearAddHistory('price');" title="Закрыть" style="float:right;margin-left: 8px">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="product-history-price" class="col-md-offset-1 sub-field"  style="display:none">

                </div>

            </div>
            <div class="form-group col-md-6 text-center">

                <label><?= THelper::t('product_image') ?></label>
                <button id="edit-category-btn" type="button" class="btn btn-link" onclick="$('#product-image-choose').toggle();$('#product-image-base').toggle()" style="display:<?=($product_action=="edit")?'inline':'none' ?>;margin-top: 0px;margin-left:10px"><i class="fa fa-edit"></i></button><br>
                <div id="product-image-base" class="row" style="margin-top: 25px">
                    <?php
                    if (($product_action=="edit")) {
                        echo "<img class='col-md-6 center m100'  src='".$product->productImage."' width=200 height=200 />";
                    }
                    ?>


                </div>

                <div id="product-image-choose"  class="bootstrap-filestyle" style="display:<?=($product_action=="edit")?'none':'inline' ?>;margin-top: 25px">
                    <?php

                    try {
                        echo FileInput::widget([
                            'model' => $upload_product_form,
                            'attribute' => 'imageFile',
                            'id'=>'input-id',
                            'options' => ['multiple' => true],
                            'pluginOptions' => [
                                'uploadUrl' => 'product-image-upload',
                            ],

                        ]);
                    } catch (\Exception $e) {
                        echo '<span class="alert alert-danger">Изображение недоступно</span>';
                    }


                    ?>
                </div>

            </div>
        </div>
        <div class="row">

            <label class="col-sm-7 control-label m-b"><?= THelper::t('bonuses') ?></label>
            <div class="col-sm-12">

                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-money"><?= THelper::t('money') ?></a></li>
                    <li><a data-toggle="tab" href="#tab-points"><?= THelper::t('points') ?></a></li>
<!--                    <li><a data-toggle="tab" href="#tab-stock">Акции</a></li>-->
                    <li><a data-toggle="tab" href="#tab-stock-category"><?= THelper::t('stock_direction') ?></a></li>
                </ul>

                <div class="tab-content">
                    <div id="tab-money" class="tab-pane fade in active">
                        <!--               ---------- MONEY -------    -->
                        <div class="row bonus-row" >
                                    <div class="form-group col-sm-6" style="margin-right: 100%">
                                        <label class="col-sm-6 control-label"><?= THelper::t('client') ?></label>
                                        <div class="col-sm-6">
                                            <input class="form-control m-b" id="product-bonus-client" placeholder="Премия" type="text" value="<?=empty($product['bonus']['money']['client'])?0:$product['bonus']['money']['client'];  ?>"> </div>
                                    </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-6 control-label"><?= THelper::t('pack_type_1') ?></label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" id="product-bonus-start" placeholder="Премия" type="text" value="<?=empty($product['bonus']['money']['beginner'])?0:$product['bonus']['money']['beginner'];  ?>"> </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-6 control-label"><?= THelper::t('standart') ?></label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" id="product-bonus-standard" placeholder="Премия" type="text" value="<?=empty($product['bonus']['money']['standard'])?0:$product['bonus']['money']['standard'];  ?>"> </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-6 control-label">Vip</label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" id="product-bonus-vip" placeholder="Премия" type="text" value="<?=empty($product['bonus']['money']['vip'])?0:$product['bonus']['money']['vip'];  ?>" > </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-6 control-label">VIP (<?= THelper::t('investor') ?>-1)</label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" id="product-bonus-investor" placeholder="Премия" type="text" value="<?=empty($product['bonus']['money']['vip_investor_1'])?0:$product['bonus']['money']['vip_investor_1'];  ?>" > </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-6 control-label">VIP (<?= THelper::t('investor') ?>-2)</label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" id="product-bonus-investor-2" placeholder="Премия" type="text" value="<?=empty($product['bonus']['money']['vip_investor_2'])?0:$product['bonus']['money']['vip_investor_2'];  ?>" > </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="col-sm-6 control-label">VIP (<?= THelper::t('investor') ?>-3)</label>
                                    <div class="col-sm-6">
                                        <input class="form-control m-b" id="product-bonus-investor-3" placeholder="Премия" type="text" value="<?=empty($product['bonus']['money']['vip_investor_3'])?0:$product['bonus']['money']['vip_investor_3'];  ?>" > </div>
                                </div>
                        </div>
                    </div>
                    <div id="tab-points" class="tab-pane fade">
                        <!--     -----------------  POINTS----------- -->
                        <div class="row bonus-row" >
                            <div class="form-group col-sm-6" style="margin-right: 100%">
                                <label class="col-sm-6 control-label"><?= THelper::t('client') ?></label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-point-client" placeholder="Премия" type="text" value="<?=empty($product['bonus']['point']['client'])?0:$product['bonus']['point']['client'];  ?>"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label"><?= THelper::t('pack_type_1') ?></label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-point-start" placeholder="Премия" type="text" value="<?=empty($product['bonus']['point']['beginner'])?0:$product['bonus']['point']['beginner'];  ?>"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label"><?= THelper::t('standart') ?></label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-point-standard" placeholder="Премия" type="text" value="<?=empty($product['bonus']['point']['standard'])?0:$product['bonus']['point']['standard'];  ?>"> </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">Vip</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-point-vip" placeholder="Премия" type="text" value="<?=empty($product['bonus']['point']['vip'])?0:$product['bonus']['point']['vip'];  ?>" > </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">VIP (<?= THelper::t('investor') ?>-1)</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-point-investor" placeholder="Премия" type="text" value="<?=empty($product['bonus']['point']['vip_investor_1'])?0:$product['bonus']['point']['vip_investor_1'];  ?>" > </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">VIP (<?= THelper::t('investor') ?>-2)</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-point-investor-2" placeholder="Премия" type="text" value="<?=empty($product['bonus']['point']['vip_investor_2'])?0:$product['bonus']['point']['vip_investor_2'];  ?>" > </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="col-sm-6 control-label">VIP (<?= THelper::t('investor') ?>-3)</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-point-investor-3" placeholder="Премия" type="text" value="<?=empty($product['bonus']['point']['vip_investor_3'])?0:$product['bonus']['point']['vip_investor_3'];  ?>" > </div>
                            </div>
                        </div>
                    </div>
                    <!--     -----------------  STOCKS-------------- -->
<!--                    <div id="tab-stock" class="tab-pane fade">-->
<!--                        <div class="row bonus-row" >-->
<!--                            <div class="form-group col-sm-6" style="margin-right: 100%">-->
<!--                                <label class="col-sm-6 control-label">Клиент</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" id="product-bonus-stock-client" placeholder="Премия" type="text" value="--><?//=empty($product['bonus']['stock']['client'])?0:$product['bonus']['stock']['client'];  ?><!--"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Начальный</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" id="product-bonus-stock-start" placeholder="Премия" type="text" value="--><?//=empty($product['bonus']['stock']['beginner'])?0:$product['bonus']['stock']['beginner'];  ?><!--"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Стандартный</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" id="product-bonus-stock-standard" placeholder="Премия" type="text" value="--><?//=empty($product['bonus']['stock']['standard'])?0:$product['bonus']['stock']['standard'];  ?><!--"> </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">Vip</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" id="product-bonus-stock-vip" placeholder="Премия" type="text" value="--><?//=empty($product['bonus']['stock']['vip'])?0:$product['bonus']['stock']['vip'];  ?><!--" > </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">VIP (Инвестор-1)</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" id="product-bonus-stock-investor" placeholder="Премия" type="text" value="--><?//=empty($product['bonus']['stock']['vip_investor_1'])?0:$product['bonus']['stock']['vip_investor_1'];  ?><!--" > </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">VIP (Инвестор-2)</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" id="product-bonus-stock-investor-2" placeholder="Премия" type="text" value="--><?//=empty($product['bonus']['stock']['vip_investor_2'])?0:$product['bonus']['stock']['vip_investor_2'];  ?><!--" > </div>-->
<!--                            </div>-->
<!--                            <div class="form-group col-sm-6">-->
<!--                                <label class="col-sm-6 control-label">VIP (Инвестор-3)</label>-->
<!--                                <div class="col-sm-6">-->
<!--                                    <input class="form-control m-b" id="product-bonus-stock-investor-3" placeholder="Премия" type="text" value="--><?//=empty($product['bonus']['stock']['vip_investor_3'])?0:$product['bonus']['stock']['vip_investor_3'];  ?><!--" > </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div id="tab-stock-category" class="tab-pane fade">
                        <div class="row bonus-row" >
                            <div class="form-group col-sm-6" style="margin-right: 100%">
                                <label class="col-sm-6 control-label">VipCoin</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-stock-vipcoin" placeholder="Акции" type="text" value="<?=empty($product['bonus']['stock']['vipcoin'])?0:$product['bonus']['stock']['vipcoin'];  ?>"> </div>
                            </div>
                            <div class="form-group col-sm-6"style="margin-right: 100%">
                                <label class="col-sm-6 control-label">VipVip</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-stock-vipvip" placeholder="Акции" type="text" value="<?=empty($product['bonus']['stock']['vipvip'])?0:$product['bonus']['stock']['vipvip'];  ?>"> </div>
                            </div>
                            <div class="form-group col-sm-6" style="margin-right: 100%">
                                <label class="col-sm-6 control-label">Wellness</label>
                                <div class="col-sm-6">
                                    <input class="form-control m-b" id="product-bonus-stock-wellness" placeholder="Акции" type="text" value="<?=empty($product['bonus']['stock']['wellness'])?0:$product['bonus']['stock']['wellness'];  ?>"> </div>
                            </div>

                        </div>
                    </div>



            </div>
            <hr>
        </div>


            <div id="difShow" class="">



            </div>
        </div>


        <div class="row">
            <div class="form-group col-sm-6 m-b plnone">
                <label class="col-sm-7 control-label"><?= THelper::t('prolong_act_month') ?></label>
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
<!--                <label class="col-sm-7 control-label">Время/Дата<br> последней продажи</label>-->
<!--                <div class="col-sm-5">-->
<!--                    <p class="font-bold">14:15:18 15/05/2018</p>-->
<!--                </div>-->
            </div>
        </div>

        <div class="row m-b">
            <div class="form-group">
                <label class="col-sm-2 control-label"><?= THelper::t('product_description') ?> (<span id="product-desc-lang" ><?=strtoupper(Yii::$app->language);?></span>)</label>
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
<!--                            <input data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" style="opacity: 0; position: absolute; top: 0px; left: 0px; width: 0px; height: 0px;" type="file"> </div>-->
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
                        <?=@(empty($product['productDescription'][Yii::$app->language])?"???":$product['productDescription'][Yii::$app->language]); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6 m-b plnone">

                <label class="col-sm-7 control-label"><?= THelper::t('buy_once') ?></label>
                <div class="col-sm-5">
                    <label class="switch">
                        <input id="product-single-purchase" <?=empty($product->singlePurchase)?'':(($product->singlePurchase)==1)?'checked':''  ?> type="checkbox">
                        <span></span>
                    </label>
                </div>

            </div>
            <div class="form-group col-sm-6 m-b plnone">

                <label class="col-sm-7 control-label"><?= THelper::t('bonus_points') ?></label>
                <div class="col-sm-5">
                    <input class="form-control" id="product-bonus-points" placeholder="Балловая стоимость" value="<?=$product->bonusPoints ?>" type="text">
                </div>
            </div>
        </div>

        <div class="row m-b">


            <div class="form-group col-sm-6 m-b plnone">

                <label class="col-sm-7 control-label"><?= THelper::t('product_active') ?></label>
                <div class="col-sm-5">
                    <label class="switch">
                        <input id="product-active" <?=empty($product->productActive)?'':(($product->productActive)==1)?'checked':''  ?> type="checkbox">
                        <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group col-sm-6 m-b plnone">
<!--                <label class="col-sm-7 control-label">Акции</label>-->
<!--                <div class="col-sm-5">-->
<!--                     It was stock here-->

<!--                </div>-->

                    <label class="col-sm-7 control-label"  ><?= THelper::t('nds') ?> (%)
                        <a href="#" style="float:right"  onclick="showHistory('productTax');">
                            <span class="glyphicon glyphicon-time"></span>
                        </a>
                    </label>
                    <div class="col-sm-5"  >
                        <input class="form-control" id="product-tax-nds" placeholder="НДС (%)" value="<?=$product->productTax ?>" type="text">
                    </div>
                    <div id="product-history-productTax" class="col sub-field"  style="display:none">

                    </div>
            </div>
          </div>
        <!--     ------------------------ Выплаты -----------------------------     -->
        <div class="row m-b">
            <div class="form-group col-sm-6 m-b plnone">
                <label class="col-sm-7 control-label"><?= THelper::t('payments_to_representative') ?></label>
                <div class="col-sm-5"  >
                    <input class="form-control" id="product-payments-rep" placeholder="Сумма" value="<?=empty($product->paymentsToRepresentive)?0:$product->paymentsToRepresentive ?>" type="text">
                </div>
            </div>
            <div class="form-group col-sm-6 m-b plnone">
                <label class="col-sm-7 control-label"><?= THelper::t('payments_to_stock') ?></label>
                <div class="col-sm-5"  >
                    <input class="form-control" id="product-payments-stock" placeholder="Сумма" value="<?=empty($product->paymentsToStock)?0:$product->paymentsToStock; ?>" type="text">
                </div>
            </div>
        </div>


    <div class="row m-b">
        <div class="form-group col-sm-6 m-b plnone">
            <label class="col-sm-7 control-label"> <?= THelper::t('replenishment_com_account') ?></label>
            <div class="col-sm-5">
                <label class="switch">
                    <input id="product-balance-top-up" <?=empty($product->productBalanceTopUp)?'':(($product->productBalanceTopUp)==1)?'checked':''  ?> type="checkbox">
                    <span></span>
                </label>
            </div>
        </div>
        <div id="product-balance-money-block" class="form-group col-sm-6 m-b plnone " style="display:<?=Empty($product->balanceMoney)?'none':'block' ?>">
            <label class="col-sm-7 control-label"><?= THelper::t('replenishment_sum') ?></label>
            <div class="col-sm-5"  >
                <input class="form-control" id="product-balance-money" placeholder="Сумма" value="<?=$product->balanceMoney ?>" type="text">
            </div>
        </div>

    </div>
    <div class="row m-b">
        <div class="form-group col-sm-6 m-b plnone">
            <label class="col-sm-7 control-label"> <?= THelper::t('replenishment_wellness_account') ?></label>
            <div class="col-sm-5">
                <label class="switch">
                    <input id="product-balance-wellness-top-up" <?=empty($product->productBalanceWellnessTopUp)?'':(($product->productBalanceWellnessTopUp)==1)?'checked':''  ?> type="checkbox">
                    <span></span>
                </label>
            </div>
        </div>
        <div id="product-balance-wellness-money-block" class="form-group col-sm-6 m-b plnone " style="display:<?=Empty($product->balanceWellnessMoney)||($product->productBalanceWellnessTopUp ==0)?'none':'block' ?>">
            <label class="col-sm-7 control-label"><?= THelper::t('replenishment_wellness_sum') ?></label>
            <div class="col-sm-5"  >
                <input class="form-control" id="product-balance-wellness-money" placeholder="Сумма" value="<?=$product->balanceWellnessMoney ?>" type="text">
            </div>
        </div>

    </div>
    <div class="row m-b">
        <div class="form-group col-sm-6 mb plone">
            <label class="col-sm-7 control-label"><?= THelper::t('buy_after_end') ?></label>
            <div class="col-sm-5">
                <label class="switch">
                    <input id="product-buy-after-end" <?=empty($product->buyAfterEnd)?'':(($product->buyAfterEnd)==1)?'checked':''  ?> type="checkbox">
                    <span></span>
                </label>
            </div>
        </div>
    </div>
        <div class="text-center" id="server-message"></div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-info" id="save-product-btn" onclick="saveProduct();" data-loading-text="Обновление..."><?= THelper::t('save_change') ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= THelper::t('close') ?></button>
    </div>
<!--</form>-->
<?php
ActiveForm::end();
//Pjax::end();
?>
<script>
    complect_items=<?=json_encode($complect_items) ?>;
    complect_goods_add_items=<?=json_encode($complect_goods_add_items) ?>;
    $(function() {
        $('#product-categories').multiselect();
        $('#product-type').change(function() {
            if ($('#product-type').val()==2) {
                $('#product-complect-block').show();
            } else {
                $('#product-complect-block').hide();
            }
        });
        $('#difPremia').change(function() {

            $('#difShow').toggleClass('hidden')

        });
        $('#editor').wysiwyg({

        });
        $('#product-balance-top-up').change(function () {
            $('#product-balance-money-block').toggle();
        });
        $('#product-balance-wellness-top-up').change(function () {
            $('#product-balance-wellness-money-block').toggle();
        });
        //----------------------------------------------------------------------------------
        $('input[type="file"]').on('fileuploaded', function(event, data, previewId, index) {
            var form = data.form, files = data.files, extra = data.extra,
                response = data.response, reader = data.reader;
            filename=response.filename;
            cur_product_image_file=filename;

            console.log('File uploaded triggered:filename',filename);
        });

    })
    function showHistory(field_name) {
        var h_block='product-history-'+field_name;
        if ($('#'+h_block).css('display') =='none' ) {


            var url="/<?=Yii::$app->language?>/business/reference/product-show-history";
            $.post(url,{'field_name':field_name,'p_id':cur_product_id}).done(function (data) {
                if (data.success == true) {
                    $('#product-history-'+field_name).html(data.history_html).show();
                } else {
                    console.log('Error:get history field '+field_name+':'+data.mes);
                }

            })
        } else {
            $('#'+h_block).hide();
            $('#product-history-add-price').hide();
        }
    }
    $('#label-product-name').click(function(e) {
        if (e.ctrlKey) {
            $('#product-name-main').toggle();
        }
    });
    function addHistory(field_name) {
        var h_block='product-history-add-price';
        if ($('#'+h_block).css('display') =='none') {
            $('#'+h_block).show();
        } else {
            $('#'+h_block).hide();
        }
    }
    function saveAddHistory(field_name) {
        var url="/<?=Yii::$app->language?>/business/reference/product-add-history";
        var price=$('#history-add-price').val();
        var date =$('#history-add-date').val();
        $.post(url,{'field_name':field_name,'p_id':cur_product_id,'price':price,'date':date}).done(function (data) {
                if (data.success == true) {
                    $('#product-history-price').hide();
                    showHistory('price');
                } else {
                    alert('Add history Error!');
                }
        });
    }
    function clearAddHistory(field_name) {
        var h_block='product-history-add-'+field_name;
        $('#history-add-price').val(0);
        $('#history-add-date').val('');
        $('#'+h_block).hide();
    }
</script>