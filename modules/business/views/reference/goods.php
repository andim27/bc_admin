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
                            <button type="button" class="btn btn-sm btn-default" id="refresh-btn" title="Refresh">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div>

                        <button type="button" class="btn btn-default btn-sm" id="createBtn" > <i class="fa fa-plus"></i>Create</button>
                    </div>
                    <div class="col-sm-4 m-b-xs">

                    </div>
                </div>
            </header>
            <section class="scrollable wrapper w-f">
                <section class="panel panel-default">



                <!--                    Products table from BD-->
                 <div class="table-responsive">
                     <div class="checkbox">
                         <label> <input type="checkbox" id="goods-active"  <?=($active_ch ==0)?'':'checked' ?> /> Активные товары</label>
                     </div>
                    <table id="goods-table" class="table table-striped m-b-none">
                        <thead>
                        <tr>
                            <th class="th-sortable" data-toggle="class" width="60">Код
                                <span class="th-sort">
<!--                                                                <i class="fa fa-sort-down text"></i>-->
<!--                                                                <i class="fa fa-sort-up text-active"></i>-->
<!--                                                                <i class="fa fa-sort"></i>-->
                                    </span>

                            </th>
                            <th width="20"></th>
                            <th class="th-sortable" data-toggle="class">Название
                                <span class="th-sort">
<!--                                                                <i class="fa fa-sort-down text"></i>-->
<!--                                                                <i class="fa fa-sort-up text-active"></i>-->
<!--                                                                <i class="fa fa-sort"></i>-->
                                    </span>
                            </th>
                            <th class="th-sortable" data-toggle="class">Категория
                                <span class="th-sort">
<!--                                                                <i class="fa fa-sort-down text"></i>-->
<!--                                                                <i class="fa fa-sort-up text-active"></i>-->
<!--                                                                <i class="fa fa-sort"></i>-->
                                    </span>
                            </th>
                            <th class="th-sortable" data-toggle="class" width="80">Цена
                                <span class="th-sort">
<!--                                                                <i class="fa fa-sort-down text"></i>-->
<!--                                                                <i class="fa fa-sort-up text-active"></i>-->
<!--                                                                <i class="fa fa-sort"></i>-->
                                    </span>
                            </th>
                            <th class="th-sortable text-center" data-toggle="class" width="80">Бонус<br>(начальный)
                                <span class="th-sort">
<!--                                                                <i class="fa fa-sort-down text"></i>-->
<!--                                                                <i class="fa fa-sort-up text-active"></i>-->
<!--                                                                <i class="fa fa-sort"></i>-->
                                    </span>
                            </th>
                            <th class="text-center">Баллы<br>(начальный)</th>
                            <th class="text-center">Акции<br>(направления)</th>
                            <th class="text-center">НДС<br>(%)</th>
                            <th class="text-center">Акт<br>(Мес)</th>
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
                                <span title="<?='Код='.$item['product'].' ID магазина='.$item['idInMarket']; ?>">
                                    <?=Empty($item['product'])?'??':$item['product']; ?>
                                </span>

                            </td>
                            <td>
                                <?php $p_id=$item['_id'];?>
                                <a href="#" onclick='showEditProduct("<?=$p_id; ?>")' data-toggle="modal" data-productid="<?=$item['_id'] ?>">
                                    <i class="fa fa-search-plus"></i>
                                </a>
                            </td>

                            <td>

                                <?php
                                if (empty($item['productName'])) {
                                    echo "??";
                                } else {
                                    if (empty($item['products']) ) {
                                        echo $item['productName'];
                                    } else {
                                        echo "<strong>".$item['productName']."</strong>";
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (empty($item['category_id'])) {
                                    echo '??';
                                } else {
                                foreach ($cat_items as $cat_item) {
                                        if ($cat_item['rec_id'] ==(string)$item['category_id']) {
                                            echo $cat_item['name'];
                                            break;
                                        }
                                }} ?>

                            </td>
                            <td><?=empty($item['price'])?0:$item['price'] ?></td>
                            <td class="text-center"><?=empty($item['bonus']['money']['beginner'])?0:$item['bonus']['money']['beginner']  ?></td>
                            <td class="text-center"><?=empty($item['bonus']['point']['beginner'])?0:$item['bonus']['point']['beginner'] ?></td>
                            <td class="text-center">
                                <?php
                                    $stock_str='';
                                    if (!empty($item['bonus']['stock']['vipcoin'])) {
                                        $stock_str.='<span title="VipCoin">vc'.$item['bonus']['stock']['vipcoin'].'</span>';
                                    }
                                    if (!empty($item['bonus']['stock']['vipvip'])) {
                                        $stock_str.=' <span title="VipVip">vv'.$item['bonus']['stock']['vipvip'].'</span>';
                                    }
                                    if (!empty($item['bonus']['stock']['wellness'])) {
                                        $stock_str.=' <span title="WellNess">ww'.$item['bonus']['stock']['wellness'].'</span>';
                                    }
                                    echo $stock_str;

                                ?>

                            </td>
                            <td class="text-center">
                                <?=empty($item['productTax'])?0:$item['productTax'] ?>


                            </td>
                            <td class="text-center">
                                <?=empty($item['expirationPeriod']['value'])?0:$item['expirationPeriod']['value'] ?>

                            </td>
                            <td>
                                <?=empty($item['type'])?'??':$item['type'] ?>
                            </td>

                            <td>
                                <?php if (!empty($item['productActive'])) { ?>
                                    <i class="fa fa-check text-success text"></i>
                                <?php } ?>
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

                </div>
            </footer>
        </section>
    </aside>
</section>


<div class="modal fade" id="editProductModal" style="display: none;" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-product">
        <div id="edit-product-content" class="modal-content">

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->


</div>

<!--<div class="modal" id="ajaxModal" style="display: none;" aria-hidden="true" >-->


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
    goods=<?=json_encode($goods) ?>;
    cur_product_action='edit';
    cur_category_id=0;
    cur_product_id=0;
    cur_category_name='all';
    cur_product_image_file='';
    function categorySelect(elem,rec_id) {
        for (let i=0;i<category_items.length;i++) {
            var rec_id_str=category_items[i].rec_id;
            $('#cat-menu-'+rec_id_str).removeClass('categorySelected');
            if (category_items[i].rec_id == rec_id) {
                //$(elem).addClass('categorySelected');
                cur_category_id=rec_id;
                cur_category_name=category_items[i].name;
                setGoodsByCategory(cur_category_name);
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
        if (cur_category_id ==0) {
            //table.fnFilterClear();
            table.column(3).search('').draw();
        } else {
            table.column(3).search(cur_category_name).draw();
        }

    }
    function setProductLang(lang) {
        $('#product-lang').attr('cur-lang',lang).html(lang.toUpperCase())
        $('#product-desc-lang').html(lang.toUpperCase());
        getNameDescLang(lang);
    }
    function addComplectItem(rec_id,item_name,item_cnt=0) {
        var item_number=complect_items.length+1;
        var del_item_html="<a href=\"#\" onclick=\"deleteComplectItem('"+rec_id+"')\">\n" + "<span class=\"glyphicon glyphicon-remove\"></span>\n" + "</a>";
        var good_title=item_name;
        var item_html="<tr id='complect-item-"+rec_id+"'><td><strong>"+item_number+")</strong></td><td title="+good_title+">"+item_name+"</td><td>"+item_cnt+"</td><td>"+del_item_html+"</td></tr>";
        $('#complect-items').append(item_html);
        complect_items.push({'id':item_number,'rec_id':rec_id,'name':item_name,'cnt':item_cnt});
    }
    function deleteComplectItem(rec_id) {
        for (i=0;i<complect_items.length;i++) {
            if (complect_items[i].rec_id == rec_id) {
                //delete complect_items[i];
                complect_items.splice(i,1);
                console.log('Deleted! '+rec_id);
                break;
            }
        }
        $("#complect-item-"+rec_id).remove();

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
    function checkProduct() {
        var url="/<?=Yii::$app->language?>/business/reference/product-check";
        $.post(url,{'product-id':$('#product-id').val()}).done(function(data){
            if (data.success==false) {
                //alert('Product with code:'+$('#product-id').val()+' exist!');
                $('#product-code-title').addClass('alert alert-danger').html('Код товара: '+$('#product-id').val()+' существут!<strong>Осторожно!</strong>');

                $('#product-id').focus();
            } else {
                $('#product-code-title').removeClass('alert alert-danger').html('Код товара');
            }
        });
    }
    function getProductAddContent() {
        var url="/<?=Yii::$app->language?>/business/reference/product-edit";
        $.post(url,{'product-action':'add','cur-product-action':'add'}).done(function(data){
            //--add form content--
            $("#edit-product-content").html(data);
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
        cur_product_action='edit';
        cur_product_id=p_id;
        $("#edit-product-content").html('');
        $("#p-id").html(p_id);
        $("#editProductModal").modal();
    }
    function showAddProduct() {
        console.log('add product to cat_id:',cur_category_id);
        cur_product_id='';
        cur_product_action='add';
        $("#edit-product-content").html('');
        $("#editProductModal").modal();
    }
    function saveProduct() {
        //alert('Save!');
        //$('#save-product-btn').hide();
        var url="/<?=Yii::$app->language?>/business/reference/product-edit";
        var product_data={
            'product-action':$('#product-action').val(),
            'cur-product-action':cur_product_action,
            'p_id':cur_product_id,
            'product-lang':$('#product-lang').attr('cur-lang'),
            'product-name':$('#product-name').val(),
            'product-natural':$('#product-natural').is(':checked')?1:0,
            'product-category':$('#product-category').val(),
            'product-type':$('#product-type').val(),
            'product-id':$('#product-id').val(),
            'product-idInMarket':$('#product-idInMarket').val(),
            'product-price':$('#product-price').val(),
            'product-image-file':cur_product_image_file,
            //------------tab-money----------------------------------------
            'product-bonus-client':$('#product-bonus-client').val(),
            'product-bonus-start':$('#product-bonus-start').val(),
            'product-bonus-standard':$('#product-bonus-standard').val(),
            'product-bonus-vip':$('#product-bonus-vip').val(),
            'product-bonus-investor':$('#product-bonus-investor').val(),
            'product-bonus-investor-2':$('#product-bonus-investor-2').val(),
            'product-bonus-investor-3':$('#product-bonus-investor-3').val(),
            //-----------tab-points-----------------------------------------
            'product-bonus-point-client':$('#product-bonus-point-client').val(),
            'product-bonus-point-start':$('#product-bonus-point-start').val(),
            'product-bonus-point-standard':$('#product-bonus-point-standard').val(),
            'product-bonus-point-vip':$('#product-bonus-point-vip').val(),
            'product-bonus-point-investor':$('#product-bonus-point-investor').val(),
            'product-bonus-point-investor-2':$('#product-bonus-point-investor-2').val(),
            'product-bonus-point-investor-3':$('#product-bonus-point-investor-3').val(),
            //------------tab-stock----------------------------------------
            'product-bonus-stock-vipcoin':$('#product-bonus-stock-vipcoin').val(),
            'product-bonus-stock-vipvip':$('#product-bonus-stock-vipvip').val(),
            'product-bonus-stock-wellness':$('#product-bonus-stock-wellness').val(),

            // 'product-bonus-stock-client':$('#product-bonus-stock-client').val(),
            // 'product-bonus-stock-start':$('#product-bonus-stock-start').val(),
            // 'product-bonus-stock-standard':$('#product-bonus-stock-standard').val(),
            // 'product-bonus-stock-vip':$('#product-bonus-stock-vip').val(),
            // 'product-bonus-stock-investor':$('#product-bonus-stock-investor').val(),
            // 'product-bonus-stock-investor-2':$('#product-bonus-stock-investor-2').val(),
            // 'product-bonus-stock-investor-3':$('#product-bonus-stock-investor-3').val(),
            //------------end tabs----------------------------------------------
            'product-expirationPeriod-value':$('#product-expirationPeriod-value').val(),
            'product-single-purchase':$('#product-single-purchase').is(':checked')?1:0,
            'product-active':$('#product-active').is(':checked')?1:0,
            'product-bonus-points':$('#product-bonus-points').val(),
            'product-tax-nds':$('#product-tax-nds').val(),
            //'product-stock':$('#product-stock').val(),
            'product-description':$('#editor').html(),
            'product-complect-goods':complect_items,
            //------------com balance--------------------------------------------
            'product-balance-top-up':$('#product-balance-top-up').is(':checked')?1:0,
            'product-balance-money' :$('#product-balance-money').val(),
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
        function showGoodsPage() {
            var active_str='active=1';
            var url="/<?=Yii::$app->language?>/business/reference/goods";
            if ( $('#goods-active').is(':checked')==true) {
                active_str='?active=1';
            } else {
                active_str='?active=0';
            }
            console.log(active_str);
            window.location.href=url+active_str;
        }
        $('#refresh-btn').click(function () {
            showGoodsPage();
        })
        $('#goods-active').click(function(el){
            showGoodsPage();
        });
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
            if (cur_product_action == 'edit') {
                getProductEditContent();
            }
            if (cur_product_action == 'add') {
                getProductAddContent();
            }
            //var dataValue = $target.data('productid');

            //console.log('productid='+dataValue);
        });
        $('#editProductModal').on('hide.bs.modal', function (e) {
            showGoodsPage();
        });

    })
    $(function() {

    })

    $(function() {
        $('#createBtn').click(function () {
            showAddProduct();
        })
    })

    $(function() {
        $('#goods-table').DataTable({
            'order':[[0,"desc"]],
            "pageLength": 20,
            language: TRANSLATION,
            sDom: "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        });
    } );
</script>