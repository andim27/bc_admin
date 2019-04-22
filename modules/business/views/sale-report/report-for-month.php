<?php
use yii\grid\GridView;
use yii\widgets\LinkPager;
use yii\helpers\Html;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Обработка заказов</h3>
</div>


<section class="panel panel-default">
    <div class="row">
        <div class="col-md-9">
            <div class="m">
                <input id="mainDate" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['date']?>" data-date-format="yyyy-mm" data-filter="date"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months">
            </div>
        </div>
        <div class="col-md-3">
            <div class="m">
                <button class="btn btn-block btn-default makeReportExcel">
                    <i class="fa fa-file"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= GridView::widget([
                'options' => [
                    'class' => 'grid-view table-responsive'
                ],
                'dataProvider' => $dataProvider,
                'filterModel' => [],
                'columns' => [
                    [
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => 'dateCreate',
                        'enableSorting' => false,
                        'label' => 'Дата создания',
                        'format' => 'raw',
                        'value' => function ($model){
                            return $model['dateCreate'];
                        }
                    ],

                    [
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => 'orderId',
                        'enableSorting' => false,
                        'label' => '№ Заказа',
                        'format' => 'raw',
                        'value' => function ($model){
                            return $model['orderId'];
                        }
                    ],

                    [
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => 'login',
                        'enableSorting' => false,
                        'label' => 'Логин',
                        'format' => 'raw',
                        'value' => function ($model){
                            return $model['login'];
                        },
                        'filter' => Html::input('text','search[login]',(!empty($request['search']['login']) ? $request['search']['login'] : ''),['class'=>'form-control'])
                    ],

                    [
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => 'productNumber',
                        'enableSorting' => false,
                        'label' => 'Id Pack',
                        'format' => 'raw',
                        'value' => function ($model){
                            return $model['productId'];
                        }
                    ],

                    [
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => 'productName',
                        'enableSorting' => false,
                        'label' => 'Полное имя',
                        'format' => 'raw',
                        'value' => function ($model){
                            return $model['productName'];
                        },
                        'filter' => Html::input('text','search[productName]',(!empty($request['search']['productName']) ? $request['search']['productName'] : ''),['class'=>'form-control'])
                    ],

                    [
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => 'productNumber',
                        'enableSorting' => false,
                        'label' => 'Кол',
                        'format' => 'raw',
                        'value' => function ($model){
                            return $model['productNumber'];
                        }
                    ],

                    [
                        'class' => 'yii\grid\DataColumn',
                        'attribute' => 'price',
                        'enableSorting' => false,
                        'label' => 'Цена',
                        'format' => 'raw',
                        'value' => function ($model){
                            return $model['price'];
                        }
                    ]

                ]

            ]); ?>


            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
            ]);
            ?>
        </div>
    </div>
</section>


<?php $this->registerCssFile('/js/datepicker/datepicker.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js', ['position' => yii\web\View::POS_END]); ?>


<script>


    $('.filterInfoDate').datepicker().on('changeDate', function (e) {
        var link = window.location.href;
        var date = new Date(e.date);
        var newDate = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2);
        var newFilter = e.currentTarget.dataset.filter;

        $('.filterInfoDate').each(function () {
            link = updateQueryStringParameter(link,$(this).data('filter'),$(this).datepicker({ dateFormat: 'yy-mm' }).val());
        });

        link = updateQueryStringParameter(link,newFilter,newDate);

        document.location.href = link;

    });
    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|#|$)", "i");
        if( value === undefined ) {
            if (uri.match(re)) {
                return uri.replace(re, '$1$2');
            } else {
                return uri;
            }
        } else {
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                var hash =  '';
                if( uri.indexOf('#') !== -1 ){
                    hash = uri.replace(/.*#/, '#');
                    uri = uri.replace(/#.*/, '');
                }
                var separator = uri.indexOf('?') !== -1 ? "&" : "?";
                return uri + separator + key + "=" + value + hash;
            }
        }
    }

    $('.makeReportExcel').on('click',function () {

        document.location.href = "/ru/business/sale-report/report-excel-for-month?date="+$('#mainDate').datepicker({ dateFormat: 'yy-mm' }).val();

    });

</script>