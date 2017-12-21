<?php

namespace app\models\api;

use app\components\ApiClient;

class Pin {

    /**
     * @param $idInMarket
     * @param $userId
     * @return mixed
     */
    public static function create($idInMarket, $userId)
    {
        $apiClient = new ApiClient('system/createPin/' . $idInMarket . '&' . $userId);

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
     * @param $id
     * @return mixed
     */
    public static function pin($id)
    {
        $apiClient = new ApiClient('system/pin/' . $id);

        $response = $apiClient->get();

        return (!empty($response) ? $response : false);
    }
}