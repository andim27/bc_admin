<?php
    use yii\grid\GridView;
    use yii\helpers\Html;
    use app\components\THelper;
?>
<div class="m-b-md">
    <h3 class="m-b-none"><?= THelper::t('shop_orders_title'); ?></h3>
</div>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="panel-body">
                <div class="tab-content">
                    <div class="table-responsive">
                        <?= GridView::widget([
                            'options' => [
                                'class' => 'grid-view table-responsive'
                            ],
                            'dataProvider' => $dataProvider,
                            'filterModel' => [],
                            'columns' => [
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'attribute' => 'orderId',
                                    'enableSorting' => false,
                                    'label' =>  THelper::t('shop_orders_order_id'),
                                    'format' => 'raw',
                                    'value' => function ($data){
                                        return $data['orderId'];
                                    },
                                    'filter' => Html::input('text','search[orderId]',(!empty($request['search']['orderId']) ? $request['search']['orderId'] : ''),['class'=>'form-control','style'=>'width: 60px;'])
                                ],

                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'attribute' => 'userLogin',
                                    'enableSorting' => false,
                                    'label' => THelper::t('shop_orders_user'),
                                    'format' => 'raw',
                                    'value' => function ($data){
                                        return
                                            $data['user']['login'].'<br/>' .
                                            $data['user']['firstName'] . ' ' . $data['user']['secondName'];
                                    },
                                    'filter' => Html::input('text','search[userLogin]',(!empty($request['search']['userLogin']) ? $request['search']['userLogin'] : ''),['class'=>'form-control'])
                                ],

                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'attribute' => 'userToLogin',
                                    'enableSorting' => false,
                                    'label' => THelper::t('shop_orders_user_to'),
                                    'format' => 'raw',
                                    'value' => function ($data){
                                        return
                                            $data['userTo']['login'].'<br/>' .
                                            $data['userTo']['firstName'] . ' ' . $data['userTo']['secondName'];
                                    },
                                    'filter' => Html::input('text','search[userToLogin]',(!empty($request['search']['userToLogin']) ? $request['search']['userToLogin'] : ''),['class'=>'form-control'])
                                ],

                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'attribute' => 'dateCreate',
                                    'enableSorting' => false,
                                    'label' => THelper::t('shop_orders_date'),
                                    'format' => 'raw',
                                    'value' => function ($data){
                                        return $data['dateCreate'];
                                    }
                                ],

                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'attribute' => 'productsName',
                                    'enableSorting' => false,
                                    'label' => THelper::t('shop_orders_products'),
                                    'format' => 'raw',
                                    'value' => function ($data){
                                        $listProduct = '<ul class="list-group">';
                                        foreach ($data['products'] as $product) {
                                            $listProduct .= '<li class="list-group-item">' . $product['productName'] . '</li>';
                                        }
                                        $listProduct .= '</ul>';

                                        return $listProduct;

                                    }
                                ],

                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'attribute' => 'total',
                                    'enableSorting' => false,
                                    'label' => THelper::t('shop_orders_total'),
                                    'format' => 'raw',
                                    'value' => function ($data){
                                        return $data['total'];
                                    },
                                    'filter' => Html::input('integer','search[total]',(!empty($request['search']['total']) ? $request['search']['total'] : ''),['class'=>'form-control'])
                                ],

                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'attribute' => 'paymentType',
                                    'enableSorting' => false,
                                    'label' => THelper::t('shop_orders_payment_type'),
                                    'format' => 'raw',
                                    'value' => function ($data){
                                        return THelper::t('shop_orders_payment_status_' . $data['paymentType']);
                                    }
                                ],

                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'attribute' => 'paymentStatus',
                                    'enableSorting' => false,
                                    'label' => THelper::t('shop_orders_payment_status'),
                                    'format' => 'raw',
                                    'value' => function ($data){
                                        $btn = '';
                                        if ($data['paymentStatus'] != 'paid') {
                                           $btn = '<a data-id="'.$data['id'].'" class="btn btn-success btn-sm set-payment-type">'. THelper::t('shop_orders_set_payment_type') .'</a>';
                                        }
                                        return $btn;
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
            </div>
        </section>
    </div>
</div>
<script>
    $('.set-payment-type').click(function () {
        var thisButton = $(this);
        thisButton.attr('disabled', 'disabled');
        var orderId = thisButton.data('id');
        $.ajax({
            url: '/' + LANG + '/business/shop/pay-order',
            method: 'POST',
            data: {
                order: orderId
            },
            success: function (data) {
                if (data) {
                    $('.shop_order_' + orderId).html('<?= THelper::t("shop_orders_payment_status_paid") ?>');
                    thisButton.hide();
                } else {
                    thisButton.removeAttr('disabled');
                }
            }
        });
    });
</script>