<?php

namespace app\models\api;

use app\components\ApiClient;

class Notification {

    /**
     * @param $pushId
     * @return bool
     */
    public static function addPushToQueue($pushId)
    {
        $apiClient = new ApiClient('notification/queue/push/add');

        $response = $apiClient->post([
            'pushId' => $pushId
        ], false);

        return $response == 'OK';
    }

    /**
     * @param $pushId
     * @return bool
     */
    public static function deletePush($pushId)
    {
        $apiClient = new ApiClient('notification/push/delete');

        $response = $apiClient->delete([
            'pushId' => $pushId
        ], false);

        return $response == 'OK';
    }

    /**
     * @param $pushId
     * @return bool
     */
    public static function deletePushFromQueue($pushId)
    {
        $apiClient = new ApiClient('notification/queue/push/delete');

        $response = $apiClient->delete([
            'pushId' => $pushId
        ], false);

        return $response == 'OK';
    }

    /**
     * @return mixed
     */
    public static function getPushes()
    {
        $apiClient = new ApiClient('notification/push/get');

        $response = $apiClient->get();

        return $response;
    }

}