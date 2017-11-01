<?php

namespace app\models\api;

use app\components\ApiClient;

class Pin {

    /**
     * @param $idInMarket
     * @param $userId
     * @return mixed
     */
    public static function createPinForProduct($idInMarket)
    {
        $apiClient = new ApiClient('system/pin/' . $idInMarket . '&1');

        $response = $apiClient->get();

        return (isset($response->pin) && $response->pin) ? $response->pin : '';
    }

    /**
     * @param $pin
     * @return mixed
     */
    public static function checkPin($pin)
    {
        $apiClient = new ApiClient('system/checkPin/' . $pin);

        $response = $apiClient->get();

        return (!empty($response) ? $response : false);
    }
}