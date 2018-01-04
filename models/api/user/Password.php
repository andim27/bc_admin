<?php

namespace app\models\api\user;

use app\components\ApiClient;

class Password
{
    /**
     * Check finance password
     *
     * @param $email
     * @param $password
     * @return bool
     */
    public static function checkFinancePassword($email, $password)
    {
        $apiClient = new ApiClient('user/password/checkFin/' . $email . '&' . $password);

        $response = $apiClient->get();

        return isset($response->id) ? true : false;
    }
}