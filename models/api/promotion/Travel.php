<?php

namespace app\models\api\promotion;

use app\components\ApiClient;

class Travel {

    public $id;
    public $username;
    public $email;
    public $turnover;


    public static function results()
    {
        $apiClient = new ApiClient('currentPromotions/travel/results');

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
                $promotion = new self;

                $promotion->id = $object->_id;
                $promotion->username = $object->username;
                $promotion->email = $object->email;
                $promotion->turnover = $object->promotions->travel->turnover;
                $promotion->dateComplete = ($object->promotions->travel->dateComplete && $object->promotions->travel->dateComplete != '0001-01-01T00:00:00.000Z') ? strtotime($object->promotions->travel->dateComplete) : '';

                $result[] = $promotion;
            }
        }

        return $result;
    }
}