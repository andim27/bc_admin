<?php

namespace app\models\api\transactions;

use app\components\ApiClient;

class Charity {

    public $amount;
    public $forWhat;
    public $dateCreate;

    /**
     * Returns all charities
     *
     * @param $userId
     * @return array
     */
    public static function all($userId)
    {
        $apiClient = new ApiClient('transactions/charity/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Transfer money
     *
     * @param $userIdFrom
     * @param $userIdTo
     * @param $amount
     * @param string $forWhat
     */
    public static function transferMoney($userIdFrom, $userIdTo, $amount, $forWhat = '')
    {
        $apiClient = new ApiClient('transactions/transferMoney');

        $apiClient->post([
            'idFrom' => $userIdFrom,
            'idTo' => $userIdTo,
            'amount' => $amount,
            'forWhat' => $forWhat
        ]);
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return array
     */
    private static function _getResults($data)
    {
        $result = [];

        if ($data) {
            if (! is_array($data)) {
                $data = [$data];
            }
            foreach ($data as $object) {
                $charity = new self;

                $charity->amount     = $object->amount;
                $charity->forWhat    = $object->forWhat;
                $charity->dateCreate = strtotime($object->dateCreate);

                $result[] = $charity;
            }
        }

        return $result;
    }
}