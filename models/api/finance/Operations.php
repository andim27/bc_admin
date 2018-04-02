<?php

namespace app\models\api\finance;

use app\components\ApiClient;

class Operations {

    /**
     * @param $userId
     * @return mixed
     */
    public static function all($userId)
    {
        $apiClient = new ApiClient('transactions/money/' . $userId);

        return $apiClient->get();
    }

    /**
     * @param $transactionId
     * @return mixed
     */
    public static function cancel($transactionId)
    {
        $apiClient = new ApiClient('transactions/money/');

        return $apiClient->delete([
            'id' => $transactionId
        ], false);
    }

}