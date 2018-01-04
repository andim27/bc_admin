<?php

namespace app\models\api\lottery;

use app\components\ApiClient;
use app\models\api\dictionary;

class User {

    public $userId;
    public $username;
    public $firstName;
    public $secondName;
    public $countryCode;
    public $city;
    public $date;

    /**
     * Adds winner
     *
     * @param $userId
     * @return bool
     */
    public static function winnerAdd($userId)
    {
        $apiClient = new ApiClient('lottery/winners/add');

        $response = $apiClient->post([
            'user_id' => $userId
        ], false);

        return $response == 'OK';
    }

    /**
     * Removes winner
     *
     * @param $userId
     * @return bool
     */
    public static function winnerRemove($userId)
    {
        $apiClient = new ApiClient('lottery/winners/delete');

        $response = $apiClient->post([
            'user_id' => $userId
        ], false);

        return $response == 'OK';
    }

    /**
     * Winners list
     *
     * @return bool|mixed
     */
    public static function winnerList()
    {
        $apiClient = new ApiClient('lottery/winners/list');

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Removes all winners
     *
     * @return bool
     */
    public static function winnerClear()
    {
        $apiClient = new ApiClient('lottery/winners/clear');

        $response = $apiClient->delete([], false);

        return $response == 'OK';

    }

    /**
     * Adds banned
     *
     * @param $userId
     * @return bool
     */
    public static function bannedAdd($userId)
    {
        $apiClient = new ApiClient('lottery/banned/add');

        $response = $apiClient->post([
            'user_id' => $userId
        ], false);

        return $response == 'OK';
    }

    /**
     * Removes banned
     *
     * @param $userId
     * @return bool
     */
    public static function bannedRemove($userId)
    {
        $apiClient = new ApiClient('lottery/banned/delete');

        $response = $apiClient->post([
            'user_id' => $userId
        ], false);

        return $response == 'OK';
    }

    /**
     * Banned list
     *
     * @return bool|mixed
     */
    public static function bannedList()
    {
        $apiClient = new ApiClient('lottery/banned/list');

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Removes all banned
     *
     * @return bool
     */
    public static function bannedClear()
    {
        $apiClient = new ApiClient('lottery/banned/clear');

        $response = $apiClient->delete([], false);

        return $response == 'OK';
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

                $user->userId       = $object->userId;
                $user->username     = $object->username;
                $user->firstName    = $object->firstName;
                $user->secondName   = $object->secondName;
                $user->countryCode  = $object->country;
                $user->city         = $object->city;
                $user->date         = strtotime($object->date);

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