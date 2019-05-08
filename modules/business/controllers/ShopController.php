<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\Order;
use app\models\Users;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use MongoDB\BSON\ObjectID;

class ShopController extends BaseController {

    public function actionOrders()
    {
        $request = Yii::$app->request->get();

        $filter = [];
        $filter['dateFrom'] = (!empty($request['dateFrom']) ? $request['dateFrom'] : '2019-01');
        $filter['dateTo'] = (!empty($request['dateTo']) ? $request['dateTo'] : date('Y-m'));



        $orders = Order::find()
            ->select([
                '_id',
                'orderId',
                'userId',
                'userToId',
                'paymentStatus',
                'paymentType',
                'created_at',
                'total',
                'amount',
                'products'
            ])
            ->orderBy(['created_at'=>SORT_DESC]);


        if (!empty($request['search']['orderId'])) {
            $orders->andFilterWhere(['or',
                ['=', 'orderId', (int)$request['search']['orderId']]
            ]);
        }

        if (!empty($request['search']['total'])) {
            $orders->andFilterWhere(['or',
                ['=', 'total', (double)$request['search']['total']]
            ]);
        }

        if (!empty($request['search']['userLogin'])) {
            $searchUser = Users::find()
                ->select(['_id'])
                ->where(['username'=>$request['search']['userLogin']])
                ->one();

            if($searchUser){
                $orders->andFilterWhere(['or',
                    ['userId' => $searchUser->_id]
                ]);
            }
        }

        if (!empty($request['search']['userToLogin'])) {
            $searchUser = Users::find()
                ->select(['_id'])
                ->where(['username'=>$request['search']['userToLogin']])
                ->one();

            if($searchUser){
                $orders->andFilterWhere(['or',
                    ['userId' => $searchUser->_id]
                ]);
            }
        }


        $countQuery = clone $orders;
        $countQuery = $countQuery->count();

        $pageSize = 20;
        $pages = new Pagination(['totalCount' => $countQuery, 'pageSize' => $pageSize]);

        $orders = $orders
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $data = [];
        if(!empty($orders)){

            $listInfoUser = Users::find()
                ->select(['_id','username','firstName','secondName'])
                ->where([
                    '_id' => [
                        '$in' => ArrayHelper::merge(
                            ArrayHelper::getColumn($orders,'userId'),
                            ArrayHelper::getColumn($orders,'userToId')
                        )
                    ]

                ])
                ->all();
            $listInfoUser = ArrayHelper::index($listInfoUser, function ($item) { return strval($item['_id']);});

            /** @var \app\models\Order $order */
            foreach ($orders as $key => $order){

                $infoUser = $listInfoUser[strval($order->userId)];
                $infoUserTo = $listInfoUser[strval($order->userToId)];

                $dateCreate = $order->created_at->toDateTime()->format('Y-m-d');

                $data[] = [
                    'id'                =>  strval($order->_id),
                    'orderId'           =>  $order->orderId,
                    'dateCreate'        =>  $dateCreate,
                    'total'             =>  $order->total,
                    'paymentType'       =>  $order->paymentType,
                    'paymentStatus'     =>  $order->paymentStatus,
                    'products'          =>  $order->products,
                    'user' => [
                        'login' => $infoUser->username,
                        'firstName' => $infoUser->firstName,
                        'secondName' => $infoUser->secondName
                    ],
                    'userTo' => [
                        'login' => $infoUserTo->username,
                        'firstName' => $infoUserTo->firstName,
                        'secondName' => $infoUserTo->secondName
                    ],

                ];



            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'totalCount' => $countQuery,
            'pagination' => false,
            'sort' => [
                'attributes' => [

                ],
            ],
        ]);


        return $this->render('orders', [
            'dataProvider' => $dataProvider,
            'request' => $request,
            'pages' => $pages,
            'filter' => $filter
        ]);
    }

    public function actionPayOrder()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            if ($orderId = Yii::$app->request->post('order')) {
                if ($order = \app\models\Order::findOne(['_id' => new ObjectID($orderId)])) {
                    if (\app\models\api\Order::paymentSuccess($order->orderId)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
    }
}