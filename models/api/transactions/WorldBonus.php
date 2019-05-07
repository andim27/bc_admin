<?php

namespace app\models\api\transactions;

use app\components\ApiClient;

/**
 * Class WorldBonus
 * @package app\models\api\transactions
 */
class WorldBonus {

    /**
     * Returns all withdrawals
     *
     * @param $userId
     * @return array
     */
    public static function getByDate($from, $to)
    {
        $apiClient = new ApiClient('transactions/worldBonus/' . $from . '&' . $to);

        $response = $apiClient->get();

        return $response;
    }

    /**
     * Returns world bonus pay info
     *
     * @param $date
     * @param null $amount
     * @return mixed
     */
    public static function getWorldBonusPayInfo($date, $amount = null)
    {
        $apiBaseUrl = 'transactions/worldBonus/payInfo/' . $date;

        $apiUrl = is_null($amount) ? $apiBaseUrl : $apiBaseUrl . '/' . $amount;

        $apiClient = new ApiClient($apiUrl);

        $response = $apiClient->get();

        return $response;
    }

    /**
     * Set world bonus
     *
     * @param $date
     * @param $amount
     * @return bool
     */
    public static function setWorldBonus($users, $month, $year)
    {
        $apiClient = new ApiClient('transactions/worldBonus');

        $response = $apiClient->post([
            'month' => $month,
            'year' => $year,
            'users' => $users
        ], false);

        return $response == 'OK';
    }

    /**
     * Returns current world bonus
     *
     * @param $date
     * @return mixed
     */
    public static function getCurrentWorldBonus($date)
    {
        $apiClient = new ApiClient('transactions/worldBonus/current/' . $date);

        $response = $apiClient->get();

        return $response;
    }

    /**
     * Cancel current world bonus
     *
     * @param $date
     * @return bool
     */
    public static function cancelCurrentWorldBonus($date)
    {
        $apiClient = new ApiClient('transactions/worldBonus/current');

        $response = $apiClient->delete(['date' => $date], false);

        return $response == 'OK';
    }

}