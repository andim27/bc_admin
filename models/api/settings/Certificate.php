<?php

namespace app\models\api\settings;

use app\components\ApiClient;

class Certificate {

    public static function get()
    {
        $apiClient = new ApiClient('settings/certificate');

        $response = $apiClient->get(false);

        return $response;
    }
}