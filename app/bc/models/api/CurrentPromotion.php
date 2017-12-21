<?php

namespace app\models\api;

use app\components\ApiClient;

class CurrentPromotion {

    public $qtyCompleteProm;

    /**
     * @return bool|mixed
     */
    public static function get()
    {
        $apiClient = new ApiClient('currentPromotions/travel');

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
                $currentPromotion = new self;

                $currentPromotion->qtyCompleteProm = $object->qtyCompleteProm;

                $result[] = $currentPromotion;
            }
        }

        return $result ? current($result) : false;
    }
}