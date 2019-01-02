<?php

namespace app\models\api;

use app\components\ApiClient;

class Pin {

    /**
     * @param $idInMarket
     * @param int $qty
     * @param null $userId
     * @return string
     */
    public static function createPinForProduct($idInMarket, $qty = 1, $userId = null)
    {
        $url = 'system/pin/' . $idInMarket . '&' . $qty;

        if ($userId) {
            $url .= '/' . $userId;
        }

        $apiClient = new ApiClient($url);

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