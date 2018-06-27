<?php

namespace app\models\api\finance;

use app\components\ApiClient;

class Points {

    /**
     * @param $userId
     * @return mixed
     */
    public static function all($userId)
    {
        $apiClient = new ApiClient('transactions/points/' . $userId);

        return $apiClient->get();
    }

}