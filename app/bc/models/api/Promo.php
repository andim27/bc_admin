<?php

namespace app\models\api;

use app\components\ApiClient;

class Promo {

    /**
     * @param $userId
     * @return mixed
     */
    public static function get($userId)
    {
        $apiClient = new ApiClient('promo/turkey/' . $userId);

        $response = $apiClient->get();

        return $response;
    }

    public static function all()
    {
        $apiClient = new ApiClient('promo/turkey');

        $response = $apiClient->get();

        return $response;
    }

}