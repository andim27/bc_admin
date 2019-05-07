<?php namespace app\models\api\transactions;

use app\components\ApiClient;

/**
 * Class StockBonus
 * @package app\models\api\transactions
 */
class StockBonus {

    /**
     * Returns stock bonus pay info
     *
     * @param $type
     * @param null $amount
     * @return mixed
     */
    public static function getStockBonusPayInfo($type, $amount = null)
    {
        $apiBaseUrl = 'transactions/stockBonus/payInfo/' . $type;

        $apiUrl = is_null($amount) ? $apiBaseUrl : $apiBaseUrl . '/' . $amount;

        $apiClient = new ApiClient($apiUrl);

        $response = $apiClient->get();

        return $response;
    }

    /**
     * Set stock bonus
     *
     * @param $type
     * @param $amount
     * @return bool
     */
    public static function setStockBonus($type, $amount)
    {
        $apiClient = new ApiClient('transactions/stockBonus');

        $response = $apiClient->post([
            'type' => $type,
            'amount' => $amount
        ], false);

        return $response == 'OK';
    }

    /**
     * Returns current stock bonus
     *
     * @param $type
     * @return mixed
     */
    public static function getCurrentStockBonus($type)
    {
        $apiClient = new ApiClient('transactions/stockBonus/current/' . $type);

        $response = $apiClient->get();

        return $response;
    }

    /**
     * Cancel current stock bonus
     *
     * @param $type
     * @return bool
     */
    public static function cancelCurrentStockBonus($type)
    {
        $apiClient = new ApiClient('transactions/stockBonus/current');

        $response = $apiClient->delete(['type' => $type], false);

        return $response == 'OK';
    }

}