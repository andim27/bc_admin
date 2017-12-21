<?php

namespace app\models\api\settings;

use app\components\ApiClient;

class Menu {

    public static function items()
    {
        $apiClient = new ApiClient('settings/bcMainMenu');

        $response = $apiClient->get();

        return $response;
    }
}