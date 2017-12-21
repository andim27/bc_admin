<?php

namespace app\models\api;

use app\components\ApiClient;

class PinsHistory {

    public $id;
    public $productName;
    public $dateUpdate;
    public $dateCreate;
    public $used;
    public $isActivated;
    public $productPrice;
    public $pin;

    /**
     * Returns all used pins
     *
     * @param $userId
     * @return array
     */
    public static function get($userId)
    {
        $apiClient = new ApiClient('system/pin/' . $userId);

        $response = $apiClient->get();

        return self::_getResults($response);
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
                $pinsHistory = new self;

                $pinsHistory->id            = $object->_id;
                $pinsHistory->productName   = isset($object->productName) ? $object->productName : '';
                $pinsHistory->dateUpdate    = strtotime($object->dateUpdate);
                $pinsHistory->dateCreate    = strtotime($object->dateCreate);
                $pinsHistory->used          = isset($object->used) ? $object->used : '';
                $pinsHistory->isActivate   = isset($object->isActivate) ? $object->isActivate : '';
                $pinsHistory->productPrice  = isset($object->productPrice) ? $object->productPrice : '';
                $pinsHistory->pin           = isset($object->pin) ? $object->pin : '';

                $result[] = $pinsHistory;
            }
        }

        return $result;
    }

}