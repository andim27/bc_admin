<?php
    use app\components\THelper;
    use yii\helpers\Html;
    use app\models\api\Showrooms;
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
    </div>

    <div class="row">
        <div class="col-md-12">
            <header class="panel-heading bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li><a href="/ru/business/showrooms/compensation-table-consolidated">Сводная</a></li>
                    <li><a href="/ru/business/showrooms/compensation-table-accruals">Начисления</a></li>
                    <li><a href="/ru/business/showrooms/compensation-table-purchases">Покупки</a></li>
                    <li class="active"><a href="javascript:void(0);">Товар на балансе</a></li>
                </ul>
            </header>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="onBalance">
                        <!-- Товар на балансе -->
                        <div class="table-responsive">
                            <table id="table-onBalance" class="table table-users table-striped datagrid m-b-sm">
                                <thead>
                                    <tr>
                                        <th>
                                            Название товара
                                        </th>
                                        <th>
                                            Отправлено
                                        </th>
                                        <th>
                                            В наличие
                                        </th>
                                        <th>
                                            Стоимость
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($partsAccessories)){ ?>
                                        <?php foreach($partsAccessories as $item){ ?>
                                            <tr>
                                                <td><?=$item['title']?></td>
                                                <td><?=$item['numberDelivering']?></td>
                                                <td><?=$item['number']?></td>
                                                <td><?=$item['priceTotal']?></td>
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

<script>

    $('.filterInfoSelect').on('change',function () {
        var link = window.location.href;

        link = updateQueryStringParameter(link,$(this).data('filter'),$(this).val());

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
