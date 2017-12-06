<?php

namespace app\models\api\transactions;

use app\components\ApiClient;
use app\components\THelper;

class Withdrawal {

    public $amount;
    public $forWhat;
    public $dateCreate;
    public $confirmed;
    public $dateConfirm;
    public $usernameFrom;

    /**
     * Returns all withdrawals
     *
     * @param $userId
     * @return array
     */
    public static function all($userId)
    {
        $apiClient = new ApiClient('transactions/withdrawal/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
    }

    /**
     * Send withdrawal
     *
     * @param $data
     * @return bool
     */
    public static function send($data)
    {
        $apiClient = new ApiClient('transactions/withdrawal');

        return $apiClient->post($data, false) == 'OK';
    }

    public static function confirm($data)
    {
        $apiClient = new ApiClient('transactions/withdrawal');

        return $apiClient->put($data, false) == 'OK';
    }
    
    public static function remove($data)
    {
        $apiClient = new ApiClient('transactions/withdrawal');

        $response = $apiClient->delete($data, false);

        return $response == 'OK';
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
                $withdrawal = new self;

                $withdrawal->amount      = $object->amount;
                $withdrawal->forWhat     = $object->forWhat;
                $withdrawal->dateCreate  = strtotime($object->dateCreate);
                $withdrawal->confirmed   = $object->confirmed;
                $withdrawal->usernameFrom  = $object->usernameFrom;
                if (isset($object->dateConfirm)) {
                    $withdrawal->dateConfirm = strtotime($object->dateConfirm);
                }

                $result[] = $withdrawal;
            }
        }

        return $result;
    }

    /**
     * Returns status as string
     *
     * @return mixed|string
     */
    public function getStatusAsString()
    {
        switch ($this->confirmed) {
            case 0:
                $statusText = THelper::t('confirmed_not_verified');
            break;
            case 1:
                $statusText = THelper::t('confirmed_approved');
            break;
            case -1:
                $statusText = THelper::t('confirmed_canceled');
            break;
        }

        return $statusText;
    }
    
}