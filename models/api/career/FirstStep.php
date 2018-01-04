<?php

namespace app\models\api\career;

use app\components\ApiClient;

class FirstStep {

    public $accountId;
    public $username;
    public $side;
    public $rank;
    public $bs;
    public $steps;
    public $email;

    /**
     * Returns all first steps
     *
     * @param $data
     * @return bool|mixed
     */
    public static function all($data)
    {
        $apiClient = new ApiClient('career/firstLine/' . $data);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return bool|mixed
     */
    private static function _getResults($data)
    {
        $result = [];

        if ($data) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $firstStep = new self;

                $firstStep->accountId = $object->accountId;
                $firstStep->username  = $object->username;
                $firstStep->side      = $object->side;
                $firstStep->rank      = $object->rank;
                $firstStep->bs        = $object->bs;
                $firstStep->steps     = $object->steps;
                $firstStep->email     = $object->email;

                $result[] = $firstStep;
            }
        }

        return $result;
    }
}