<?php

namespace app\models\api;

use app\components\ApiClient;

class Order
{
    /**
     * @param $orderId
     * @return bool
     */
    public static function paymentSuccess($orderId)
    {
        $apiClient = new ApiClient('order/payment/success');

        $response = $apiClient->post([
            'orderId' => $orderId
        ], false);

        return $response == 'OK';
    }

}