<?php

namespace app\models\api\user;

use app\components\ApiClient;
use app\models\api;

class CareerHistory {

    public $username;
    public $careerRank;
    public $careerDate;

    public static function get($dateFrom, $dateTo)
    {
        $apiClient = new ApiClient('users/careerHistory/' . $dateFrom . '&' . $dateTo);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    private static function _getResults($data)
    {
        $result = [];

        if ($data) {
            if (!is_array($data)) {
                $data = [$data];
            }

            foreach ($data as $object) {
                $careerHistory = new self;

                $careerHistory->username = $object->username;
                $careerHistory->careerRank = $object->career->rank;

                $careerHistory->careerDate = strtotime($object->career->date);

                $result[] = $careerHistory;
            }
        }

        return $result;
    }

}