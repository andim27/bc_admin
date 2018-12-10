<?php

namespace app\models\api;

use app\components\ApiClient;

class Career
{
    public $id;
    public $author;
    public $dateOfPublication;
    public $lang;
    public $isDelete;
    public $dateUpdate;
    public $dateCreate;
    public $body;
    public $title;

    /**
     * Returns career
     *
     * @param string $language
     * @return array
     */
    public static function get($language)
    {
        $apiClient = new ApiClient('careerPlan/' . $language);

        $response = $apiClient->get();

        return $response ? self::_getResults($response) : false;
    }

    /**
     * Add career
     *
     * @param $data
     * @return bool
     */
    public static function add($data)
    {
        $apiClient = new ApiClient('careerPlan');

        $response = $apiClient->post($data, false);

        return $response == 'OK';
    }

    /**
     * Update career
     *
     * @param $data
     * @return bool
     */
    public static function update($data)
    {
        $apiClient = new ApiClient('careerPlan');

        $response = $apiClient->put($data, false);

        return $response == 'OK';
    }

    /**
     * Convert response from API
     *
     * @param $data
     * @return array
     */
    private static function _getResults($object)
    {
        $result = false;

        if ($object) {
            $promotion = new self;

            $promotion->id         = $object->_id;
            $promotion->author     = $object->author;
            $promotion->lang       = $object->lang;
            $promotion->isDelete   = $object->isDelete;
            $promotion->dateUpdate = strtotime($object->dateUpdate);
            $promotion->dateCreate = strtotime($object->dateCreate);
            $promotion->body       = $object->body;
            $promotion->title      = $object->title;

            $result = $promotion;
        }

        return $result;
    }
}