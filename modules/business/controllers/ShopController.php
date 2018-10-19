<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use Yii;
use yii\web\Response;
use MongoDB\BSON\ObjectID;

class ShopController extends BaseController {

    public function actionOrders()
    {
        $orders = \app\models\Order::find()->select([
            '_id',
            'orderId',
            'paymentStatus',
            'paymentType',
            'created_at',
            'total',
            'amount',
            'products.productName'
        ])->all();

        return $this->render('orders', [
            'orders' => $orders,
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