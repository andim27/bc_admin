<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\api\Order;
use Yii;
use yii\web\Response;
use MongoDB\BSON\ObjectID;

class ShopController extends BaseController {

    public function actionOrders()
    {
        return $this->render('orders', [
            'orders' => Order::getAll(),
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