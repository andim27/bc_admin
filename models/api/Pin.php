<?php

namespace app\models\api;

use app\components\ApiClient;

class Pin {

    /**
     * @param $idInMarket
     * @param int $qty
     * @return mixed
     * @internal param $userId
     */
    public static function createPinForProduct($idInMarket, $qty = 1)
    {
        $apiClient = new ApiClient('system/pin/' . $idInMarket . '&' . $qty);

        $response = $apiClient->get(false);

        return $response;
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

    /**
     * @param $pin
     * @return bool|mixed
     */
    public static function getPinInfo($pin)
    {
        $apiClient = new ApiClient('system/pinInfo/' . $pin);

        $response = $apiClient->get();

        return (!empty($response) ? $response : false);
    }
}