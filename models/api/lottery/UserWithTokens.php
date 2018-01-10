<?php

namespace app\models\api\lottery;

use app\components\ApiClient;
use app\models\api\dictionary;

class UserWithTokens {

    public $id;
    public $username;
    public $firstName;
    public $secondName;
    public $countryCode;
    public $city;
    public $tokens;

    public static function get()
    {
        $apiClient = new ApiClient('users/withTokens');

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
            foreach ($data as $object) {
                $user = new self;

                $user->id           = $object->{'_id'};
                $user->username     = $object->username;
                $user->firstName    = $object->firstName;
                $user->secondName   = $object->secondName;
                $user->countryCode  = $object->country;
                $user->city         = $object->city;
                $user->tokens       = $object->statistics->tokens;

                $result[] = $user;
            }
        }

        return $result;
    }

    /**
     * Returns country
     *
     * @return bool|mixed
     */
    public function getCountry()
    {
        return dictionary\Country::get($this->countryCode);
    }
}