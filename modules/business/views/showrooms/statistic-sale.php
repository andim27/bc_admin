<?php
use yii\helpers\Html;
use app\components\AlertWidget;

$listStatusShowroom = \app\models\Sales::getStatusShowroom();

$alert = Yii::$app->session->getFlash('alert', '', true);

$totalPricePack = 0;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Статистика заказов</h3>
</div>

<?= (!empty($alert) ? AlertWidget::widget($alert) : '') ?>

<section class="panel panel-default">
    <div class="row">
        <div class="col-md-6">
            <?=Html::dropDownList('',$filter['showroomId'],$listShowroomsForSelect,[
                'class'     => 'filterInfoSelect form-control m',
                'prompt'    => 'Список активных шоурумов',
                'data'      => [
                    'filter'    => 'showroomId'
                ]
            ])?>
        </div>
        <div class="col-md-6">
            <div class="m">
                <span class="m-r">С</span>
                <input id="mainFrom" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['dateFrom']?>" data-date-format="yyyy-mm" data-filter="dateFrom"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months">
                <span class="m-r m-l">ПО</span>
                <input id="mainTo" class="input-s datepicker-input inline input-showroom form-control text-center filterInfoDate"
                       size="16" type="text" value="<?=$filter['dateTo']?>" data-date-format="yyyy-mm" data-filter="dateTo"
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="active mainLi"><a href="#products" data-toggle="tab">Товары</a></li>
                    <li class="historyLi"><a href="#packs" data-toggle="tab">Паки</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="products">
                        <table id="table-requests" class="table table-users table-striped datagrid m-b-sm">
                            <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Заказано</th>
                                <th>Выдано</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($infoSale['products'])){?>
                                <?php foreach ($infoSale['products'] as $k=>$item) {?>
                                    <tr>
                                        <td><?=$k?></td>
                                        <td><?=$item['orderCount']?></td>
                                        <td><?=$item['issueCount']?></td>
                                    </tr>
                                <?php } ?>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane" id="packs">
                        <table id="table-requests" class="table table-users table-striped datagrid m-b-sm">
                            <thead>
                            <tr>
                                <th>Номер пака</th>
                                <th>Паки</th>
                                <th>Заказано</th>
                                <th>Общая стоимость</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($infoSale['packs'])){?>
                                <?php foreach ($infoSale['packs'] as $k=>$item) {?>
                                    <?php $totalPricePack +=$item['totalPrice']; ?>
                                    <tr>
                                        <td><?=$item['productNumber']?></td>
                                        <td><?=$k?></td>
                                        <td><?=$item['orderCount']?></td>
                                        <td><?=$item['totalPrice']?></td>
                                    </tr>
                                <?php } ?>
                            <?php }?>
                            </tbody>
                            <tfooter>
                                <tr>
                                    <th colspan="3" class="text-right">Итого:</th>
                                    <th><?=$totalPricePack?></th>
                                </tr>
                            </tfooter>
                        </table>
                    </div>
                </div>
            </div>





        </div>
    </div>
</section>



<?php $this->registerCssFile('/js/datepicker/datepicker.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js', ['position' => yii\web\View::POS_END]); ?>

<script>

    $('.filterInfoSelect').on('change',function () {
        var link = window.location.href;

        link = updateQueryStringParameter(link,$(this).data('filter'),$(this).val());

        document.location.href = link;
    });

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

</script>