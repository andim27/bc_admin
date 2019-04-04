<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
?>



<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => [],
    'columns' => [

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'dateCreate',
            'label' => 'Дата создания',
            'format' => 'raw',
            'value' => function ($model){
                return $model['dateCreate'];
            }
        ],

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'login',
            'label' => 'Оригинал',
            'format' => 'raw',
            'value' => function ($model){
                return $model['login'];
            },
            'filter' => Html::input('text','login',(!empty($request['login']) ? $request['login'] : ''),['class'=>'form-control'])
        ],

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'fullName',
            'label' => 'Полное имя',
            'format' => 'raw',
            'value' => function ($model){
                return $model['fullName'];
            }
        ],

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'phones',
            'label' => 'Телефоны',
            'format' => 'raw',
            'value' => function ($model){
                return $model['phones'];
            }
        ],

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'productName',
            'label' => 'Название продукта',
            'format' => 'raw',
            'value' => function ($model){
                return $model['productName'];
            },
            'filter' => Html::input('text','productName',(!empty($request['productName']) ? $request['productName'] : ''),['class'=>'form-control'])
        ],

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'productNumber',
            'label' => 'Кол',
            'format' => 'raw',
            'value' => function ($model){
                return $model['productNumber'];
            },
            'filter' => Html::input('integer','productNumber',(!empty($request['productNumber']) ? $request['productNumber'] : ''),['class'=>'form-control'])
        ],

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'status',
            'label' => 'Статус',
            'format' => 'raw',
            'value' => function ($model){
                return $model['status'];
            }
        ],

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'dateClose',
            'label' => 'Время доставки',
            'format' => 'raw',
            'value' => function ($model){
                return $model['dateClose'];
            }
        ],

        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'dateClose',
            'label' => 'Адресс доставки',
            'format' => 'raw',
            'value' => function ($model){
                return $model['dateClose'];
            }
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{editOrder}',
            'buttons' => [
                'editOrder' => function($url, $model, $key) {

                    return '<a class="editIssue" href="#issueInfo" data-id="'.$model['saleId'].'" data-toggle="modal">
                                <i class="fa fa-pencil"></i>
                            </a>';

                }
            ]
        ]
    ]

]); ?>
