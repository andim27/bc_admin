<?php
    use app\components\THelper;
    use yii\helpers\Html;
?>

<div class="m-b-md">
    <h3 class="m-b-none">Таблица компенсаций</h3>
</div>
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
                       data-date-viewMode="months" data-date-minViewMode="months" data-date-maxViewMode="months" >
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
                    <li><a href="/ru/business/showrooms/compensation-table-consolidated">Сводная</a></li>
                    <li><a href="/ru/business/showrooms/compensation-table-accruals">Начисления</a></li>
                    <li class="active"><a href="javascript:void(0);">Покупки</a></li>
                    <li><a href="/ru/business/showrooms/compensation-table-on-balance">Товар на балансе</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="purchases">
                        <!-- Покупки -->
                        <div class="table-responsive">
                            <table id="table-purchases" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Дата
                                        </th>
                                        <th>
                                            Логин
                                        </th>
                                        <th>
                                            ФИО
                                        </th>
                                        <th>
                                            Телефон
                                        </th>
                                        <th>
                                            Шоурум
                                        </th>
                                        <th>
                                            Название продукта
                                        </th>
                                        <th>
                                            Статус
                                        </th>
                                        <th>
                                            Начисление
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($salesShowroom)){ ?>
                                        <?php foreach ($salesShowroom as $itemSale) { ?>
                                            <tr>
                                                <td><?=$itemSale['dateCreate']?></td>
                                                <td><?=$itemSale['login']?></td>
                                                <td><?=$itemSale['secondName']?> <?=$itemSale['firstName']?></td>
                                                <td><?=$itemSale['phone1']?><br><?=$itemSale['phone2']?></td>
                                                <td><?=$itemSale['showroom']?></td>
                                                <td><?=$itemSale['productName']?></td>
                                                <td><?=$itemSale['status']?></td>
                                                <td>
                                                    <?=$itemSale['accrual']?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->registerCssFile('/js/datepicker/datepicker.css', ['position' => yii\web\View::POS_HEAD]); ?>
<?php $this->registerJsFile('/js/datepicker/bootstrap-datepicker.js', ['position' => yii\web\View::POS_END]); ?>

<script>

    $('#table-purchases').dataTable({
        language: TRANSLATION,
        lengthMenu: [ 25, 50, 75, 100 ],
        "order": [[ 0, "desc" ]]
    });

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
