<?php
use app\components\THelper;

$this->title = THelper::t('goods');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="hbox stretch">
    <aside id="subNav" class="aside-md bg-white b-r">
        <div class="wrapper b-b header">
            <?=THelper::t('grocery_list')?><!--Список продуктов-->
          <!--  <span class="pull-right m-t">
                <button class="btn btn-dark btn-sm btn-icon" id="new-product" data-toggle="tooltip" data-placement="right" title="Add product">
                    <i class="fa fa-plus"></i>
                </button>
            </span>-->
        </div>
        <ul class="nav">
            <?php
            foreach($models as $model) {
                echo '<li class="b-b b-light"><a data-id="'.$model->id.'" class="lang"><i class="fa fa-chevron-right pull-right m-t-xs text-xs icon-muted"></i>' . $model->title . '</a></li>';
            }
            ?>
            <!--<li class="b-b b-light"><a href="/email-list/create"><i class="fa fa-chevron-right pull-right m-t-xs text-xs icon-muted"></i>Создать новый шаблон</a></li>-->
        </ul>
    </aside>
    <aside class="card bg-white ">
        <div class="header b-b wrapper clearfix panel panel-default"><?=THelper::t('card_product')?><!--Катрочка товара--></div>
    </aside>
</section>
<?php $this->registerJsFile('js/main/product.js',['depends'=>['app\assets\AppAsset']]); ?>